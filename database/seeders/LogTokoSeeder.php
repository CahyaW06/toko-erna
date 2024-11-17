<?php

namespace Database\Seeders;

use App\Models\LogToko;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogTokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'bulan' => 10,
                'tahun' => 2024,
                'omset' => 10000000,
                'pengeluaran' => 1000000,
                'bersih' => 9000000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($data as $key => $value) {
            LogToko::create($value);
        }
    }
}
