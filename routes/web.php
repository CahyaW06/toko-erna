<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogKeuanganController;
use App\Http\Controllers\LogRetailController;
use App\Http\Controllers\LogStokController;
use App\Http\Controllers\LogTokoController;
use App\Http\Controllers\RetailController;
use App\Models\Barang;
use App\Models\LogKeuangan;
use App\Models\LogToko;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    $now = Carbon::now();
    $logTokoSebelumnya = LogToko::where('created_at', $now->subMonth()->month)->get();
    $logTransaksi = LogKeuangan::whereMonth('created_at', $now->month)
        ->whereYear('created_at', $now->year)
        ->get();

    $omset = 0;
    $pengeluaran = 0;

    foreach ($logTransaksi as $key => $value) {
        if ($value->status == "Laku") {
            $omset += $value->nominal;
        } else {
            $pengeluaran += $value->nominal;
        }
    }

    $bersih = $omset - $pengeluaran;

    $barangCounter = Barang::count();
    dd($now);

    return view('home.index', [
        'barangCounter' => $barangCounter,
        'omset' => $omset,
        'pengeluaran' => $pengeluaran,
        'bersih' => $bersih,
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
    Route::resource('/gudang', LogStokController::class);
    Route::get('/gudang-get-data', [LogStokController::class, 'getDatas'])->name('gudang.get');
    Route::resource('/keuangan', LogKeuanganController::class);
    Route::get('/keuangan-get-data', [LogKeuanganController::class, 'getDatas'])->name('keuangan.get');
    Route::resource('/barang', LogRetailController::class);
    Route::get('/barang-get-data', [LogRetailController::class, 'getDatas'])->name('barang.get');
    Route::resource('/toko', LogTokoController::class);
    Route::get('/toko-get-data', [LogTokoController::class, 'getDatas'])->name('toko.get');
});
