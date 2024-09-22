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
    public $showPaymentModal = false;
    public $showDetailsModal = false;
    public $transaksiId;
    public $jumlahBayar;
    public $totalHarusDibayar;
    public $kembalian = 0;
    public $selectedTransaksi;  // Menyimpan data transaksi yang dipilih untuk ditampilkan di modal

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'perPage' => ['except' => 5],
    ];

    // Method untuk menampilkan modal detail transaksi
    public function showDetails($transaksiId)
    {
        $this->selectedTransaksi = Transaksi::with('detailTransaksis.product')->findOrFail($transaksiId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedTransaksi = null;
    }
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

        // Kembalikan stok produk ketika transaksi dihapus
        // foreach ($transaksi->detailTransaksis as $detail) {
        //     $product = $detail->product;
        //     $product->increment('stock', $detail->jumlah);
        // }

        // Hapus transaksi
        $transaksi->delete();

        session()->flash('success', 'Transaksi berhasil dihapus');
        $this->dispatch('swal', [
            'title' => 'Deleted!',
            'text' => 'Transaksi berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function confirmFinishTransaksi($transaksiId)
    {
        $transaksi = Transaksi::findOrFail($transaksiId);
        $this->transaksiId = $transaksiId;
        $this->totalHarusDibayar = $transaksi->total_harga; // Set total harga
        $this->jumlahBayar = null; // Reset jumlah pembayaran
        $this->kembalian = 0; // Reset kembalian
        $this->showPaymentModal = true;

    }

    public function updatedJumlahBayar()
    {
        // Hitung kembalian
        $this->kembalian = max(0, $this->jumlahBayar - $this->totalHarusDibayar);
    }

    public function closeModal()
    {
        $this->showPaymentModal = false;
        $this->transaksiId = null;
        $this->jumlahBayar = null;
        $this->totalHarusDibayar = null;
        $this->kembalian = 0;
    }

    public function processFinishTransaksi()
    {
        $transaksi = Transaksi::findOrFail($this->transaksiId);
    

        if ($this->jumlahBayar < $transaksi->total_harga) {
            session()->flash('error', 'Jumlah pembayaran tidak mencukupi.');
            return;
        }
    
        // Simpan uang yang dibayar dan kembalian ke transaksi
       
        // Mengurangi stok produk berdasarkan jumlah di detail transaksi
        foreach ($transaksi->detailTransaksis as $detail) {
            $product = $detail->product;
    
            if ($product->stock >= $detail->jumlah) {
                $product->decrement('stock', $detail->jumlah);
            } else {
                session()->flash('error', 'Stok produk tidak mencukupi untuk menyelesaikan transaksi.');
                return;
            }
        }
        $transaksi->update([
            'uang_dibayar' => $this->jumlahBayar,
            'kembalian' => $this->kembalian,
            'status' => 'finished',
        ]);
    
        $balek = $this->kembalian;
        // Tampilkan pesan sukses dan kembalian dengan SweetAlert
        session()->flash('success', 'Transaksi selesai. Kembalian: ' . number_format($balek, 0, ',', '.'));
        $this->dispatch('swal', [
            'title' => 'Finished!',
            'text' => 'Transaksi selesai dengan kembalian ' . number_format($balek, 0, ',', '.').'.',
            'icon' => 'success',
        ]);
        $this->closeModal();
    }

    public function deleteTransaksiFinished($transaksiId)
    {
        $transaksi = Transaksi::findOrFail($transaksiId);

        // Hapus transaksi
        $transaksi->delete();

        session()->flash('success', 'Transaksi berhasil dihapus');
        $this->dispatch('swal', [
            'title' => 'Deleted!',
            'text' => 'Transaksi berhasil dihapus.',
            'icon' => 'success',
        ]);
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
