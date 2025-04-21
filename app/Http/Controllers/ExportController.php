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

        $dataRetails = $retails->map(function ($retail) {
            $logKonsiRetail = Cache::rememberForever('recentLogKonsiRetail' . $retail->id, function () use ($retail) {
                return $retail->logRetails()->get()->groupBy('created_at')->last();
            });

            $logTransaksiRetail = Cache::rememberForever('recentLogTransaksiRetail' . $retail->id, function () use ($retail, $logKonsiRetail) {
                if (empty($logKonsiRetail)) {
                    return [];
                }

                return $retail->logKeuangans()->where('status', 'Laku')->where('keterangan', 'Konsinyasi')->where('created_at', '>=', $logKonsiRetail->last()->created_at)->get();
            });

            $total = empty($logKonsiRetail) ? 0 : $logKonsiRetail->sum('nominal');
            $paid = empty($logTransaksiRetail) ? 0 : $logTransaksiRetail->sum('nominal');
            return [
                'retail_name' => $retail->nama,
                'total' => $total,
                'paid' => $paid,
                'due' => $total - $paid
            ];
        });

        $data = [
            'title' => 'Laporan Konsinyasi per Fraktur',
            'dataRetails' => $dataRetails,
            'finalTotal' => $dataRetails->sum('total'),
            'finalPaid' => $dataRetails->sum('paid'),
            'finalDue' => $dataRetails->sum('due')
        ];

        return view('pdf.konsifraktur', $data);
    }
}
