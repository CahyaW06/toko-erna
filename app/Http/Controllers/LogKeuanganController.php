<?php

namespace App\Http\Controllers;

use App\Models\LogRetail;
use App\Models\LogKeuangan;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreLogKeuanganRequest;
use App\Http\Requests\UpdateLogKeuanganRequest;
use App\Models\Barang;
use App\Models\Retail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogKeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('log.transaksi.index');
    }

    public function getDatas(Request $request)
    {
        if ($request->ajax()) {
            $data = LogKeuangan::with('barang', 'retail')->get()->reverse();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    if ($row->created_at) {
                        return $row->created_at->format('d M Y');
                    }

                    return "";
                })
                ->editColumn('jumlah', function($row) {
                    return number_format($row->jumlah,0,',','.');
                })
                ->editColumn('nominal', function($row) {
                    return "Rp " . number_format($row->nominal,0,',','.');
                })
                ->make(true);
        }
    }

    public function chart() {
        $labels = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

        $thisWeekData = LogKeuangan::selectRaw('DATE(created_at) as date, SUM(nominal) as nominal') // Ambil tanggal dan agregasi (contoh: SUM)
        ->where('status', 'Laku')
        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->groupBy('date') // Group berdasarkan tanggal
        ->orderBy('date', 'asc') // Urutkan berdasarkan tanggal
        ->get()
        ->keyBy('date'); // Mengubah hasil menjadi array dengan key = tanggal

        $lastWeekData = LogKeuangan::selectRaw('DATE(created_at) as date, SUM(nominal) as nominal') // Ambil tanggal dan agregasi (contoh: SUM)
        ->where('status', 'Laku')
        ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
        ->groupBy('date') // Group berdasarkan tanggal
        ->orderBy('date', 'asc') // Urutkan berdasarkan tanggal
        ->get()
        ->keyBy('date'); // Mengubah hasil menjadi array dengan key = tanggal

        // Langkah 2: Buat daftar semua hari dalam seminggu
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $datesOfWeek = [];
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $datesOfWeek[] = $date->format('Y-m-d');
        }

        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $datesOfLastWeek = [];
        for ($date = $startOfLastWeek; $date->lte($endOfLastWeek); $date->addDay()) {
            $datesOfLastWeek[] = $date->format('Y-m-d');
        }

        // Langkah 3: Gabungkan data dari database dengan hari kosong
        $resultThisWeek = collect($datesOfWeek)->map(function ($date) use ($thisWeekData) {
            return
            ['nominal' => $thisWeekData->get($date)->nominal ?? 0];
        });
        $resultLastWeek = collect($datesOfLastWeek)->map(function ($date) use ($lastWeekData) {
            return
            ['nominal' => $lastWeekData->get($date)->nominal ?? 0];
        });

        return [
            'labels' => $labels,
            'thisWeekData' => $resultThisWeek->flatten(),
            'lastWeekData' => $resultLastWeek->flatten(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        $retails = Retail::all();

        return view('log.transaksi.create', ['barangs' => $barangs, 'retails' => $retails]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $laku = [];
        $rugi = [];

        foreach ($request->status as $key => $status) {
            if ($status == 1) {
                $laku[] = $key;
            } else {
                $rugi[] = $key;
            }
        }

        try {
            if ($laku != []) {
                foreach ($laku as $key => $value) {
                    $retail = Retail::with('barangs')->find($request->retail[$value]);
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogKeuangan::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
                    ]);

                    $retail->barangs->find($request->barang[$value])->pivot->jumlah = $retail->barangs->find($request->barang[$value])->pivot->jumlah - $jumlah;
                    $retail->push();
                }
            }

            if ($rugi != []) {
                foreach ($rugi as $key => $value) {
                    $retail = Retail::with('barangs')->find($request->retail[$value]);
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogKeuangan::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
                    ]);

                    $retail->barangs->find($request->barang[$value])->pivot->jumlah = $retail->barangs->find($request->barang[$value])->pivot->jumlah + $jumlah;
                    $retail->push();

                    $gudang->jumlah = $gudang->jumlah - $jumlah;
                    $gudang->save();
                }
            }
        } catch(Exception $e) {
            return redirect()->route('log.keuangan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.keuangan.index')->with('success', 'Log Berhasil Dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LogKeuangan $logKeuangan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogKeuangan $logKeuangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLogKeuanganRequest $request, LogKeuangan $logKeuangan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogKeuangan $logKeuangan)
    {
        //
    }
}
