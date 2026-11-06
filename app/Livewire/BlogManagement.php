<?php

namespace App\Livewire;

use App\Models\Blog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BlogManagement extends Component
{
    use WithPagination;

    public $title, $content, $excerpt, $slug, $status = 'draft', $featured_image;
    public $blogId;
    public $isEdit = false;
    public $search = '';
    public $statusFilter = 'all';
    public $showCreateForm = false;
    public $showEditForm = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'slug' => 'required|string|max:255|unique:blogs,slug' . ($this->isEdit ? ',' . $this->blogId : ''),
            'status' => 'required|in:draft,published,archived',
            'featured_image' => 'nullable|string',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Blog::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('content', 'like', '%'.$this->search.'%')
                  ->orWhere('excerpt', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $blogs = $query->latest()->paginate(10);

        return view('livewire.blog-management', compact('blogs'));
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

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->resetForm();
    }

    public function save()
    {
        try {
            $this->validate();

            $data = [
                'title' => $this->title,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'slug' => $this->slug,
                'status' => $this->status,
                'featured_image' => $this->featured_image,
            ];

            if ($this->status === 'published') {
                $data['published_at'] = now();
            }

            if ($this->isEdit) {
                Blog::find($this->blogId)->update($data);
                $this->dispatch('toast',
                    type: 'success',
                    message: 'Blog post updated successfully!'
                );
                $this->closeEditForm();
            } else {
                Blog::create($data);
                $this->dispatch('toast',
                    type: 'success',
                    message: 'Blog post created successfully!'
                );
                $this->closeCreateForm();
            }

            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('toast',
                type: 'error',
                message: 'An error occurred: ' . $e->getMessage()
            );
        }
    }

    public function edit($id)
    {
        $blog = Blog::find($id);
        $this->blogId = $id;
        $this->title = $blog->title;
        $this->content = $blog->content;
        $this->excerpt = $blog->excerpt;
        $this->slug = $blog->slug;
        $this->status = $blog->status;
        $this->featured_image = $blog->featured_image;
        $this->isEdit = true;
        $this->showEditForm = true;
    }

    public function delete($id)
    {
        try {
            Blog::find($id)->delete();
            $this->dispatch('toast',
                type: 'success',
                message: 'Blog post deleted successfully!'
            );
        } catch (\Exception $e) {
            $this->dispatch('toast',
                type: 'error',
                message: 'Error deleting blog post: ' . $e->getMessage()
            );
        }
    }

    public function resetForm()
    {
        $this->reset(['title', 'content', 'excerpt', 'slug', 'status', 'featured_image', 'blogId', 'isEdit', 'showCreateForm', 'showEditForm']);
        $this->status = 'draft';
    }

    public function generateSlug()
    {
        if ($this->title) {
            $this->slug = Str::slug($this->title);
        }
    }
}
