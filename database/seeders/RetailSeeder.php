<?php

namespace Database\Seeders;

use App\Models\Retail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $namas = [
            "APT UNISIA KUSUMANEGARA",
            "APT ADNA FARMA JL. PARIS YK",
            "APT PATALAN BANTUL",
            "APT MITRA SEJAHTERA NANGGULAN",
            "APT JATISARONO NANGGULAN",
            "KOP RSUD WATES",
            "APT ASY SYIFA WATES",
            "APT KELUARGA SEHAT WATES",
            "APT DJUANDA WATES",
            "APT INSANI GODEAN",
            "APT INSANI GODEAN",
            "APT UAD 4 SIDOARUM",
            "MOOMART SRAGEN",
            "APT AKMAL SEHAT 5 GM PWDD",
            "APT AKMAL SEHAT 6 JL A YANI PWDD",
            "APT TASNIM JL MT HARYONO PWDD",
            "APT PELITA JL HAYAM WURUK PWDD",
            "APT HIDUP TEMANGGUNG",
            "APT SIAGA TEMANGGUNG",
            "APT SEHAT ABADI TEMANGGUNG",
            "APT WARINGIN TEMANGGUNG",
            "SARINAH BS WONOSOBO",
            "APT JOGOSEHAT WONOSOBO",
            "APT KACANGAN",
            "QIRANI BS GEMOLONG",
            "QIRANI BS GEMOLONG",
            "SBA KARANGANYAR",
            "APT SEHAT SEHATI MASARAN SRAGEN",
            "APT NURHAYATI",
            "APT AKMAL SEHAT KLECO",
            "APT SOLO JL KATAMSO",
            "APT AKMAL SEHAT 7 GROGOL",
            "APT AKMAL SEHAT JATEN",
            "APT AKMAL SEHAT ADI SUCIPTO",
            "APT INDAH FARMA BANTUL",
            "APT KENCANA JL KESEHATAN",
            "APT WARINGIN JOGJA",
            "APT K24 KOTABARU",
            "APT ANUGRAH BABARSARI",
            "MB WIWIN PUSK JETIS II",
            "APT BLAWONG JL IMOGIRI TIMUR",
            "APT SOLUSI SEHATI JL IMOGIRI TIMUR",
            "INDAH FARMA PLERET",
            "ZURA BS PLERET",
            "APT PHARM 24 JL. MGL",
            "KOSUDGAMA UGM YK",
            "APT PHARM 24GEJAYAN",
            "SAMARA RS JIH",
            "APT OMAH SEHAT JOGJA",
            "APT PRATAMA TRIDADI",
            "APT UNISIA 24 MURANGAN",
            "KOP HOSPITA MANDIRI RSA UGM",
            "HOSPITA MART RSUD SLEMAN",
            "MBAK ENY PAMELA WONOSARI",
            "KINARA BS WONOSARI",
            "UNGU BS WONOSARI",
            "NATALIA ARGO STG",
            "NATALIA BS",
            "APT TRIANA MUNTILAN",
            "APT TRININGSIH MUNTILAN",
            "APT ADI FARMA MUNTILAN",
            "APT KAWATAN MGL",
            "KOP RSI SULTAN AGUNG SMG",
            "MOM & CHILD"
        ];

        foreach ($namas as $nama) {
            Retail::create(['nama' => $nama]);
        }
    }
}
