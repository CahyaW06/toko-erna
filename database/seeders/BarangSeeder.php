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
            ['MK201', 'POMPA ASI MANUAL MK201'],
            ['MK99', 'POMPA ASI MANUAL MK99'],
            ['MK011', 'POMPA ASI ELEKTRI MK011'],
            ['MK010', 'POMPA ASI ELEKTRIK MK010'],
            ['MK228', 'POMPA ASI ELEKTRI MK228'],
            ['R28H9', 'POMPA ASI ELEKTRIK RH289'],
            ['HS18', 'POMPA ASI HANDSFEE 18'],
            ['HS9', 'POMPA ASI HANDSFEE 9'],
            ['MK021', 'BOTOL DOT COKLAT 180ML'],
            ['MK022', 'BOTOL DOT COKLAT 150ML'],
            ['MK020', 'BOTOL DOT HIJAU 180ML'],
            ['MK019', 'BOTOL DOT HIJAU 150ML'],
            ['MK024', 'BOTOL DOT PINK 180ML'],
            ['MK023', 'BOTOL DOT PINK 150ML'],
            ['MK025', 'VELVE STANDAR'],
            ['MK026', 'VELVE S18'],
            ['MK027', 'DIAFRAGMA STANDAR'],
            ['MK028', 'DIAFRAGMA S18'],
            ['MK029', 'NIPPLE DOT'],
            ['MK030', 'NIPPLE SPOUT'],
            ['MK031', 'NIPPLE SPOON'],
            ['MK032', 'CORONG LENTUR'],
            ['MK033', 'SELANG BOTOL'],
            ['MK034', 'SIKAT BOTOL'],
            ['KOLIBRI', 'KANTONG ASI KOLIBRI 120 ML'],
            ['LITTLE', 'KANTONG ASI LITTLE ANGLE 120 ML'],
            ['MILKY-L', 'KANTONG ASI MILKY HOUSE LITE 120 ML'],
            ['MILKY-D', 'KANTONG ASI MILKY HOUSE DELUXE 120 ML']
        ];

        // $daftar = [
        //     ['A1', 'MANUAL'],
        //     ['A1.1', 'MANUALMK201'],
        // ];

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
