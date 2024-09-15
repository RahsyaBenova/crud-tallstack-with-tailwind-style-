<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Livewire\Component;
use Livewire\WithPagination;

class TransaksiIndex extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $perPage = 5;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage' => ['except' => 5],
    ];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function deleteTransaksi($transaksiId)
    {
        $transaksi = Transaksi::findOrFail($transaksiId);

        // Mengembalikan stok produk ketika transaksi dihapus
        foreach ($transaksi->detailTransaksis as $detail) {
            $product = $detail->product;
            $product->increment('stock', $detail->jumlah);
        }

        // Hapus transaksi
        $transaksi->delete();

        session()->flash('success', 'Transaksi deleted successfully');
        $this->dispatch('swal', [
            'title' => 'Deleted!',
            'text' => 'Transaksi deleted successfully.',
            'icon' => 'success',
        ]);
    }

    public function finishTransaksi($transaksiId)
    {
        $transaksi = Transaksi::findOrFail($transaksiId);

        // Mengurangi stok produk berdasarkan jumlah di detail transaksi
        foreach ($transaksi->detailTransaksis as $detail) {
            $product = $detail->product;

            if ($product->stock >= $detail->jumlah) {
                // Mengurangi stok produk
                $product->decrement('stock', $detail->jumlah);
            } else {
                session()->flash('error', 'Stok produk tidak mencukupi untuk menyelesaikan transaksi');
                return;
            }
        }

        // Mengubah status transaksi menjadi finished
        $transaksi->update(['status' => 'finished']);

        session()->flash('success', 'Transaksi finished successfully');
    }

    public function render()
    {
        $transaksis = Transaksi::with('detailTransaksis.product')
            ->where('id', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('tanggal', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('status', 'like', '%' . $this->searchTerm . '%')
            ->paginate($this->perPage);

        return view('livewire.transaksi-index', ['transaksis' => $transaksis]);
    }
}
