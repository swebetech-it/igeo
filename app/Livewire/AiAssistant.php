<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\GeminiService;
use App\Services\DatabaseContextService;

class AiAssistant extends Component
{
    public $message = '';
    public $chatHistory = [];
    public $isTyping = false;

    public function sendMessage(GeminiService $gemini, DatabaseContextService $context)
    {
        if (trim($this->message) === '') {
            return;
        }

        $userMessage = $this->message;
        $this->chatHistory[] = ['role' => 'user', 'content' => $userMessage];
        $this->message = '';
        $this->isTyping = true;

        $businessContext = $context->getBusinessContext();
        $schemaContext = $context->getSchemaContext();
        
        $systemPrompt = "You are a bilingual Sales Agent for Igeo (English and Swahili). \n" . 
                        "Current Context: " . $businessContext . "\n" . 
                        "Instructions: " . $schemaContext . "\n" .
                        "LEAD GENERATION: If the user provides their name, phone, or email, or expresses interest in a product/service, acknowledge it and try to get their contact details. \n" .
                        "OUTPUT FORMAT: If you identify lead information (Name, Phone, Email, Company, Message), append a JSON block at the end of your response like this: [LEAD_DATA:{\"name\":\"...\",\"phone\":\"...\",\"email\":\"...\",\"company\":\"...\",\"message\":\"...\"}]";
        
        $aiResponse = $gemini->generateContent($userMessage, $systemPrompt);

        // Process Lead Data if present
        if (preg_match('/\[LEAD_DATA:(.*?)\]/', $aiResponse, $matches)) {
            $leadData = json_decode($matches[1], true);
            if ($leadData) {
                \App\Models\Lead::create(array_merge($leadData, [
                    'session_id' => session()->getId(),
                    'status' => 'new'
                ]));
            }
            // Clean the response from the lead data tag
            $aiResponse = str_replace($matches[0], '', $aiResponse);
        }

        $this->chatHistory[] = ['role' => 'assistant', 'content' => trim($aiResponse)];
        $this->isTyping = false;
        
        $this->dispatch('messageSent');
    }

    public function clearChat()
    {
        $this->chatHistory = [];
    }

    public function render()
    {
        return view('livewire.ai-assistant');
    }
}
