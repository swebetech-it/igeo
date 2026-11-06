<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('agent.api_key');
        $this->model = config('agent.model', 'gemini-1.5-flash');
        $this->client = new Client([
            'base_uri' => 'https://generativelanguage.googleapis.com/v1beta/models/',
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function generateContent($prompt, $systemPrompt = null)
    {
        if (!$this->apiKey) {
            return 'Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file.';
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";
        
        $contents = [];

        if ($systemPrompt) {
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => "SYSTEM INSTRUCTION: You are a bilingual assistant (English and Swahili). Always respond in the language the user is using. " . $systemPrompt]],
            ];
        }
        
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $prompt]],
        ];

        try {
            $response = $this->client->post($url, [
                'json' => [
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 2048,
                    ],
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);
            return $body['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from AI.';

        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            return 'Error communicating with AI. Please check your API key and network connection.';
        }
    }
}
