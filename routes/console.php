<?php

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\LogToko;
use App\Models\LogKeuangan;
use App\Models\LogPengeluaran;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $now = Carbon::now();

    $omset = 0;
    $pengeluaran = 0;
    $bersih = 0;

    LogToko::create([
        'bulan' => $now->month,
        'tahun' => $now->year,
        'omset' => $omset,
        'pengeluaran' => $pengeluaran,
        'bersih' => $bersih,
        'created_at' => $now,
        'updated_at' => $now,
    ]);
})->monthly();

Schedule::call(function() {
    $logTokoNow = LogToko::where('bulan', Carbon::now()->month)->where('tahun', Carbon::now()->year)->first();
    $barangs = Barang::all();

    $logBarangLaku = LogKeuangan::where('status', 'Laku')
        ->whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
        ->get();

    $logPengeluaranNow = LogPengeluaran::whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
        ->get();

    $data = $barangs->map(function($barang) use($logBarangLaku) {
        return [
            'jumlah-' . $barang->id => $logBarangLaku->where('barang_id', $barang->id)->sum('jumlah'),
            'omset-' . $barang->id => $logBarangLaku->where('barang_id', $barang->id)->sum('nominal')
        ];
    });

    $kotor = 0;

    foreach ($logTokoNow->barangs as $key => $value) {
        $kotor += $data[$key]['jumlah-' . $value->id] * $value->harga;

        $value->pivot->jumlah = $data[$key]['jumlah-' . $value->id];
        $value->pivot->omset = $data[$key]['omset-' . $value->id];
        $value->push();
    }

    $omset = $logBarangLaku->sum('nominal');
    $pengeluaran = $logPengeluaranNow->sum('nominal');

    $logTokoNow->update([
        'omset' => $omset,
        'kotor' => $kotor,
        'pengeluaran' => $pengeluaran,
        'bersih' => $omset - $kotor - $pengeluaran,
    ]);

    return $logTokoNow->barangs;
})->everyThreeHours();
