<?php

namespace App\Http\Controllers;

use App\Models\Retail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExportController extends Controller
{
    public function pdfKonsiFraktur(Request $request) {
        $retails = Retail::all();

        $data = $retails->map(function ($retail) {
            $logKonsiRetail = Cache::rememberForever('recentLogKonsiRetail' . $retail->id, function () use ($retail) {
                return $retail->logRetails()->get()->groupBy('created_at')->last();
            });

            $logTransaksiRetail = Cache::rememberForever('recentLogTransaksiRetail' . $retail->id, function () use ($retail, $logKonsiRetail) {
                return $retail->logKeuangans()->where('status', 'Laku')->where('keterangan', 'Konsinyasi')->where('created_at', '>=', $logKonsiRetail->last()->created_at)->get();
            });

            return [
                'retail_name' => $retail->nama,
                'total' => $logKonsiRetail->sum('nominal')
            ];
        });

        $data = [
            'title' => 'Laporan Konsinyasi per Fraktur',
            'date' => now()
        ];

        $pdf = Pdf::loadView(view('pdf.konsifraktur'), $data);
        // return $pdf->download('laporan.pdf');
    }
}
