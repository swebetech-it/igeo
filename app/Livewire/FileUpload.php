<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FileUpload extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $category;
    public $image;
    public $preview_image;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'nullable|max:1000',
        'category' => 'required|max:100',
        'image' => 'required|image|max:10240', // 10MB max
    ];

    public function mount()
    {
        Log::info('FileUpload component mounted');
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:10240',
        ]);

        if ($this->image) {
            $this->preview_image = $this->image->temporaryUrl();
            Log::info('Image uploaded for preview', ['filename' => $this->image->getClientOriginalName()]);
        }
    }

    public function save()
    {
        Log::info('Save method called', [
            'title' => $this->title,
            'category' => $this->category,
            'has_image' => !is_null($this->image)
        ]);

        $this->validate();

        try {
            // Generate unique filename
            $filename = Str::uuid() . '.' . $this->image->getClientOriginalExtension();

            // Store in public disk under gallery folder
            $imagePath = $this->image->storeAs('gallery', $filename, 'public');

            // Create gallery record
            $gallery = Gallery::create([
                'title' => $this->title,
                'description' => $this->description,
                'category' => $this->category,
                'file_path' => $imagePath,
            ]);

            Log::info('Gallery item created successfully', ['id' => $gallery->id, 'path' => $imagePath]);

            session()->flash('message', 'Image uploaded successfully!');

            // Reset form
            $this->reset(['title', 'description', 'category', 'image', 'preview_image']);

            // Redirect back to gallery
            return redirect()->route('admin.gallery');

        } catch (\Exception $e) {
            Log::error('Error saving gallery item', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error uploading image. Please try again.');
        }
    }

    public function cancel()
    {
        Log::info('Cancel button clicked - redirecting to gallery');
        return redirect()->route('admin.gallery');
    }

    public function render()
    {
        return view('livewire.file-upload');
    }
}
