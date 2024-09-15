<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class CategoryCrud extends Component
{
    //untuk pagination
    use WithPagination;

    public $searchTerm = '';
    public $perPage = 5;
    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage' => ['except' => 5],
    ];

    public $name, $categoryId;
    public $isModalOpen = false;
    // Render categories with search and pagination
    public function render()
    {
        // Search categories
        $categories = Category::where('name', 'like', '%' . $this->searchTerm . '%')
        // Untuk paginasi
        ->paginate($this->perPage);

    return view('livewire.category-crud', [
        'categories' => $categories,
    ]);
    }

    // Create or update category
    public function createOrUpdateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            ['name' => $this->name]
        );

        session()->flash('message', $this->categoryId ? 'Category updated successfully.' : 'Category created successfully.');
        $message = $this->categoryId ? 'Category  updated successfully.' : 'Category  created successfully.';
        $this->dispatch('swal', [
            'title' => 'Success!',
            'text' => $message,
            'icon' => 'success',
        ]);
        $this->closeModal();
        $this->resetInputFields();
    }

    // Open modal
    public function openModal()
    {
        $this->isModalOpen = true;
    }

    // Close modal
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    // Reset input fields
    public function resetInputFields()
    {
        $this->name = '';
        $this->categoryId = '';
    }

    // Edit category
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->openModal();
    }

    // Delete category
    public function delete($id)
{
    $category = Category::findOrFail($id);
    
    // Delete related records in category_product table
    $category->products()->detach();

    // Now delete the category
    $category->delete();
    $this->dispatch('swal', [
        'title' => 'Deleted!',
        'text' => 'Brand deleted successfully.',
        'icon' => 'success',
    ]);
    session()->flash('message', 'Category deleted successfully.');
}

}
