<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Livewire\Component;

class TransaksiCreate extends Component
{
    public $tanggal, $total_harga = 0;
    public $products, $cart = [];
    public $search = ''; // Tambahkan properti search

    public function mount()
    {
        // Load all products
        $this->products = Product::all();
    }

    public function updatedSearch()
    {
        // Update produk yang ditampilkan sesuai dengan pencarian
        $this->products = Product::where('name', 'like', '%' . $this->search . '%')->get();
    }

    public function addProductToCart($productId)
    {
        $product = Product::find($productId);

        if ($product) {
            $this->cart[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'jumlah' => 1,
            ];
        }

        $this->calculateTotal();
    }

    public function removeProductFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Reindex array setelah remove
        $this->calculateTotal();
    }

    public function updateCart($index, $field, $value)
    {
        $this->cart[$index][$field] = $value;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_harga = array_reduce($this->cart, function ($total, $item) {
            return $total + ($item['price'] * $item['jumlah']);
        }, 0);
    }

    public function createTransaksi()
    {
        // Membuat transaksi baru
        $transaksi = Transaksi::create([
            'tanggal' => now(),
            'total_harga' => $this->total_harga,
            'status' => 'pending',  // Set transaksi sebagai pending
        ]);

        // Menyimpan detail transaksi tanpa mengurangi stok produk
        foreach ($this->cart as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'product_id' => $item['product_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['price'],
            ]);
        }

        session()->flash('success', 'Transaksi created successfully');
        $this->dispatch('swal', [
            'title' => 'Success!',
            'text' => 'Transaksi created successfully.',
            'icon' => 'success',
        ]);

        return redirect()->route('transaksi.index');
    }

    public function render()
    {
        return view('livewire.transaksi-create', ['products' => $this->products]);
    }
}
