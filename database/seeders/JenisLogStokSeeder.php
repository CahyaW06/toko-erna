<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLogStokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            'Masuk',
            'Keluar'
        ];

        foreach ($datas as $data) {
            DB::table('jenis_log_stok')->insert([
                'nama' => $data
            ]);
        }
    }
}
