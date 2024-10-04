<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\LogKeuanganController;
use App\Http\Controllers\LogStokController;
use App\Http\Controllers\LogTokoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});

Route::resource('/gudang', BarangController::class);
Route::group(['middleware' => 'guest', 'prefix' => 'statistik', 'as' => 'statistik.'], function() {
    Route::resource('/keuangan', LogKeuanganController::class);
    Route::resource('/barang', LogStokController::class);
    Route::resource('/toko', LogTokoController::class);
});
