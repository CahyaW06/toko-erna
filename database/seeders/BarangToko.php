<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\LogToko;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangToko extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = Barang::all();
        $logToko = LogToko::all();

        foreach($logToko as $key=>$value) {
            foreach($barangs as $barang) {
                DB::table('barang_toko')->insert([
                    'barang_id' => $barang->id,
                    'log_toko_id' => $value->id,
                    'jumlah' => 0,
                    'omset' => 0,
                ]);
            }
        }
    }
}
