<div class="flex flex-col h-screen" x-data="chatHandler()" x-init="init()">
    <!-- Center vertically and horizontally -->
    <div class="flex flex-col flex-grow w-full max-w-4xl m-auto">
        <!-- Chat container -->
        <div class="flex flex-col flex-grow p-6 bg-gray-800 rounded-lg shadow-md">
            <!-- Chat header -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">Chatbot</h2>
                <div class="flex">
                    <button wire:click="$set('deleteHistoryOpen', true)" class="mr-3 text-white focus:outline-none">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button wire:click="$set('settingsOpen', true)" class="text-white focus:outline-none">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

        <!-- Delete History Modal -->
        @if ($deleteHistoryOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center" 
            style="background-color: rgba(0,0,0,0.5);">
            <div class="p-6 bg-gray-800 rounded-lg">
                <button wire:click="$set('deleteHistoryOpen', false)" class="float-right text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="mb-4 text-lg font-bold">Delete Chat History</h2>
                <p>Are you sure you want to delete the chat history?</p>
                <div class="flex justify-end mt-4">
                    <button wire:click="$set('deleteHistoryOpen', false)" class="px-3 py-2 mr-3 text-white bg-gray-600 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cancel  
                    </button>
                    <button wire:click="deleteHistory" class="px-3 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete  
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Settings Modal -->
        @if ($settingsOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center" 
             style="background-color: rgba(0,0,0,0.5);">
            <div class="p-6 bg-gray-800 rounded-lg">
                <button wire:click="$set('settingsOpen', false)" class="float-right text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
                <h2 class="mb-4 text-lg font-bold">Settings</h2>
                <div>
                    <label class="block">API Key:</label>
                    <input type="text" wire:model="apiKey"
                           class="w-full px-3 py-2 text-white bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button wire:click="updateApiKey" class="w-full px-3 py-2 mt-4 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Confirm  
                </button>
            </div>
        </div>
        @endif

<!-- Chat messages -->
    <div class="flex-grow px-3 py-2 space-y-2 overflow-y-auto bg-gray-700 rounded-lg" x-ref="chatContainer" style="height: 300px;">
        @foreach ($messages as $msg)
        <div class="flex {{ $msg['isUser'] ? 'justify-end' : '' }}">
            <div class="px-3 py-2 text-sm rounded-lg {{ $msg['isUser'] ? 'bg-gray-600 text-white mr-auto' : 'bg-green-900 text-white ml-auto' }}">
                <span>{{ $msg['message'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

        
        <!-- Chat input -->
        <div class="flex mt-4">
            <input type="text" 
                   wire:model="newMessage"
                   wire:keydown.enter="sendMessage"
                   placeholder="Type your message..."
                   class="flex-1 px-3 py-2 text-white bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button wire:click="sendMessage"
                    class="px-3 py-2 ml-4 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Send  
            </button>
        </div>
        
      </div>
    </div>
</div>

<script>
function chatHandler() {
    return {
        init() {
            this.scrollChatToBottom();
            window.livewire.on('newMessage', message => {
                this.$nextTick(() => {
                    this.scrollChatToBottom();
                });
            });
        },
        scrollChatToBottom() {
            this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
        }
    }
}

</script>
