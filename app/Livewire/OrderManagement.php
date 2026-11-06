<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    
    // UI State
    public $selectedOrder = null;
    public $showDetailModal = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewOrder($id)
    {
        $this->selectedOrder = Order::with('items.product')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function updateStatus($id, $status)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);
        $this->dispatch('show-toast', ['message' => 'Order status updated!', 'type' => 'success']);
        
        if ($this->selectedOrder && $this->selectedOrder->id == $id) {
            $this->selectedOrder->refresh();
        }
    }

    public function updatePaymentStatus($id, $status)
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => $status]);
        $this->dispatch('show-toast', ['message' => 'Payment status updated!', 'type' => 'success']);
        
        if ($this->selectedOrder && $this->selectedOrder->id == $id) {
            $this->selectedOrder->refresh();
        }
    }

    public function render()
    {
        $orders = Order::query()
            ->when($this->search, function ($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.order-management', compact('orders'));
    }
}
