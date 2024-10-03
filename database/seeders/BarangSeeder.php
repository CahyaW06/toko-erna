<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftar = [
            ['A1', 'MANUAL'],
            ['A1.1', 'MANUAL MK201'],
            ['A2', 'MK228'],
            ['A3', 'RH289'],
            ['A4', 'HS 18'],
            ['A5', 'HS 9'],
            ['A6', 'MZ10'],
            ['B1', 'DOT C 180ML'],
            ['B2', 'DOT H/P 180ML'],
            ['B3', 'DOT C 150ML'],
            ['B4', 'DOT H/P 150ML'],
            ['C1', 'KA KOLOBRI'],
            ['C1.1', 'KA HIJAU'],
            ['C1.2', 'KA MH BARU 13.500'],
            ['C1.3', 'KA MH LAMA 15.461'],
            ['C2', 'NIPPLE DOT'],
            ['C3', 'NIPPLE DUCBIL'],
            ['C4', 'VELVE BIASA'],
            ['C5', 'DIAFRAGMA BIASA'],
            ['C6', 'DIAFRAGMA 18'],
        ];

        foreach ($daftar as $key => $value) {
            Barang::create([
                'kode_barang' => $value[0],
                'nama' => $value[1],
                'harga' => 0,
                'jumlah' => 0,
            ]);
        }
    }
}
