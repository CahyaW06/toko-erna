<?php

namespace App\Http\Controllers;

use App\Models\LogStok;
use App\Models\LogToko;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogTokoRequest;
use App\Http\Requests\UpdateLogTokoRequest;
use App\Models\Barang;
use App\Models\LogKeuangan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogTokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('log.toko.index');
    }

    public function getDatas(Request $request) {
        if ($request->ajax()) {
            $data = LogToko::all()->reverse();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('omset', function($row) {
                    return 'Rp ' . number_format($row->omset,0,',','.');
                })
                ->editColumn('kotor', function($row) {
                    return 'Rp ' . number_format($row->kotor,0,',','.');
                })
                ->editColumn('pengeluaran', function($row) {
                    return 'Rp ' . number_format($row->pengeluaran,0,',','.');
                })
                ->editColumn('bersih', function($row) {
                    return 'Rp ' . number_format($row->bersih,0,',','.');
                })
                ->addColumn('aksi', function($row){
                    $showUrl = route('log.toko.show', ['toko' => $row->id]);

                    $btn = '<a href="'.$showUrl.'" type="button" class="btn btn-info btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                    </svg>
                    </a>';

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function getRincian(Request $request) {
        if ($request->ajax()) {
            $logTokoId = $request->route('toko');
            $barangs = Barang::with('logToko')->get();

            $data = $barangs->map(function($barang) use($logTokoId) {
                return [
                    'kode_barang' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'hpp' => $barang->harga,
                    'jumlah' => $barang->logToko->find($logTokoId)->pivot->jumlah,
                    'jumlah_x_hpp' => $barang->logToko->find($logTokoId)->pivot->jumlah * $barang->harga,
                    'omset' => $barang->logToko->find($logTokoId)->pivot->omset,
                    'laba_kotor' => $barang->logToko->find($logTokoId)->pivot->omset - ($barang->logToko->find($logTokoId)->pivot->jumlah * $barang->harga),
                ];
            });

            // $logBarang = LogKeuangan::where('status', 'Laku')
            //     ->whereBetween('created_at', [Carbon::create($logToko->tahun, $logToko->bulan)->startOfMonth(), Carbon::create($logToko->tahun, $logToko->bulan)->endOfMonth()])
            //     ->get();

            $datatable = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('hpp', function($row) {
                    return 'Rp ' . number_format($row['hpp'],0,',','.');
                })
                ->editColumn('jumlah', function($row) {
                    return number_format($row['jumlah'],0,',','.');
                })
                ->editColumn('jumlah_x_hpp', function($row) {
                    return 'Rp ' . number_format($row['jumlah_x_hpp'],0,',','.');
                })
                ->editColumn('omset', function($row) {
                    return 'Rp ' . number_format($row['omset'],0,',','.');
                })
                ->editColumn('laba_kotor', function($row) {
                    return 'Rp ' . number_format($row['laba_kotor'],0,',','.');
                })
                ;

            return $datatable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLogTokoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $logTokoId = $request->route('toko');
        $logToko = LogToko::find($logTokoId);

        return view('log.toko.show',
            ['logToko' => $logToko]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogToko $logToko)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLogTokoRequest $request, LogToko $logToko)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogToko $logToko)
    {
        //
    }
}
