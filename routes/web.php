<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogKeuanganController;
use App\Http\Controllers\LogRetailController;
use App\Http\Controllers\LogStokController;
use App\Http\Controllers\LogTokoController;
use App\Http\Controllers\RetailController;
use App\Models\Barang;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    $barangCounter = Barang::count();

    return view('home.index', [
        'barangCounter' => $barangCounter
    ]);
})->name('home');

Route::group(['prefix' => 'stok', 'as' => 'stok.'], function() {
    Route::resource('/gudang', BarangController::class);
    Route::get('/gudang-get-data', [BarangController::class, 'getDatas'])->name('gudang.get');
    Route::get('/gudang-get-list-barang', [BarangController::class, 'getListBarang'])->name('gudang.get-list-nama');

    Route::resource('/retail', RetailController::class);
    Route::get('/retail-get-data', [RetailController::class, 'getRetails'])->name('retail.get');
});


Route::group(['prefix' => 'log', 'as' => 'log.'], function() {
    Route::resource('/keuangan', LogKeuanganController::class);
    Route::get('/keuangan-get-data', [LogKeuanganController::class, 'getDatas'])->name('keuangan.get');
    Route::resource('/barang', LogRetailController::class);
    Route::get('/barang-get-data', [LogRetailController::class, 'getDatas'])->name('barang.get');
    Route::resource('/toko', LogTokoController::class);
});
