<div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">Our Shop</h2>
            <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">Premium products for your engineering and construction needs.</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Product List -->
            <div class="md:col-span-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($products as $product)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-transform hover:scale-105 border border-gray-100 dark:border-gray-700">
                        <div class="h-48 bg-gray-200 dark:bg-gray-700 relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <span class="text-4xl text-gray-400">📦</span>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-blue-600 text-white px-2 py-1 rounded text-sm font-bold">
                                TSh {{ number_format($product->price, 0) }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $product->short_description }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                    {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Out of stock' }}
                                </span>
                                <button wire:click="addToCart({{ $product->id }})" 
                                    {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors {{ $product->stock_quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 border border-gray-100 dark:border-gray-700 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                        <span class="mr-2">🛒</span> Your Cart
                    </h3>

                    @if(empty($cart))
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">Your cart is empty</p>
                    @else
                        <div class="space-y-4 mb-6">
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $qty)
                                @php 
                                    $p = $products->firstWhere('id', $id);
                                    $sub = $p->price * $qty;
                                    $total += $sub;
                                @endphp
                                <div class="flex justify-between items-center text-sm">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $p->name }}</p>
                                        <p class="text-gray-500">x{{ $qty }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 dark:text-white">TSh {{ number_format($sub, 0) }}</p>
                                        <button wire:click="removeFromCart({{ $id }})" class="text-red-500 text-xs hover:underline">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                            <div class="border-t pt-4 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                                <span class="text-xl font-extrabold text-blue-600">TSh {{ number_format($total, 0) }}</span>
                            </div>
                        </div>

                        @if(!$showOrderForm)
                            <button wire:click="toggleOrderForm" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all shadow-lg">
                                Checkout Now
                            </button>
                        @endif
                    @endif

                    @if($showOrderForm)
                        <div class="mt-6 border-t pt-6">
                            <h4 class="text-lg font-semibold mb-4 dark:text-white">Customer Details</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                    <input type="text" wire:model="customer_name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number (WhatsApp)</label>
                                    <input type="text" wire:model="customer_phone" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="e.g. 255712345678">
                                    @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Notes (Optional)</label>
                                    <textarea wire:model="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="toggleOrderForm" class="flex-1 py-2 bg-gray-200 text-gray-800 font-bold rounded-lg">Cancel</button>
                                    <button wire:click="placeOrder" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg">Confirm Order</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
