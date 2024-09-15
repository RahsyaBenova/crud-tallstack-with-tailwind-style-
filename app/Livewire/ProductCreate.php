<?php
namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $sku, $name, $brand_id, $category_ids = [], $price, $stock, $image, $description;
    public $brands, $categories;

    public function mount()
    {
        $this->brands = Brand::pluck('name', 'id');
        $this->categories = Category::pluck('name', 'id');
    }

    public function createProduct()
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

        // Simpan produk
        $product = Product::create([
            'sku' => $this->sku,
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,  // save description here
        ]);

        if ($this->category_ids) {
            $product->categories()->attach($this->category_ids);
        }

        if ($this->image) {
            $imagePath = $this->image->store('images', 'public');
            $product->update(['image' => $imagePath]);
        }

        session()->flash('success', 'Product created successfully');
        $message = 'Product created successfully';
        $this->dispatch('swal', [
            'title' => 'Success!',
            'text' => $message,
            'icon' => 'success',
        ]);
        return redirect()->route('products.index');
    }

    // Method untuk menangani upload gambar dari CKEditor
    public function uploadImage()
    {
        if (request()->hasFile('upload')) {
            $file = request()->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('images', $fileName, 'public');

            $url = Storage::url($filePath);

            // Response JSON ke CKEditor dengan URL gambar
            return response()->json([
                'uploaded' => 1,
                'fileName' => $fileName,
                'url' => $url
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'File upload failed.']
        ]);
    }

    public function render()
    {
        return view('livewire.product-create');
    }
}
