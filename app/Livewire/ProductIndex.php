<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
class ProductIndex extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $perPage = 5;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage' => ['except' => 5],
    ];

    // Reset pagination when search term is updated
    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    // Reset pagination when perPage value is updated
    public function updatingPerPage()
    {
        $this->resetPage();
    }
   

public function deleteProduct($productId)
{
    $product = Product::findOrFail($productId);

    // Detach categories from pivot table
    $product->categories()->detach();

    // Delete the image from storage
    if ($product->image && Storage::exists('public/' . $product->image)) {
        Storage::delete('public/' . $product->image);
    }
    

    // Delete the product from the database
    $product->delete();

    // Flash success message
    session()->flash('success', 'Product deleted successfully');

    // Show SweetAlert notification
    $message = 'Product Deleted Successfully';
    $this->dispatch('swal', [
        'title' => 'Success!',
        'text' => $message,
        'icon' => 'success',
    ]);
}

    public function render()
    {
        $products = Product::with(['brand', 'categories'])
            ->where('name', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('sku', 'like', '%' . $this->searchTerm . '%')
            ->paginate($this->perPage);

        return view('livewire.product-index', [
            'products' => $products,
        ]);
    }
}
