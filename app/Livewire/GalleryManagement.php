<?php

namespace App\Livewire;

use App\Models\Gallery;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GalleryManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $description, $file_path, $category, $featured = false;
    public $image; // For file upload
    public $galleryId;
    public $isEdit = false;
    public $search = '';
    public $filterCategory = '';
    public $showCreateForm = false;
    public $showEditForm = false;

    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'featured' => 'boolean',
        ];

        if (!$this->isEdit) {
            $rules['image'] = 'required|image|max:2048';
        } elseif ($this->image) {
            $rules['image'] = 'image|max:2048';
        }

        return $rules;
    }

    protected $messages = [
        'image.image' => 'The file must be an image (jpeg, png, bmp, gif, svg, or webp).',
        'image.max' => 'The image must not exceed 2MB in size.',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Gallery::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%')
                  ->orWhere('category', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        $galleries = $query->latest()->paginate(12);
        $categories = Gallery::distinct()->pluck('category');

        return view('livewire.gallery-management', compact('galleries', 'categories'));
    }

    public function openCreateForm()
    {
        Log::info('GalleryManagement: openCreateForm called');
        try {
            $this->resetForm();
            $this->showCreateForm = true;
            $this->isEdit = false;
            Log::info('GalleryManagement: Create form opened successfully');
        } catch (\Exception $e) {
            Log::error('GalleryManagement: Error opening create form', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function closeCreateForm()
    {
        Log::info('GalleryManagement: closeCreateForm called');
        try {
            $this->showCreateForm = false;
            $this->resetForm();
            Log::info('GalleryManagement: Create form closed successfully');
        } catch (\Exception $e) {
            Log::error('GalleryManagement: Error closing create form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function closeEditForm()
    {
        Log::info('GalleryManagement: closeEditForm called');
        try {
            $this->showEditForm = false;
            $this->resetForm();
            Log::info('GalleryManagement: Edit form closed successfully');
        } catch (\Exception $e) {
            Log::error('GalleryManagement: Error closing edit form', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function save()
    {
        Log::info('GalleryManagement: Save method called', [
            'isEdit' => $this->isEdit,
            'galleryId' => $this->galleryId,
            'title' => $this->title,
            'category' => $this->category,
            'hasImage' => !is_null($this->image)
        ]);

        try {
            Log::info('GalleryManagement: Starting validation');
            $this->validate();

            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'category' => $this->category,
                'featured' => (bool) $this->featured,
            ];

            // Handle image upload
            if ($this->image) {
                // Delete old image if editing
                if ($this->isEdit && $this->galleryId) {
                    $oldGallery = Gallery::find($this->galleryId);
                    if ($oldGallery && $oldGallery->file_path) {
                        if (Storage::disk('public')->exists($oldGallery->file_path)) {
                            Storage::disk('public')->delete($oldGallery->file_path);
                        }
                    }
                }

                // Store new image
                $imagePath = $this->image->store('gallery', 'public');
                $data['file_path'] = $imagePath;
            }

            if ($this->isEdit && $this->galleryId) {
                Log::info('GalleryManagement: Updating existing gallery item', ['galleryId' => $this->galleryId, 'data' => $data]);
                $gallery = Gallery::find($this->galleryId);
                if ($gallery) {
                    $gallery->update($data);
                    Log::info('GalleryManagement: Gallery item updated successfully');
                    $this->dispatch('show-toast', [
                        'type' => 'success',
                        'message' => 'Gallery item updated successfully!'
                    ]);
                    $this->closeEditForm();
                } else {
                    throw new \Exception('Gallery item not found');
                }
            } else {
                Log::info('GalleryManagement: Creating new gallery item', ['data' => $data]);
                $newGallery = Gallery::create($data);
                Log::info('GalleryManagement: Gallery item created successfully', ['newId' => $newGallery->id]);
                $this->dispatch('show-toast', [
                    'type' => 'success',
                    'message' => 'Gallery item created successfully!'
                ]);
                $this->closeCreateForm();
            }

            $this->resetForm();
            Log::info('GalleryManagement: Save operation completed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('GalleryManagement: Validation error during save', [
                'errors' => $e->errors(),
                'data' => $data ?? null
            ]);
            // Let Livewire handle validation errors automatically
            throw $e;
        } catch (\Exception $e) {
            Log::error('GalleryManagement: Error during save operation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data ?? null
            ]);
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        Log::info('GalleryManagement: Edit method called', ['galleryId' => $id]);
        try {
            $gallery = Gallery::find($id);
            if (!$gallery) {
                Log::error('GalleryManagement: Gallery item not found', ['id' => $id]);
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gallery item not found']);
                return;
            }

            $this->resetForm();
            $this->galleryId = $id;
            $this->title = $gallery->title;
            $this->description = $gallery->description;
            $this->file_path = $gallery->file_path;
            $this->category = $gallery->category;
            $this->featured = (bool) $gallery->featured;
            $this->isEdit = true;
            $this->showEditForm = true;

            Log::info('GalleryManagement: Edit form populated successfully', [
                'galleryId' => $id,
                'title' => $this->title,
                'showEditForm' => $this->showEditForm
            ]);
        } catch (\Exception $e) {
            Log::error('GalleryManagement: Error during edit operation', [
                'error' => $e->getMessage(),
                'galleryId' => $id
            ]);
        }
    }

    public function delete($id)
    {
        Log::info('GalleryManagement: Delete method called', ['galleryId' => $id]);
        try {
            $gallery = Gallery::find($id);
            if (!$gallery) {
                Log::error('GalleryManagement: Gallery item not found for deletion', ['id' => $id]);
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Gallery item not found']);
                return;
            }

            Log::info('GalleryManagement: Starting delete operation', [
                'galleryId' => $id,
                'title' => $gallery->title,
                'file_path' => $gallery->file_path
            ]);

            // Delete the image file if it exists
            if ($gallery->file_path) {
                Log::info('GalleryManagement: Attempting to delete image file', ['file_path' => $gallery->file_path]);
                if (Storage::disk('public')->exists($gallery->file_path)) {
                    Storage::disk('public')->delete($gallery->file_path);
                    Log::info('GalleryManagement: Image file deleted successfully');
                } else {
                    Log::info('GalleryManagement: Image file does not exist, skipping');
                }
            }

            $gallery->delete();
            Log::info('GalleryManagement: Gallery item deleted successfully', ['galleryId' => $id]);
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Gallery item deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('GalleryManagement: Error during delete operation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'galleryId' => $id
            ]);
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Error deleting gallery item: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'file_path', 'category', 'galleryId', 'isEdit', 'image', 'showCreateForm', 'showEditForm']);
        $this->featured = false;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterCategory']);
        $this->resetPage();
    }
}
