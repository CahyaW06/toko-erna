<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogKeuanganController;
use App\Http\Controllers\LogRetailController;
use App\Http\Controllers\LogStokController;
use App\Http\Controllers\LogTokoController;
use App\Http\Controllers\RetailController;
use App\Models\Barang;
use App\Models\LogKeuangan;
use App\Models\LogStok;
use App\Models\LogToko;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    if (Auth::check()) {
        $now = Carbon::now();
        $logTransaksi = LogKeuangan::where('created_at', '>', $now->startOfMonth())->get();
        $logGudang = LogStok::where('created_at', '>', $now->startOfMonth())->get();

        $stokGudang = Barang::orderBy('jumlah', 'ASC')
        ->limit(5)->get();

        $omset = 0;
        $pengeluaran = 0;
        $totalLaku = 0;
        $totalRugi = 0;

        foreach ($logTransaksi as $key => $value) {
            if ($value->status == "Laku") {
                $omset += $value->nominal;
                $totalLaku += $value->jumlah;
            } else {
                $totalRugi += $value->jumlah;
            }
        }

        foreach ($logGudang as $key => $value) {
            $pengeluaran += $value->nominal;
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
    } else {
        return view('home.index');
    }
})->name('home');
Route::post('/login-user', [LoginController::class, 'authenticate'])->name('login');


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'stok', 'as' => 'stok.'], function() {
        Route::resource('/gudang', BarangController::class);
        Route::post('/gudang-get-data', [BarangController::class, 'getDatas'])->name('gudang.get');
        Route::get('/gudang-get-list-barang', [BarangController::class, 'getListBarang'])->name('gudang.get-list-nama');

        Route::resource('/retail', RetailController::class);
        Route::post('/retail-get-data', [RetailController::class, 'getRetails'])->name('retail.get');
    });

    Route::group(['prefix' => 'log', 'as' => 'log.'], function() {
        Route::resource('/gudang', LogStokController::class);
        Route::post('/gudang-get-data', [LogStokController::class, 'getDatas'])->name('gudang.get');
        Route::resource('/keuangan', LogKeuanganController::class);
        Route::post('/keuangan-get-data', [LogKeuanganController::class, 'getDatas'])->name('keuangan.get');
        Route::get('/keuangan-get-chart', [LogKeuanganController::class, 'chart'])->name('keuangan.chart');
        Route::resource('/barang', LogRetailController::class);
        Route::post('/barang-get-data', [LogRetailController::class, 'getDatas'])->name('barang.get');
        Route::resource('/toko', LogTokoController::class);
        Route::post('/toko-get-data', [LogTokoController::class, 'getDatas'])->name('toko.get');
    });
});
