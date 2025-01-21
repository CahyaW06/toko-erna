<?php

use App\Models\LogKeuangan;
use App\Models\LogToko;
use Carbon\Carbon;
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
