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
            ['MK201', 'POMPA ASI MANUAL MK201', 38767],
            ['MK99', 'POMPA ASI MANUAL MK99', 35168],
            ['MK011', 'POMPA ASI ELEKTRI MK011', 111369],
            ['MK010', 'POMPA ASI ELEKTRIK MK010', 143638],
            ['MK228', 'POMPA ASI ELEKTRI MK228', 121695],
            ['R28H9', 'POMPA ASI ELEKTRIK RH289', 129900],
            ['HS18', 'POMPA ASI HANDSFEE 18', 321494],
            ['HS9', 'POMPA ASI HANDSFEE 9', 343359],
            ['MK021', 'BOTOL DOT COKLAT 180ML', 41708],
            ['MK022', 'BOTOL DOT COKLAT 150ML', 40631],
            ['MK020', 'BOTOL DOT HIJAU 180ML', 41708],
            ['MK019', 'BOTOL DOT HIJAU 150ML', 40631],
            ['MK024', 'BOTOL DOT PINK 180ML', 41708],
            ['MK023', 'BOTOL DOT PINK 150ML', 40631],
            ['MK025', 'VELVE STANDAR', 11200],
            ['MK026', 'VELVE S18', 10280],
            ['MK027', 'DIAFRAGMA STANDAR', 11200],
            ['MK028', 'DIAFRAGMA S18', 13670],
            ['MK029', 'NIPPLE DOT', 21340],
            ['MK030', 'NIPPLE SPOUT', 11083],
            ['MK031', 'NIPPLE SPOON', 11083],
            ['MK032', 'CORONG LENTUR', 11083],
            ['MK033', 'SELANG BOTOL', 11083],
            ['MK034', 'SIKAT BOTOL', 26744],
            ['KOLIBRI', 'KANTONG ASI KOLIBRI 120 ML', 18000],
            ['LITTLE', 'KANTONG ASI LITTLE ANGLE 120 ML', 12500],
            ['MILKY-L', 'KANTONG ASI MILKY HOUSE LITE 120 ML', 13500],
            ['MILKY-D', 'KANTONG ASI MILKY HOUSE DELUXE 120 ML', 15300],
        ];

        foreach ($daftar as $key => $value) {
            Barang::create([
                'kode_barang' => $value[0],
                'nama' => $value[1],
                'jumlah' => 0,
            ]);
        }
    }
}
