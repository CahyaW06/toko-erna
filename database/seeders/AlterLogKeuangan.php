<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\LogKeuangan;
use App\Models\Retail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlterLogKeuangan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = LogKeuangan::with('barang', 'retail')->get()->reverse();
        $toBeDeleteData = $data->where('id', '>=', 635);

        foreach ($toBeDeleteData as $key => $log) {
            $retailLama = Retail::find($log->retail_id);
            $statusLama = $log->status;
            $ketLama = $log->keterangan;

            if ($ketLama == 'Konsinyasi') {
                if ($statusLama == "Laku") {
                    $retailLama->barangs->find($log->barang_id)->pivot->jumlah += $log->jumlah;
                    $retailLama->push();
                } else {
                    $retailLama->barangs->find($log->barang_id)->pivot->jumlah -= $log->jumlah;
                    $retailLama->push();

                    $barang = Barang::find($log->barang_id);
                    $barang->jumlah += $log->jumlah;
                    $barang->save();
                }
            } else {
                $barang = Barang::find($log->barang_id);
                $barang->jumlah += $log->jumlah;
                $barang->save();
            }

            $log->delete();
        }
    }
}
