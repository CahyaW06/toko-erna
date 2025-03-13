<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\LogStok;
use App\Models\LogToko;
use App\Models\LogKeuangan;
use App\Models\LogPengeluaran;
use App\Models\LogRetail;
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

        $logPengeluaranNow = LogPengeluaran::whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
        ->get();

        $kotor = 0;

        if (LogToko::find($logTokoNow->id - 1) == null) {
            $logKonsi = LogRetail::where('created_at', '<=', Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth())
            ->get();
            $logBarangLaku = LogKeuangan::where('status', 'Laku')->where('created_at', '<=', Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth())->get();

            foreach ($logTokoNow->barangs as $key => $value) {
                $kotor += $logBarangLaku->where('barang_id', $value->id)->sum('jumlah') * $value->harga;
                $value->pivot->jumlah = $logBarangLaku->where('barang_id', $value->id)->sum('jumlah');
                $value->pivot->omset = $logBarangLaku->where('barang_id', $value->id)->sum('nominal');
                $value->pivot->konsinyasi = $logKonsi->where('barang_id', $value->id)->where('status', 'Diterima')->sum('jumlah') - $logBarangLaku->where('barang_id', $value->id)->where('keterangan', 'Konsinyasi')->sum('jumlah');
                $value->pivot->nominal_konsinyasi = ($logKonsi->where('barang_id', $value->id)->where('status', 'Diterima')->sum('jumlah') - $logBarangLaku->where('barang_id', $value->id)->where('keterangan', 'Konsinyasi')->sum('jumlah')) * $value->harga;
                $value->push();
            }
        } else {
            $logTokoSebelumnya = LogToko::find($logTokoNow->id - 1);
            $logKonsi = LogRetail::where('status', 'Diterima')->whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])->get();
            $logBarangLaku = LogKeuangan::where('status', 'Laku')
                ->whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
                ->get();

            foreach ($logTokoNow->barangs as $key => $value) {
                $kotor += $logBarangLaku->where('barang_id', $value->id)->sum('jumlah') * $value->harga;
                $value->pivot->jumlah = $logBarangLaku->where('barang_id', $value->id)->sum('jumlah');
                $value->pivot->omset = $logBarangLaku->where('barang_id', $value->id)->sum('nominal');
                $value->pivot->konsinyasi = $logTokoSebelumnya->barangs->find($value->id)->pivot->konsinyasi + $logKonsi->where('barang_id', $value->id)->sum('jumlah') - $logBarangLaku->where('barang_id', $value->id)->where('keterangan', 'Konsinyasi')->sum('jumlah');
                $value->pivot->nominal_konsinyasi = ($logTokoSebelumnya->barangs->find($value->id)->pivot->konsinyasi + $logKonsi->where('barang_id', $value->id)->sum('jumlah') - $logBarangLaku->where('barang_id', $value->id)->where('keterangan', 'Konsinyasi')->sum('jumlah')) * $value->harga;
                $value->push();
            }
        }

        $omset = $logBarangLaku->sum('nominal');
        $pengeluaran = $logPengeluaranNow->sum('nominal');

        $logTokoNow->update([
            'omset' => $omset,
            'kotor' => $kotor,
            'pengeluaran' => $pengeluaran,
            'bersih' => $omset - $kotor - $pengeluaran,
        ]);

        $this->info('logToko berhasil diupdate');
    }
}
