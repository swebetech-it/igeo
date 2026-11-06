<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold text-gray-900">Order Management</h1>
            <p class="mt-2 text-sm text-gray-700">Track and manage customer orders and sales status.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <div class="flex-1 max-w-xs">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search orders..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div>
            <select wire:model.live="statusFilter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg bg-white">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Order ID</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Payment</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($orders as $order)
                            <tr>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">#{{ $order->id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-xs">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 font-bold">TSh {{ number_format($order->total_amount, 0) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <select wire:change="updateStatus({{ $order->id }}, $event.target.value)" class="text-xs rounded border-gray-300 focus:ring-indigo-500">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <select wire:change="updatePaymentStatus({{ $order->id }}, $event.target.value)" class="text-xs rounded border-gray-300 focus:ring-indigo-500">
                                        <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <button wire:click="viewOrder({{ $order->id }})" class="text-indigo-600 hover:text-indigo-900">View Details</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    @if($showDetailModal && $selectedOrder)
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Order #{{ $selectedOrder->id }} Details</h3>
                        <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-500">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase">Customer Information</h4>
                            <p class="mt-2 text-lg font-medium">{{ $selectedOrder->customer_name }}</p>
                            <p class="text-gray-600">{{ $selectedOrder->customer_phone }}</p>
                            @if($selectedOrder->notes)
                                <div class="mt-4 p-3 bg-gray-50 rounded italic text-sm text-gray-600">
                                    "{{ $selectedOrder->notes }}"
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase">Order Summary</h4>
                            <p class="mt-2 text-3xl font-black text-indigo-600">TSh {{ number_format($selectedOrder->total_amount, 0) }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $selectedOrder->created_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-4">Items</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($selectedOrder->items as $item)
                                <tr>
                                    <td class="py-4 text-sm font-medium text-gray-900">{{ $item->product->name }}</td>
                                    <td class="py-4 text-right text-sm text-gray-500">TSh {{ number_format($item->price, 0) }}</td>
                                    <td class="py-4 text-right text-sm text-gray-500">{{ $item->quantity }}</td>
                                    <td class="py-4 text-right text-sm font-bold text-gray-900">TSh {{ number_format($item->subtotal, 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-bold hover:bg-gray-300 transition-colors">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
