<?php

namespace App\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Brand;

class BrandCrud extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $perPage = 5;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage' => ['except' => 5],
    ];
    public $name, $slug, $brandId;
    public $isModalOpen = false;

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    public function createOrUpdateBrand()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $slug = \Str::slug($this->name);
    
        Brand::updateOrCreate(
            ['id' => $this->brandId],
            ['name' => $this->name, 'slug' => $slug]
        );
    
        $message = $this->brandId ? 'Brand updated successfully.' : 'Brand created successfully.';

        // Dispatch SweetAlert event
        $this->dispatch('swal', [
            'title' => 'Success!',
            'text' => $message,
            'icon' => 'success',
        ]);
    
        $this->closeModal();
        $this->resetInputFields();
    }
    
    
    public function delete($id)
    {
        Brand::find($id)->delete();
        
        // Dispatch SweetAlert event for deletion
        $this->dispatch('swal', [
            'title' => 'Deleted!',
            'text' => 'Brand deleted successfully.',
            'icon' => 'success',
        ]);
    }


    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->brandId = '';
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->brandId = $id;
        $this->name = $brand->name;
        $this->openModal();
    }

    public function render()
    {
        $brands = Brand::where('name', 'like', '%' . $this->searchTerm . '%')
            ->paginate($this->perPage);

        return view('livewire.brand-crud', [
            'brands' => $brands,
        ]);
    }
}
