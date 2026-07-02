<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class SuggestIQController extends BaseController
{
    public function index()
    {
        return view('user.suggest');
    }

    public function generate(Request $request): JsonResponse
    {
        // ── 0. Token Extraction & Verification ────────────────────────
        $tokenString = $request->bearerToken() 
            ?? $request->header('X-API-Key') 
            ?? $request->input('api_key');

        if (!$tokenString) {
            return response()->json(['error' => 'API token missing.'], 401);
        }

        $tokenRecord = \App\Models\Token::with('user')->where('token', $tokenString)->first();

        if (!$tokenRecord || $tokenRecord->disabled) {
            return response()->json(['error' => 'Invalid or disabled token.'], 401);
        }

        if ($tokenRecord->expires_at && now()->greaterThan($tokenRecord->expires_at)) {
            return response()->json(['error' => 'Token has expired.'], 401);
        }

        if (!$tokenRecord->user || $tokenRecord->user->disabled) {
            return response()->json(['error' => 'User account is disabled.'], 401);
        }

        // ── 0.5. Rate Limiting ────────────────────────────────────────
        $limiterKey = 'suggest_api_' . $tokenString;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($limiterKey, 10)) {
            return response()->json(['error' => 'Too Many Requests. Rate limit exceeded.'], 429);
        }
        \Illuminate\Support\Facades\RateLimiter::hit($limiterKey, 60);

        // ── 1. Validate input ──────────────────────────────────────────
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:500',
            'pageContext' => 'nullable|array|max:20',
            'pageContext.*' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $query = trim($request->input('query'));
        $pageContext = $request->input('pageContext', []);

        // ── 2. Slim the context — only keep what AI needs ──────────────
        //    (Even if frontend sends full objects, we strip here as safety)
        $hotels = collect($pageContext)
            ->filter(fn($h) => !empty($h['name']))
            ->values()
            ->map(fn($h, $i) => [
                'id' => $h['id'] ?? $i,
                'name' => (string) ($h['name'] ?? ''),
                'location' => (string) ($h['location'] ?? ''),
                'price' => (string) ($h['price'] ?? ''),
                'rating' => (string) ($h['rating'] ?? ''),
                'tags' => array_slice((array) ($h['tags'] ?? []), 0, 4),
            ])
            ->toArray();

        // ── 3. Build tight prompts ─────────────────────────────────────
        $hotelsJson = json_encode($hotels, JSON_UNESCAPED_UNICODE);

        $systemPrompt = <<<PROMPT
You are a hotel recommendation engine embedded in a hotel listing website.

TASK: Given a user query and a list of hotels, pick  hotels that best match the query.

RESPONSE RULES — follow exactly:
- Return ONLY a JSON object. No explanation, no markdown, no extra text.
- The object must have one key: "suggestions" (an array).
- Each suggestion must have these exact keys:
    "id"   → integer, the hotel's id from the input list
    "name" → string, copied exactly from the input list
    "sub"  → string, format: "Location · Rating★" (e.g. "Aspen, CO · 4.6★")
    "type" → always the string "hotel"
    "desc" → string, one sentence max 20 words explaining why it suits the user query

EXAMPLE OUTPUT:
{
  "suggestions": [
    {
      "id": 0,
      "name": "Mountain View Lodge",
      "sub": "Aspen, CO · 4.6★",
      "type": "hotel",
      "desc": "Perfect for families with ski access and cozy mountain atmosphere."
    }
  ]
}
PROMPT;

        $userMessage = "User query: \"{$query}\"\n\nHotels list:\n{$hotelsJson}";

        // ── 4. Call the AI ─────────────────────────────────────────────
        $apiKey = env('GROQ_API_KEY');
        $model = env('GROQ_MODEL', 'llama-3.3-70b-versatile'); // fast, free, supports json_object

        if (!$apiKey) {
            Log::error('SuggestIQ: GROQ_API_KEY not set');
            return response()->json(['error' => 'API key not configured.'], 500);
        }

        try {
            $response = Http::timeout(25)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'response_format' => ['type' => 'json_object'], // forces valid JSON ✅
                    'temperature' => 0.3,   // low = predictable format
                    'max_tokens' => 700,   // 3–5 suggestions is well within this
                ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('SuggestIQ: Connection timeout — ' . $e->getMessage());
            return response()->json(['error' => 'AI provider timed out. Please try again.'], 503);
        }

        // ── 5. Handle HTTP errors from Groq ───────────────────────────
        if (!$response->successful()) {
            $body = $response->json();
            $msg = $body['error']['message'] ?? $response->body();
            Log::error("SuggestIQ: Groq HTTP {$response->status()} — {$msg}");
            return response()->json(['error' => 'AI provider error: ' . $msg], 502);
        }

        // ── 6. Parse the content safely ───────────────────────────────
        $content = $response->json('choices.0.message.content') ?? '';

        if (empty($content)) {
            Log::error('SuggestIQ: Empty content in AI response', $response->json() ?? []);
            return response()->json([
                [
                    'id' => null,
                    'name' => 'Hotels not available with this type of prompt',
                    'sub' => '',
                    'type' => 'default',
                    'desc' => 'The AI returned an empty response. Please try a different search query.',
                ]
            ]);
        }

        // Strip markdown fences just in case (some models still add them)
        $content = trim(preg_replace('/^```(?:json)?\s*|\s*```$/m', '', $content));

        $parsed = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('SuggestIQ: JSON parse failed — ' . $content);
            return response()->json(['error' => 'AI returned malformed JSON.'], 500);
        }

        // ── 7. Validate the shape ─────────────────────────────────────
        $suggestions = $parsed['suggestions'] ?? null;

        if (!is_array($suggestions) || empty($suggestions)) {
            Log::error('SuggestIQ: Missing suggestions array', $parsed);
            return response()->json([
                [
                    'id' => null,
                    'name' => 'Hotels not available with this type of prompt',
                    'sub' => '',
                    'type' => 'default',
                    'desc' => 'Please try a different search query.',
                ]
            ]);
        }

        // ── 8. Sanitise each suggestion ───────────────────────────────
        $allowedTypes = ['hotel', 'restaurant', 'attraction', 'transport', 'shop', 'health', 'default'];

        $clean = collect($suggestions)->map(fn($s) => [
            'id' => isset($s['id']) ? (int) $s['id'] : null,
            'name' => (string) ($s['name'] ?? 'Unknown'),
            'sub' => (string) ($s['sub'] ?? ''),
            'type' => in_array($s['type'] ?? '', $allowedTypes) ? $s['type'] : 'hotel',
            'desc' => (string) ($s['desc'] ?? ''),
        ])->values()->toArray();

        Log::info('SuggestIQ: Returning ' . count($clean) . ' suggestions for query: ' . $query);

        // ── 9. Log Usage ──────────────────────────────────────────────
        \App\Models\TokenUsage::create([
            'token_id' => $tokenRecord->id,
            'user_id' => $tokenRecord->user_id,
            'query' => $query,
            'response' => json_encode($clean, JSON_UNESCAPED_UNICODE),
        ]);
        $tokenRecord->increment('usage_count');

        return response()->json($clean);
    }
}
