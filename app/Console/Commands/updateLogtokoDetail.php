<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\LogStok;
use App\Models\LogToko;
use App\Models\LogKeuangan;
use App\Models\LogPengeluaran;
use Illuminate\Console\Command;

class updateLogtokoDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-logtoko-detail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update details of log toko every three hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logTokoNow = LogToko::where('bulan', Carbon::now()->month)->where('tahun', Carbon::now()->year)->first();
        $barangs = Barang::all();

        if ($logTokoNow->barangs()->count() != $barangs->count()) {
            $logTokoNow->barangs()->sync($barangs);
            $logTokoNow->save();
        }

        $logBarangLaku = LogKeuangan::where('status', 'Laku')
            ->whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
            ->get();

        $logPengeluaranNow = LogPengeluaran::whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
            ->get();

        $logGudang = LogStok::where('status', 'Masuk')
            ->whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
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
        $pengeluaran += $logGudang->sum('nominal');

        $logTokoNow->update([
            'omset' => $omset,
            'kotor' => $kotor,
            'pengeluaran' => $pengeluaran,
            'bersih' => $omset - $kotor - $pengeluaran,
        ]);

        $this->info('logToko berhasil diupdate');
    }
}
