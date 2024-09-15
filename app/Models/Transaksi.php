<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = ['tanggal', 'total_harga', 'status'];
    

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
