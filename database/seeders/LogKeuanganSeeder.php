<?php

namespace Database\Seeders;

use App\Models\LogKeuangan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'log_retail_id' => 1,
                'status' => 1,
                'jumlah' => 10,
                'nominal' => 1000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($data as $key => $value) {
            LogKeuangan::create($value);
        }
    }
}
