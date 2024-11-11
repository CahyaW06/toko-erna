<?php

namespace Database\Seeders;

use App\Models\LogStok;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogStokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                'barang_id' => 1,
                'status' => 1,
                'jumlah' => 10,
                'nominal' => 1000000,
                'created_at' => Carbon::now()
            ]
        ];

        foreach ($datas as $key => $value) {
            LogStok::create($value);
        }
    }
}
