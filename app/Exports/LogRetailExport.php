<?php

namespace App\Exports;

use App\Models\LogRetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LogRetailExport implements FromView, WithStyles
{
    public function view(): View
    {
        return view('log.retail.print', [
            'log' => LogRetail::with('barangs, retails')->get(),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Mengatur semua sel untuk membungkus teks
        $sheet->getStyle('D1:BB100')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('A')->setWidth(5);    // No
        $sheet->getColumnDimension('B')->setWidth(40);   // Nama
        $sheet->getColumnDimension('C')->setWidth(20);   // Alamat
    }
}
