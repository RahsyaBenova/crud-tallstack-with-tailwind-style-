<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Livewire\Component;

class TransaksiEdit extends Component
{
    public $transaksi, $tanggal, $total_harga;
    public $products, $cart = [];

    public function mount($id)
    {
        $this->transaksi = Transaksi::findOrFail($id);
        $this->tanggal = $this->transaksi->tanggal;
        $this->total_harga = $this->transaksi->total_harga;

        // Load all products
        $this->products = Product::all();

        // Load existing transaction details into cart
        foreach ($this->transaksi->detailTransaksis as $detail) {
            $this->cart[] = [
                'product_id' => $detail->product_id,
                'name' => $detail->product->name,
                'price' => $detail->harga,
                'jumlah' => $detail->jumlah,
            ];
        }
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
        $this->cart = array_values($this->cart);  // Reindex array
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

    public function updateTransaksi()
    {
        $this->transaksi->update([
            'tanggal' => $this->tanggal,
            'total_harga' => $this->total_harga,
        ]);
    
        // Delete existing details
        DetailTransaksi::where('transaksi_id', $this->transaksi->id)->delete();
    
        // Simpan detail transaksi tanpa mengurangi stok
        foreach ($this->cart as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $this->transaksi->id,
                'product_id' => $item['product_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['price'],
            ]);
        }
    
        session()->flash('success', 'Transaksi updated successfully');
        return redirect()->route('transaksi.index');
    }
    
public function finishTransaksi($id)
{
    $transaksi = Transaksi::find($id);

    if ($transaksi && $transaksi->status === 'pending') {
        foreach ($transaksi->detailTransaksis as $detail) {
            $product = Product::find($detail->product_id);

            // Pastikan stok cukup sebelum mengurangi
            if ($product->stock >= $detail->jumlah) {
                $product->decrement('stock', $detail->jumlah);
            } else {
                session()->flash('error', 'Stok tidak cukup untuk menyelesaikan transaksi.');
                return;
            }
        }

        // Update status transaksi menjadi finished
        $transaksi->update(['status' => 'finished']);
        session()->flash('success', 'Transaksi berhasil diselesaikan.');
    } else {
        session()->flash('error', 'Transaksi sudah selesai atau tidak ditemukan.');
    }
}

    public function render()
    {
        return view('livewire.transaksi-edit', ['products' => $this->products]);
    }
}
