<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Chat;
use App\Models\ApiKey;  // Import the ApiKey model
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatComponent extends Component
{
    public $messages = [];
    public $newMessage = '';
    public $apiKey = '';
    public $settingsOpen = false;
    public $deleteHistoryOpen = false;

    public function mount()
    {
        // Load messages from database and sort them based on their 'order'
        $this->messages = Chat::orderBy('order', 'asc')->get()->toArray();
        
        // Load API key from database
        $this->apiKey = ApiKey::first()?->key ?? '';

        // If there are no messages, add the initial bot message
        if (empty($this->messages)) {
            $initialBotMessage = Chat::create(['message' => 'How can I assist you today?', 'isUser' => false, 'order' => 1]);
            $this->messages[] = $initialBotMessage->toArray();
        }
    }

    
    public function render()
    {
        return view('livewire.chat-component');
    }
    
    public function sendMessage()
    {
        // get the highest order value from the messages
        $order = Chat::max('order') ?? 0;
    
        // save user message to database and add to messages array
        $message = Chat::create(['message' => $this->newMessage, 'isUser' => true, 'order' => $order + 1]);
        $this->messages[] = $message->toArray();
    
        // clear new message input
        $this->newMessage = '';
    
        // Emit the new message event
        $this->emit('newMessage', $message->toArray());
    
        // get bot response and add to messages array (skip if it's the initial bot message)
        if (count($this->messages) > 1) {
            $botResponse = $this->getBotResponse();
            Log::debug('Order value: ' . ($order + 2));
            $this->messages[] = Chat::create(['message' => $botResponse, 'isUser' => false, 'order' => $order + 2])->toArray();
        }
    }
    
    public function getBotResponse()
    {
        // Fetch the last three user and bot messages
        $botMessages = Chat::where('isUser', false)->orderBy('order', 'desc')->take(3)->pluck('message')->toArray();
        $userMessages = Chat::where('isUser', true)->orderBy('order', 'desc')->take(3)->pluck('message')->toArray();
    
        // Reverse the arrays to ensure the correct order
        $botMessages = array_reverse($botMessages);
        $userMessages = array_reverse($userMessages);
        
        // Make a POST request to the chat API
        $response = Http::post('http://api:8000/chat', [
            'api_key' => $this->apiKey,
            'user_messages' => $userMessages,
            'bot_messages' => $botMessages,
        ]);
    
        // If the request was successful, return the bot's response
        if ($response->successful()) {
            return $response['response'];
        }
    
        // If the request failed, return a default message
        return 'Sorry, I am currently unable to assist you.';
    }
     
        
    public function updateApiKey()
    {
        // update API key in database
        ApiKey::updateOrCreate([], ['key' => $this->apiKey]);
    
        // close settings modal
        $this->settingsOpen = false;
    }
    
    public function deleteHistory()
    {
        // delete chat history from database
        Chat::truncate();
    
        // clear messages array
        $this->messages = [];
    
        // close delete history modal
        $this->deleteHistoryOpen = false;

        $this->redirect("/");
    }
}
