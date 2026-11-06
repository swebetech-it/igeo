<?php

namespace App\Livewire;

use App\Models\Career;
use Livewire\Component;
use Livewire\WithPagination;

class CareerManagement extends Component
{
    use WithPagination;

    public $title, $description, $department, $location, $type = 'Full-time', $is_active = true, $deadline;
    public $careerId;
    public $isEdit = false;
    public $search = '';
    public $statusFilter = 'all';
    public $showCreateForm = false;
    public $showEditForm = false;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:Full-time,Part-time,Contract,Internship',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
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
        $query = Career::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%')
                  ->orWhere('department', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $careers = $query->latest()->paginate(10);

        return view('livewire.career-management', compact('careers'));
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
                'description' => $this->description,
                'department' => $this->department,
                'location' => $this->location,
                'type' => $this->type,
                'deadline' => $this->deadline ?: null,
                'is_active' => $this->is_active,
            ];

            if ($this->isEdit) {
                Career::find($this->careerId)->update($data);
                $this->dispatch('toast',
                    type: 'success',
                    message: 'Job posting updated successfully!'
                );
                $this->closeEditForm();
            } else {
                Career::create($data);
                $this->dispatch('toast',
                    type: 'success',
                    message: 'Job posting created successfully!'
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
        $career = Career::find($id);
        $this->careerId = $id;
        $this->title = $career->title;
        $this->description = $career->description;
        $this->department = $career->department;
        $this->location = $career->location;
        $this->type = $career->type;
        $this->deadline = $career->deadline ? $career->deadline->format('Y-m-d') : null;
        $this->is_active = $career->is_active;
        $this->isEdit = true;
        $this->showEditForm = true;
    }

    public function delete($id)
    {
        try {
            Career::find($id)->delete();
            $this->dispatch('toast',
                type: 'success',
                message: 'Job posting deleted successfully!'
            );
        } catch (\Exception $e) {
            $this->dispatch('toast',
                type: 'error',
                message: 'Error deleting job posting: ' . $e->getMessage()
            );
        }
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'department', 'location', 'type', 'deadline', 'is_active', 'careerId', 'isEdit', 'showCreateForm', 'showEditForm']);
        $this->type = 'Full-time';
        $this->location = 'Remote';
        $this->is_active = true;
    }
}
