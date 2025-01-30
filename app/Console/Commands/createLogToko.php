<?php

namespace App\Console\Commands;

use App\Models\Barang;
use Carbon\Carbon;
use App\Models\LogToko;
use Illuminate\Console\Command;

class createLogToko extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-log-toko';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make initial logToko every month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $omset = 0;
        $pengeluaran = 0;
        $bersih = 0;

        $logToko = LogToko::create([
            'bulan' => $now->month,
            'tahun' => $now->year,
            'omset' => $omset,
            'pengeluaran' => $pengeluaran,
            'bersih' => $bersih,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $barangs = Barang::all();

        $logToko->barangs()->attach($barangs);
        $logToko->save();

        $this->info('logToko bulan ' . $now->month . ' tahun ' . $now->year . ' telah berhasil dibuat');
    }
}
