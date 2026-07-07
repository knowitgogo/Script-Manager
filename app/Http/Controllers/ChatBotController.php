<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Pail\ValueObjects\Origin\Console;
use Illuminate\Support\Facades\Http;

class ChatBotController extends BaseController
{
    public function sendMessage(Request $request)
    {
        $query = $request->input('query');
        error_log('ChatBotController: ' . $query);
        return "test";
    }
    public function sendUserMessage(Request $request)
    {
        $query = $request->input('message') ?? $request->input('query');
        $pageContext = $request->input('pageContext');

        // Print/log the query and pageContext to the console/terminal
        error_log('--- ChatBot Request Context ---');
        error_log('User Query: ' . $query);
        error_log('Page Context: ' . json_encode($pageContext, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        // Attempt to get a response from Groq AI
        try {
            $apiKey = env('GROQ_API_KEY');
            $model = env('GROQ_MODEL', 'groq/compound-mini');
            if ($apiKey) {
                $systemPrompt = "You are a short and crisp chatbot assistant. " .
                    "You must ONLY answer questions based on the provided page context. " .
                    "Keep your answers extremely short and crisp, and suggest/recommend actions or options based on the context and user query. " .
                    "Format your responses using HTML tags (like <strong>, <ul>, <li>, <br>) instead of Markdown to structure and format your answers.";

                $userMessage = "Page Context:\n" . json_encode($pageContext, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n\nUser Query: " . $query;

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.groq.com/openai/v1/chat/completions', [
                            'model' => $model,
                            'messages' => [
                                ['role' => 'system', 'content' => $systemPrompt],
                                ['role' => 'user', 'content' => $userMessage],
                            ],
                            'max_tokens' => 500,
                        ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data['choices'][0]['message']['content'])) {
                        $reply = trim($data['choices'][0]['message']['content']);
                    }
                } else {
                    error_log('Groq API response failed: ' . $response->status() . ' - ' . $response->body());
                }
            }
        } catch (\Exception $e) {
            error_log('Groq API error: ' . $e->getMessage());
        }

        // If we got a reply from the AI, print and return it
        if (isset($reply)) {
            error_log('--- ChatBot Response ---');
            error_log('Response: ' . $reply);
            return $reply;
        }

        // If no AI response was obtained, print and return the busy fallback message
        $reply = 'Chat bot is busy, try again';
        error_log('--- ChatBot Response (Fallback) ---');
        error_log('Response: ' . $reply);
        return $reply;
    }

}