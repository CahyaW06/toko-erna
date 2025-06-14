<?php

namespace App\Http\Controllers;

use App\Models\Retail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExportController extends Controller
{
    public function pdfKonsiFraktur(Request $request) {
        $retails = Retail::whereHas('logRetails', function ($query) {
            $query->where('status', 'Diterima');
        })->get();

        $dataRetails = $retails->map(function ($retail) {
            $logKonsiRetail = Cache::rememberForever('recentLogKonsiRetail' . $retail->id, function () use ($retail) {
                return $retail->logRetails()->where('status', 'Diterima')->get()->groupBy('created_at')->last();
            });

            $logTransaksiRetail = Cache::rememberForever('recentLogTransaksiRetail' . $retail->id, function () use ($retail, $logKonsiRetail) {
                return $retail->logKeuangans()->where('status', 'Laku')->where('keterangan', 'Konsinyasi')->where('created_at', '>=', $logKonsiRetail->last()->created_at)->get();
            });

            $total = $logKonsiRetail->sum('nominal');
            $paid = $logTransaksiRetail->sum('nominal');
            return [
                'retail_name' => $retail->nama,
                'total' => $total,
                'paid' => $paid,
                'due' => $total - $paid
            ];
        });

        $data = [
            'dataRetails' => $dataRetails,
            'finalTotal' => $dataRetails->sum('total'),
            'finalPaid' => $dataRetails->sum('paid'),
            'finalDue' => $dataRetails->sum('due')
        ];

        return view('pdf.konsifraktur', $data);
    }

    public function pdfKonsiItem(Request $request) {
        $retails = Retail::whereHas('logRetails', function ($query) {
            $query->where('status', 'Diterima');
        })->get();

        $dataRetails = $retails->map(function ($retail) {
            $logKonsiRetail = Cache::rememberForever('recentLogKonsiRetail' . $retail->id, function () use ($retail) {
                return $retail->logRetails()->where('status', 'Diterima')->get()->groupBy('created_at')->last();
            });

            $rincianRetail = $logKonsiRetail->map(function ($konsi) {
                return [
                    'kode_barang' => $konsi->barang->kode_barang,
                    'nama_barang' => $konsi->barang->nama,
                    'qty' => $konsi->jumlah,
                    'omset' => $konsi->nominal,
                    'pl' => ($konsi->nominal/$konsi->jumlah) - $konsi->barang->harga,
                ];
            });

            return [
                'retail_name' => $retail->nama,
                'dataPerRetail' => $rincianRetail,
                'finalOmset' => $rincianRetail->sum('omset'),
                'finalPl' => $rincianRetail->sum('pl')
            ];
        });

        return view('pdf.konsiitem', [
            'dataRetails' => $dataRetails,
            'totalOmset' => $dataRetails->sum('finalOmset'),
        ]);
    }

    public function pdfTransaksiFraktur(Request $request) {
        $retails = Retail::whereHas('logRetails', function ($query) {
            $query->where('status', 'Diterima');
        })->get();

        $dataRetails = $retails->map(function ($retail) {
            $logKonsiRetail = Cache::rememberForever('recentLogKonsiRetail' . $retail->id, function () use ($retail) {
                return $retail->logRetails()->where('status', 'Diterima')->get()->groupBy('created_at')->last();
            });

            $logTransaksiRetail = Cache::rememberForever('recentLogTransaksiRetail' . $retail->id, function () use ($retail, $logKonsiRetail) {
                return $retail->logKeuangans()->where('status', 'Laku')->where('keterangan', 'Konsinyasi')->where('created_at', '>=', $logKonsiRetail->last()->created_at)->get();
            });

            $total = $logKonsiRetail->sum('nominal');
            $paid = $logTransaksiRetail->sum('nominal');
            return [
                'retail_name' => $retail->nama,
                'total' => $total,
                'paid' => $paid,
                'due' => $total - $paid
            ];
        });

        $data = [
            'dataRetails' => $dataRetails,
            'finalTotal' => $dataRetails->sum('total'),
            'finalPaid' => $dataRetails->sum('paid'),
            'finalDue' => $dataRetails->sum('due')
        ];

        return view('pdf.transaksifraktur', $data);
    }

    public function pdfTransaksiItem(Request $request) {
        $retails = Retail::whereHas('logRetails', function ($query) {
            $query->where('status', 'Diterima');
        })->get();

        $dataRetails = $retails->map(function ($retail) {
            $logKonsiRetail = Cache::rememberForever('recentLogKonsiRetail' . $retail->id, function () use ($retail) {
                return $retail->logRetails()->where('status', 'Diterima')->get()->groupBy('created_at')->last();
            });

            $logTransaksiRetail = Cache::rememberForever('recentLogTransaksiRetail' . $retail->id, function () use ($retail, $logKonsiRetail) {
                return $retail->logKeuangans()->where('status', 'Laku')->where('keterangan', 'Konsinyasi')->where('created_at', '>=', $logKonsiRetail->last()->created_at)->get();
            });

            $rincianRetail = $logTransaksiRetail->map(function ($transaksi) {
                return [
                    'kode_barang' => $transaksi->barang->kode_barang,
                    'nama_barang' => $transaksi->barang->nama,
                    'qty' => $transaksi->jumlah,
                    'omset' => $transaksi->nominal,
                    'pl' => ($transaksi->nominal/$transaksi->jumlah) - $transaksi->barang->harga,
                ];
            });

            return [
                'retail_name' => $retail->nama,
                'dataPerRetail' => $rincianRetail,
                'finalOmset' => $rincianRetail->sum('omset'),
                'finalPl' => $rincianRetail->sum('pl')
            ];
        });

        return view('pdf.transaksiitem', [
            'dataRetails' => $dataRetails,
            'totalOmset' => $dataRetails->sum('finalOmset'),
        ]);
    }
}
