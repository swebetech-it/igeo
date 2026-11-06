<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $name, $short_description, $description, $price, $stock_quantity, $is_active = true, $image;
    public $productId;
    public $isEdit = false;
    public $search = '';
    public $perPage = 10;

    // UI state
    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $deleteProductId = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:1024',
        ];
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function openEditForm()
    {
        $this->showEditForm = true;
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('short_description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.product-management', compact('products'));
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->isEdit) {
            Product::find($this->productId)->update($data);
            $message = 'Product updated successfully!';
        } else {
            Product::create($data);
            $message = 'Product created successfully!';
        }

        $this->closeCreateForm();
        $this->closeEditForm();
        $this->dispatch('show-toast', ['message' => $message, 'type' => 'success']);
    }

    public function edit($id)
    {
        $this->resetForm();
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock_quantity = $product->stock_quantity;
        $this->is_active = $product->is_active;
        $this->isEdit = true;
        $this->showEditForm = true;
    }

    public function confirmDelete($id)
    {
        $this->deleteProductId = $id;
        $this->showDeleteConfirm = true;
    }

    public function delete()
    {
        if ($this->deleteProductId) {
            Product::find($this->deleteProductId)->delete();
            $this->dispatch('show-toast', ['message' => 'Product deleted successfully!', 'type' => 'success']);
        }
        $this->showDeleteConfirm = false;
    }

    public function resetForm()
    {
        $this->reset(['name', 'short_description', 'description', 'price', 'stock_quantity', 'is_active', 'image', 'productId', 'isEdit']);
    }
}
