<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Pail\ValueObjects\Origin\Console;

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

        // Log the entire request data and the parsed input
        error_log('ChatBotController payload received: ' . json_encode($request->all()));
        error_log('ChatBotController query parsed: ' . $query);

        $responses = [
            "Hi there! How can I help you today?",
            "That sounds interesting, tell me more!",
            "I am currently processing your request.",
            "Thanks for reaching out! I'm here to assist you.",
            "I hope you are having a wonderful day!",
            "I'm a public assistant chatbot."
        ];

        return $responses[array_rand($responses)];
    }

}