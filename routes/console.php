<?php

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\LogToko;
use App\Models\LogKeuangan;
use App\Models\LogPengeluaran;
use App\Models\LogStok;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:create-log-toko')->monthly();
Schedule::command('app:update-logtoko-detail')->everyFiveMinutes()->withoutOverlapping();
