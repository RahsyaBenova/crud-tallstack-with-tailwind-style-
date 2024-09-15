<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CategoryCrud;
use App\Livewire\BrandCrud;
use App\Livewire\ProductIndex;
use App\Livewire\ProductCreate;
use App\Livewire\ProductEdit;
use App\Livewire\TransaksiIndex;
use App\Livewire\TransaksiCreate;
use App\Livewire\TransaksiEdit;

Route::get('/transaksi/edit/{id}', TransaksiEdit::class)->name('transaksi.edit');
Route::get('/transaksi', TransaksiIndex::class)->name('transaksi.index');
Route::get('/transaksi/create', TransaksiCreate::class)->name('transaksi.create');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/products', ProductIndex::class)->name('products.index');
Route::get('/products/create', ProductCreate::class)->name('products.create');
Route::get('/products/edit/{id}', ProductEdit::class)->name('products.edit');
Route::get('brands', BrandCrud::class);
Route::get('categories', CategoryCrud::class);
Route::get('/', function () {
    return view('welcome');
});
