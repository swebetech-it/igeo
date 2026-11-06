<div class="fixed bottom-6 right-6 z-50">
    <!-- Chat Toggle Button -->
    <button id="chat-toggle" onclick="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-2xl transition-all hover:scale-110 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chat-window" class="hidden absolute bottom-20 right-0 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col transition-all duration-300 transform scale-95 opacity-0 origin-bottom-right">
        <!-- Header -->
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between text-white">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-lg">AI</div>
                <div>
                    <h3 class="font-bold leading-none">Igeo Sales Agent</h3>
                    <p class="text-xs text-blue-100 mt-1">Online & Ready to Help</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white hover:text-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18" />
                </svg>
            </button>
        </div>

        <!-- Chat History -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 max-h-[400px] min-h-[300px] bg-gray-50 dark:bg-gray-900">
            @if(empty($chatHistory))
                <div class="text-center py-10">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">👋</div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Hello! How can I assist you with our services or products today?</p>
                </div>
            @endif

            @foreach($chatHistory as $chat)
                <div class="flex {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[85%] px-4 py-2 rounded-2xl {{ $chat['role'] === 'user' ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 shadow-sm border border-gray-100 dark:border-gray-700 rounded-tl-none' }}">
                        <p class="text-sm prose dark:prose-invert prose-sm">{{ $chat['content'] }}</p>
                    </div>
                </div>
            @endforeach

            @if($isTyping)
                <div class="flex justify-start">
                    <div class="bg-white dark:bg-gray-800 px-4 py-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 rounded-tl-none">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce delay-75"></div>
                            <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce delay-150"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer / Input -->
        <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
            <form wire:submit.prevent="sendMessage" class="flex items-center space-x-2">
                <input type="text" wire:model="message" placeholder="Ask about services or buy..." class="flex-1 bg-gray-100 dark:bg-gray-900 border-none rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 dark:text-white">
                <button type="submit" class="bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
            <div class="mt-2 text-center">
                <button wire:click="clearChat" class="text-[10px] text-gray-400 hover:text-gray-600 uppercase tracking-widest font-bold">Clear Conversation</button>
            </div>
        </div>
    </div>

    <script>
        function toggleChat() {
            const window = document.getElementById('chat-window');
            if (window.classList.contains('hidden')) {
                window.classList.remove('hidden');
                setTimeout(() => {
                    window.classList.remove('scale-95', 'opacity-0');
                }, 10);
            } else {
                window.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    window.classList.add('hidden');
                }, 300);
            }
        }

        document.addEventListener('livewire:initialized', () => {
            @this.on('messageSent', () => {
                const chatContainer = document.getElementById('chat-messages');
                chatContainer.scrollTop = chatContainer.scrollHeight;
            });
        });
    </script>
</div>
