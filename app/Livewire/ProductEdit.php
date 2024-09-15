<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductEdit extends Component
{
    use WithFileUploads;

    public $product;
    public $sku, $name, $brand_id, $category_ids = [], $price, $stock, $image, $description;
    public $brands, $categories;

    public function mount($id)
    {
        $this->product = Product::findOrFail($id);
        $this->sku = $this->product->sku;
        $this->name = $this->product->name;
        $this->brand_id = $this->product->brand_id;
        $this->category_ids = $this->product->categories->pluck('id')->toArray();
        $this->price = $this->product->price;
        $this->stock = $this->product->stock;
        $this->description = $this->product->description;

        $this->brands = Brand::pluck('name', 'id');
        $this->categories = Category::pluck('name', 'id');
    }

    public function updateProduct()
    {
        $this->validate([
            'sku' => 'required',
            'name' => 'required',
            'brand_id' => 'nullable|exists:brands,id',
            'category_ids' => 'array',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:1024',
            'description' => 'required|string',
        ]);

        $this->product->update([
            'sku' => $this->sku,
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,  // save description here
        ]);

        if ($this->category_ids) {
            $this->product->categories()->sync($this->category_ids);
        }

        if ($this->image) {
            $imagePath = $this->image->store('images', 'public');
            $this->product->update(['image' => $imagePath]);
        }

        session()->flash('success', 'Product updated successfully');

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product-edit');
    }
}
