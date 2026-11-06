<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;

class Shop extends Component
{
    public $products;
    public $cart = [];
    public $showOrderForm = false;
    
    public $customer_name;
    public $customer_phone;
    public $notes;

    protected $rules = [
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->products = Product::active()->get();
    }

    public function addToCart($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }
        
        session()->flash('cart_updated', 'Product added to cart!');
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
        }
    }

    public function toggleOrderForm()
    {
        $this->showOrderForm = !$this->showOrderForm;
    }

    public function placeOrder(WhatsAppService $whatsapp)
    {
        $this->validate();

        if (empty($this->cart)) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($this->cart as $productId => $quantity) {
                $product = Product::findOrFail($productId);
                
                if ($product->stock_quantity < $quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}. Only {$product->stock_quantity} remaining.");
                }

                $subtotal = $product->price * $quantity;
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                // Decrement stock
                $product->decrement('stock_quantity', $quantity);
            }

            $order = Order::create([
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'total_amount' => $totalAmount,
                'notes' => $this->notes,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();

            // Send Notification
            $whatsapp->sendOrderNotification($order);

            $this->cart = [];
            $this->showOrderForm = false;
            $this->reset(['customer_name', 'customer_phone', 'notes']);

            session()->flash('message', 'Order placed successfully! We will contact you soon.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.shop')->layout('layouts.web');
    }
}
