<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLogKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            'Pendapatan',
            'Pengeluaran'
        ];

        foreach ($datas as $data) {
            DB::table('jenis_log_keuangan')->insert([
                'nama' => $data
            ]);
        }
    }
}
