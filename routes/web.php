<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogKeuanganController;
use App\Http\Controllers\LogStokController;
use App\Http\Controllers\LogTokoController;
use App\Models\Barang;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    $barangCounter = Barang::count();

    return view('home.index', [
        'barangCounter' => $barangCounter
    ]);
})->name('home');

Route::resource('/gudang', BarangController::class);
Route::get('/get-gudangs', [BarangController::class, 'getDatas'])->name('gudang.get');
Route::group(['middleware' => 'guest', 'prefix' => 'statistik', 'as' => 'statistik.'], function() {
    Route::resource('/keuangan', LogKeuanganController::class);
    Route::resource('/barang', LogStokController::class);
    Route::resource('/toko', LogTokoController::class);
});
