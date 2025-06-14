<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogKeuanganController;
use App\Http\Controllers\LogPengeluaranController;
use App\Http\Controllers\LogRetailController;
use App\Http\Controllers\LogStokController;
use App\Http\Controllers\LogTokoController;
use App\Http\Controllers\RetailController;
use App\Models\Barang;
use App\Models\LogKeuangan;
use App\Models\LogStok;
use App\Models\LogToko;
use App\Models\Retail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    if (Auth::check()) {
        $logTokoNow = LogToko::where('bulan', Carbon::now()->month)->where('tahun', Carbon::now()->year)->first();

        $logTransaksi = LogKeuangan::whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
        ->get();

        $stokGudang = Barang::orderBy('jumlah', 'ASC')
        ->limit(5)->get();

        $totalLaku = 0;
        $totalRugi = 0;

        foreach ($logTransaksi as $key => $value) {
            if ($value->status == "Laku") {
                $totalLaku += $value->jumlah;
            } else {
                $totalRugi += $value->jumlah;
            }
        }

        $barangCounter = Barang::count();

        return view('home.index', [
            'barangCounter' => $barangCounter,
            'omset' => $logTokoNow->omset,
            'pengeluaran' => $logTokoNow->pengeluaran,
            'bersih' => $logTokoNow->bersih,
            'totalLaku' => $totalLaku,
            'totalRugi' => $totalRugi,
            'stokGudang' => $stokGudang,
        ]);
    } else {
        return view('home.index');
    }
})->name('home');
Route::post('/', [LoginController::class, 'authenticate'])->name('login');


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'stok', 'as' => 'stok.'], function() {
        Route::resource('/gudang', BarangController::class);
        Route::post('/gudang-get-data', [BarangController::class, 'getDatas'])->name('gudang.get');
        Route::get('/gudang-get-list-barang', [BarangController::class, 'getListBarang'])->name('gudang.get-list-nama');
        Route::post('/gudang/{gudang}/rincian', [BarangController::class, 'getRincian'])->name('gudang.rincian');

        Route::resource('/retail', RetailController::class);
        Route::post('/retail-get-data', [RetailController::class, 'getRetails'])->name('retail.get');
        Route::post('/retail/{retail}/rincian', [RetailController::class, 'getRincian'])->name('retail.rincian');
        Route::get('/retail/{retail}/konsi', [RetailController::class, 'getLogKonsi'])->name('retail.konsi');
    });

    Route::group(['prefix' => 'log', 'as' => 'log.'], function() {
        Route::resource('/gudang', LogStokController::class);
        Route::post('/gudang-get-data', [LogStokController::class, 'getDatas'])->name('gudang.get');
        Route::resource('/barang', LogRetailController::class);
        Route::post('/barang-get-data', [LogRetailController::class, 'getDatas'])->name('barang.get');
        Route::resource('/keuangan', LogKeuanganController::class);
        Route::post('/keuangan-get-data', [LogKeuanganController::class, 'getDatas'])->name('keuangan.get');
        Route::get('/keuangan-get-chart', [LogKeuanganController::class, 'chart'])->name('keuangan.chart');
        Route::resource('/pengeluaran', LogPengeluaranController::class);
        Route::post('/pengeluaran-get-data', [LogPengeluaranController::class, 'getDatas'])->name('pengeluaran.get');
        Route::resource('/toko', LogTokoController::class);
        Route::post('/toko-get-data', [LogTokoController::class, 'getDatas'])->name('toko.get');
        Route::post('/toko/{toko}/rincian', [LogTokoController::class, 'getRincian'])->name('toko.rincian');
        Route::get('/toko/{toko}/konsi', [LogTokoController::class, 'showKonsi'])->name('toko.konsi');
        Route::post('/toko/{toko}/rincian-konsi', [LogTokoController::class, 'getKonsi'])->name('toko.rincian-konsi');
        Route::put('/toko/{toko}/update-belanja-modal', [LogTokoController::class, 'updateBelanjaModal'])->name('toko.updateBelanjaModal');
    });

    Route::group(['prefix' => 'export', 'as' => 'export.'], function() {
        Route::get('/konsi-fraktur', [ExportController::class, 'pdfKonsiFraktur'])->name('konsi-fraktur');
        Route::get('/konsi-item', [ExportController::class, 'pdfKonsiItem'])->name('konsi-item');
        Route::get('/transaksi-fraktur', [ExportController::class, 'pdfTransaksiFraktur'])->name('transaksi-fraktur');
        Route::get('/transaksi-item', [ExportController::class, 'pdfTransaksiItem'])->name('transaksi-item');
    });
});
