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
    $logTransaksi = LogKeuangan::where('created_at', '>', $now->startOfMonth())->get();

    $stokGudang = Barang::orderBy('jumlah', 'ASC')
    ->limit(13)->get();

    $omset = 0;
    $pengeluaran = 0;
    $totalLaku = 0;
    $totalRugi = 0;

    foreach ($logTransaksi as $key => $value) {
        if ($value->status == "Laku") {
            $omset += $value->nominal;
            $totalLaku += $value->jumlah;
        } else {
            $pengeluaran += $value->nominal;
            $totalRugi += $value->jumlah;
        }
    }

    $bersih = $omset - $pengeluaran;

    $barangCounter = Barang::count();

    return view('home.index', [
        'barangCounter' => $barangCounter,
        'omset' => $omset,
        'pengeluaran' => $pengeluaran,
        'bersih' => $bersih,
        'totalLaku' => $totalLaku,
        'totalRugi' => $totalRugi,
        'stokGudang' => $stokGudang,
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
    Route::get('/keuangan-get-chart', [LogKeuanganController::class, 'chart'])->name('keuangan.chart');
    Route::resource('/barang', LogRetailController::class);
    Route::get('/barang-get-data', [LogRetailController::class, 'getDatas'])->name('barang.get');
    Route::resource('/toko', LogTokoController::class);
    Route::get('/toko-get-data', [LogTokoController::class, 'getDatas'])->name('toko.get');
});
