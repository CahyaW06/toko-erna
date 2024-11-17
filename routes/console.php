<?php

use App\Models\LogKeuangan;
use App\Models\LogToko;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    $now = Carbon::now();
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

    LogToko::create([
        'bulan' => $now->month,
        'tahun' => $now->year,
        'omset' => $omset,
        'pengeluaran' => $pengeluaran,
        'bersih' => $bersih,
        'created_at' => $now,
        'updated_at' => $now,
    ]);
})->lastDayOfMonth('23:59');
