<!-- Toast Notification Component -->
<div
    x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            window.addEventListener('show-toast', (event) => {
                this.message = event.detail.message;
                this.type = event.detail.type || 'success';
                this.show = true;
                setTimeout(() => {
                    this.show = false;
                }, 4000);
            });
        }
    }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed top-0 right-0 mt-4 mr-4 z-50 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
    x-cloak
>
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <!-- Success Icon -->
                <template x-if="type === 'success'">
                    <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                </template>

                <!-- Error Icon -->
                <template x-if="type === 'error'">
                    <div class="w-6 h-6 bg-red-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-white text-sm"></i>
                    </div>
                </template>

                <!-- Warning Icon -->
                <template x-if="type === 'warning'">
                    <div class="w-6 h-6 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation text-white text-sm"></i>
                    </div>
                </template>

                <!-- Info Icon -->
                <template x-if="type === 'info'">
                    <div class="w-6 h-6 bg-blue-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-info text-white text-sm"></i>
                    </div>
                </template>
            </div>

            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900" x-text="message"></p>
            </div>

            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="h-1 bg-gray-200">
        <div
            x-show="show"
            class="h-full transition-all duration-4000 ease-linear"
            :class="{
                'bg-green-500': type === 'success',
                'bg-red-500': type === 'error',
                'bg-yellow-500': type === 'warning',
                'bg-blue-500': type === 'info'
            }"
            x-transition:enter="transition-all duration-4000 ease-linear"
            x-transition:enter-start="w-full"
            x-transition:enter-end="w-0"
        ></div>
    </div>
</div>
