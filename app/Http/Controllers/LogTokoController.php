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
use App\Models\LogPengeluaran;
use App\Models\LogRetail;
use Carbon\Carbon;
use Exception;
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
                ->editColumn('belanja_modal', function($row) {
                    return 'Rp ' . number_format($row->belanja_modal,0,',','.');
                })
                ->editColumn('bersih', function($row) {
                    return 'Rp ' . number_format($row->bersih,0,',','.');
                })
                ->addColumn('aksi', function($row){
                    $showUrl = route('log.toko.show', ['toko' => $row->id]);
                    $showKonsiUrl = route('log.toko.konsi', ['toko' => $row->id]);
                    $editUrl = route('log.toko.edit', ['toko' => $row->id]);
                    $csrfToken = csrf_field();
                    $methodField = method_field('PUT');
                    $updateUrl = route('log.toko.update', ['toko' => $row->id]);

                    $btn = '<form action="'.$updateUrl.'" method="POST" class="d-flex gap-1">';
                    $btn .= $csrfToken;
                    $btn .= $methodField;
                    $btn .= '<a href="'.$showUrl.'" type="button" class="btn btn-info btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                    </svg>
                    </a>';
                    $btn .= '<a href="'.$showKonsiUrl.'" type="button" class="btn btn-warning btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
                    <path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1M.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8z"/>
                    </svg>
                    </a>';
                    $btn .= '<button type="submit" class="btn btn-success btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-repeat" viewBox="0 0 16 16">
                    <path d="M11 5.466V4H5a4 4 0 0 0-3.584 5.777.5.5 0 1 1-.896.446A5 5 0 0 1 5 3h6V1.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384l-2.36 1.966a.25.25 0 0 1-.41-.192m3.81.086a.5.5 0 0 1 .67.225A5 5 0 0 1 11 13H5v1.466a.25.25 0 0 1-.41.192l-2.36-1.966a.25.25 0 0 1 0-.384l2.36-1.966a.25.25 0 0 1 .41.192V12h6a4 4 0 0 0 3.585-5.777.5.5 0 0 1 .225-.67Z"/>
                    </svg></button>';
                    $btn .= '<a href="'.$editUrl.'" type="button" class="btn btn-danger btn-sm"><i class="mdi mdi-lead-pencil"></a>';
                    $btn .= '</form>';

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function getRincian(Request $request) {
        if ($request->ajax()) {
            $logTokoId = $request->route('toko');
            $barangs = Barang::with('logTokos')->get();

            $data = $barangs->map(function($barang) use($logTokoId) {
                return [
                    'kode_barang' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'hpp' => $barang->harga,
                    'jumlah' => $barang->logTokos->find($logTokoId)->pivot->jumlah,
                    'jumlah_x_hpp' => $barang->logTokos->find($logTokoId)->pivot->jumlah * $barang->harga,
                    'omset' => $barang->logTokos->find($logTokoId)->pivot->omset,
                    'laba_kotor' => $barang->logTokos->find($logTokoId)->pivot->omset - ($barang->logTokos->find($logTokoId)->pivot->jumlah * $barang->harga),
                ];
            });

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

    public function getKonsi(Request $request) {
        if ($request->ajax()) {
            $logTokoId = $request->route('toko');
            $barangs = Barang::with('logTokos')->get();

            $data = $barangs->map(function($barang) use($logTokoId) {
                return [
                    'kode_barang' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'hpp' => $barang->harga,
                    'konsinyasi' => $barang->logTokos->find($logTokoId)->pivot->konsinyasi,
                    'nominal_konsinyasi' => $barang->logTokos->find($logTokoId)->pivot->nominal_konsinyasi * $barang->harga,
                ];
            });

            $datatable = DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('hpp', function($row) {
                    return 'Rp ' . number_format($row['hpp'],0,',','.');
                })
                ->editColumn('konsinyasi', function($row) {
                    return number_format($row['konsinyasi'],0,',','.');
                })
                ->editColumn('nominal_konsinyasi', function($row) {
                    return 'Rp ' . number_format($row['nominal_konsinyasi'],0,',','.');
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

    public function showKonsi(Request $request)
    {
        $logTokoId = $request->route('toko');
        $logToko = LogToko::find($logTokoId);

        return view('log.toko.showKonsi',
            ['logToko' => $logToko]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->route('toko');
        $logToko = LogToko::find($id);

        return view('log.toko.edit', [
            'log' => $logToko
        ]);
    }

    public function updateBelanjaModal(Request $request)
    {
        $id = $request->route('toko');
        $logToko = LogToko::find($id);

        $validated = $request->validate([
            'belanja_modal' => 'required'
        ]);

        try {
            $logToko->update([
                'belanja_modal' => str_replace('.', '', $validated['belanja_modal']),
            ]);
        } catch (Exception $e) {
            return redirect()->route('log.toko.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.toko.index')->with('success', 'Log berhasil diupdate');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $logTokoId = $request->route('toko');
            $logTokoNow = LogToko::find($logTokoId);
            $barangs = Barang::all();

            if ($logTokoNow->barangs()->count() != $barangs->count()) {
                $logTokoNow->barangs()->sync($barangs);
                $logTokoNow->save();
            }

            $logBarangLaku = LogKeuangan::where('status', 'Laku')
                ->whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
                ->get();

            $logPengeluaranNow = LogPengeluaran::whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
                ->get();

            $logKonsi = LogRetail::whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
                ->get();

            $logGudang = LogStok::where('status', 'Masuk')
                ->whereBetween('created_at', [Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->startOfMonth(), Carbon::create($logTokoNow->tahun, $logTokoNow->bulan)->endOfMonth()])
                ->get();

            $kotor = 0;

            foreach ($logTokoNow->barangs as $key => $value) {
                $kotor += $logBarangLaku->where('barang_id', $value->id)->sum('jumlah') * $value->harga;
                $value->pivot->jumlah = $logBarangLaku->where('barang_id', $value->id)->sum('jumlah');
                $value->pivot->omset = $logBarangLaku->where('barang_id', $value->id)->sum('nominal');
                $value->pivot->konsinyasi = $logKonsi->where('barang_id', $value->id)->sum('jumlah');
                $value->pivot->nominal_konsinyasi = $logKonsi->where('barang_id', $value->id)->sum('nominal');
                $value->push();
            }

            $omset = $logBarangLaku->sum('nominal');
            $pengeluaran = $logPengeluaranNow->sum('nominal');
            $pengeluaran += $logGudang->sum('nominal');

            $logTokoNow->update([
                'omset' => $omset,
                'kotor' => $kotor,
                'pengeluaran' => $pengeluaran,
                'bersih' => $omset - $kotor - $pengeluaran,
            ]);
        } catch (Exception $e) {
            return redirect()->route('log.toko.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.toko.index')->with('success', 'Log berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogToko $logToko)
    {
        //
    }
}
