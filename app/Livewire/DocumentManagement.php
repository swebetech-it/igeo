<?php

namespace App\Livewire;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DocumentManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $description, $file_path, $file_type, $category, $is_public = true;
    public $document; // For file upload
    public $documentId;
    public $isEdit = false;
    public $showCreateForm = false;
    public $search = '';
    public $filterCategory = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:10240', // 10MB max
        'category' => 'required|string|max:255',
        'is_public' => 'boolean',
    ];

    protected $messages = [
        'document.mimes' => 'The file must be a document or image (pdf, doc, docx, xls, xlsx, ppt, pptx, txt, jpg, jpeg, png).',
        'document.max' => 'The file must not exceed 10MB in size.',
    ];

    public function render()
    {
        $query = Document::query();

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Document::distinct()->pluck('category');

        return view('livewire.document-management', compact('documents', 'categories'));
    }

    public function save()
    {
        // Custom validation rules for edit vs create
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'is_public' => 'boolean',
        ];

        if (!$this->isEdit) {
            // For new items, document is required
            $rules['document'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:10240';
        } elseif ($this->document) {
            // For edit, document is optional but must be valid if provided
            $rules['document'] = 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png|max:10240';
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'is_public' => $this->is_public,
        ];

        // Handle file upload
        if ($this->document) {
            // Delete old file if editing
            if ($this->isEdit) {
                $oldDocument = Document::find($this->documentId);
                if ($oldDocument && $oldDocument->file_path) {
                    $oldFilePath = str_replace('/storage/', '', $oldDocument->file_path);
                    if (Storage::disk('public')->exists($oldFilePath)) {
                        Storage::disk('public')->delete($oldFilePath);
                    }
                }
            }

            // Store new file
            $filePath = $this->document->store('documents', 'public');
            $data['file_path'] = '/storage/' . $filePath;
            $data['file_type'] = $this->document->getClientOriginalExtension();
        }

        if ($this->isEdit) {
            Document::find($this->documentId)->update($data);
        } else {
            Document::create($data);
        }

        $this->resetForm();
        session()->flash('message', $this->isEdit ? 'Document updated successfully!' : 'Document created successfully!');
    }

    public function edit($id)
    {
        $document = Document::find($id);
        $this->documentId = $id;
        $this->title = $document->title;
        $this->description = $document->description;
        $this->file_path = $document->file_path;
        $this->file_type = $document->file_type;
        $this->category = $document->category;
        $this->is_public = $document->is_public;
        $this->isEdit = true;
        $this->showCreateForm = true;
    }

    public function delete($id)
    {
        $document = Document::find($id);

        // Delete the file if it exists
        if ($document && $document->file_path) {
            $filePath = str_replace('/storage/', '', $document->file_path);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $document->delete();
        session()->flash('message', 'Document deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'file_path', 'file_type', 'category', 'is_public', 'documentId', 'isEdit', 'document']);
        $this->is_public = true;
        $this->showCreateForm = false;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterCategory']);
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showCreateForm = true;
    }

    public function closeCreateForm()
    {
        $this->resetForm();
    }
}
