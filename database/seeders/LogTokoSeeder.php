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
                'bulan' => 1,
                'tahun' => 2025,
                'omset' => 0,
                'kotor' => 0,
                'pengeluaran' => 0,
                'bersih' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($data as $key => $value) {
            LogToko::create($value);
        }
    }
}
