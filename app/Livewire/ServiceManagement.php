<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceManagement extends Component
{
    use WithPagination;

    public $name, $description, $picture, $features = [], $is_active = true;
    public $serviceId;
    public $isEdit = false;
    public $search = '';
    public $statusFilter = 'all';
    public $showCreateForm = false;
    public $showEditForm = false;
    public $newFeature = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'features' => 'array',
            'picture' => 'nullable|string|max:255',
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
        $query = Service::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $services = $query->latest()->paginate(10);

        return view('livewire.service-management', compact('services'));
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
                'name' => $this->name,
                'description' => $this->description,
                'picture' => $this->picture,
                'features' => $this->features,
                'is_active' => $this->is_active,
            ];

            if ($this->isEdit) {
                Service::find($this->serviceId)->update($data);
                $this->dispatch('toast',
                    type: 'success',
                    message: 'Service updated successfully!'
                );
                $this->closeEditForm();
            } else {
                Service::create($data);
                $this->dispatch('toast',
                    type: 'success',
                    message: 'Service created successfully!'
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

    public function addFeature()
    {
        if ($this->newFeature) {
            $this->features[] = $this->newFeature;
            $this->newFeature = '';
        }
    }

    public function removeFeature($index)
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function edit($id)
    {
        $service = Service::find($id);
        $this->serviceId = $id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->picture = $service->picture;
        $this->features = $service->features ?? [];
        $this->is_active = $service->is_active;
        $this->isEdit = true;
        $this->showEditForm = true;
    }

    public function delete($id)
    {
        try {
            Service::find($id)->delete();
            $this->dispatch('toast',
                type: 'success',
                message: 'Service deleted successfully!'
            );
        } catch (\Exception $e) {
            $this->dispatch('toast',
                type: 'error',
                message: 'Error deleting service: ' . $e->getMessage()
            );
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'description', 'features', 'is_active', 'serviceId', 'isEdit', 'newFeature', 'showCreateForm', 'showEditForm']);
        $this->is_active = true;
    }
}
