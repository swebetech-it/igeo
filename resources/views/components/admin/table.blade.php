@props(['headers' => [], 'items' => [], 'emptyMessage' => 'No items found'])

<div class="admin-card">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        {{ $item }}
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 flex items-center justify-center text-gray-500 mb-4">
                                    <i class="fas fa-table"></i>
                                </div>
                                <p class="text-gray-500">{{ $emptyMessage }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>