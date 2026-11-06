<?php

namespace App\Livewire;

use App\Models\Experience;
use Livewire\Component;
use Livewire\WithPagination;

class ExperienceManagement extends Component
{
    use WithPagination;

    public $title, $description, $year, $category;
    public $experienceId;
    public $isEdit = false;
    public $search = '';
    public $showCreateForm = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'year' => 'required|integer|min:1990|max:2030',
        'category' => 'required|string|max:255',
    ];

    public function render()
    {
        $experiences = Experience::where('title', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orWhere('category', 'like', '%'.$this->search.'%')
            ->orderBy('year', 'desc')
            ->paginate(10);

        return view('livewire.experience-management', compact('experiences'));
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'year' => $this->year,
            'category' => $this->category,
        ];

        if ($this->isEdit) {
            Experience::find($this->experienceId)->update($data);
        } else {
            Experience::create($data);
        }

        $this->closeCreateForm();
        session()->flash('message', $this->isEdit ? 'Experience updated successfully!' : 'Experience created successfully!');
    }

    public function edit($id)
    {
        $experience = Experience::find($id);
        $this->experienceId = $id;
        $this->title = $experience->title;
        $this->description = $experience->description;
        $this->year = $experience->year;
        $this->category = $experience->category;
        $this->isEdit = true;
        $this->showCreateForm = true;
    }

    public function delete($id)
    {
        Experience::find($id)->delete();
        session()->flash('message', 'Experience deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'year', 'category', 'experienceId', 'isEdit']);
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showCreateForm = true;
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }
}
