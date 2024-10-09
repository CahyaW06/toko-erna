<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Retail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangRetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = Barang::all();
        $retails = Retail::all();

        foreach($retails as $retail) {
            foreach($barangs as $barang) {
                DB::table('barang_retail')->insert([
                    'barang_id' => $barang->id,
                    'retail_id' => $retail->id,
                    'jumlah' => rand(1, 50),
                ]);
            }
        }
    }
}
