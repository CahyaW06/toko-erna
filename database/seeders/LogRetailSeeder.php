<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogRetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'barang_id' => 1,
                'retail_id' => 1,
                'jenis_log_stok_id' => 1,
                'jumlah' => 10,
                'nominal' => 150000,
            ]
        ];

        DB::table('log_retails')->insert($datas);
    }
}
