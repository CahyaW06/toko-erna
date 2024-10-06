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
Route::get('/gudang-get-data', [BarangController::class, 'getDatas'])->name('gudang.get');
Route::get('/gudang-export-excel', [BarangController::class, 'exportExcel'])->name('gudang.export-excel');
Route::get('/gudang-export-pdf', [BarangController::class, 'exportPdf'])->name('gudang.export-pdf');
Route::get('/gudang-printed-data', [BarangController::class, 'printedData'])->name('gudang.printed-data');

Route::group(['prefix' => 'catatan', 'as' => 'catatan.'], function() {
    Route::resource('/keuangan', LogKeuanganController::class);
    Route::resource('/barang', LogStokController::class);
    Route::resource('/toko', LogTokoController::class);
});
