<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SuggestIQController extends Controller
{
    public function index()
    {
        return view('user.suggest');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:500',
        ]);

        $query = $request->input('query');
        
        try {
            $apiKey = env('GROQ_API_KEY');
            $model = env('GROQ_MODEL', 'mixtral-8x7b-32768');
            
            if (!$apiKey) {
                return response()->json(['error' => 'Groq API key not configured.'], 500);
            }

            $systemPrompt = "You are an advanced interactive recommendation and suggestion engine. Given the user's inquiry, provide a JSON object containing a 'suggestions' array with between 4 and 7 suggestions.\n\n" .
                "Each suggestion must be a JSON object strictly complying with the following schema:\n" .
                "{\n" .
                "  \"name\": \"Distinct title or name of option (max 5 words)\",\n" .
                "  \"sub\": \"Primary descriptor / classification (e.g. 'Boutique Hotel · 1.2km' or 'Restaurant · $$')\",\n" .
                "  \"type\": \"Exactly one of: 'hotel', 'restaurant', 'attraction', 'transport', 'shop', 'health', or 'default'\",\n" .
                "  \"desc\": \"A single informative, compelling, and punchy sentence describing this specific suggestion.\"\n" .
                "}\n\n" .
                "You must return a valid JSON object like: { \"suggestions\": [ ... ] }";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $query],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
                'max_tokens' => 1500,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (!empty($data['choices'][0]['message']['content'])) {
                    $content = $data['choices'][0]['message']['content'];
                    $parsed = json_decode($content, true);
                    
                    if (isset($parsed['suggestions']) && is_array($parsed['suggestions'])) {
                        return response()->json($parsed['suggestions']);
                    }
                    
                    // Fallback if model returned array directly despite prompt
                    if (is_array($parsed) && !isset($parsed['suggestions'])) {
                        return response()->json($parsed);
                    }
                }
                
                return response()->json(['error' => 'Invalid response format from AI.'], 500);
            } else {
                Log::error('Groq API Error: ' . $response->body());
                return response()->json(['error' => 'Failed to fetch suggestions from AI provider.'], 500);
            }
            
        } catch (\Exception $e) {
            Log::error('SuggestIQ Exception: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
