<?php

namespace App\Exports;

use App\Models\Barang;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class GudangExport implements FromView
{
    public function view(): View
    {
        return view('gudang.print', [
            'barangs' => Barang::all()
        ]);
    }
}
