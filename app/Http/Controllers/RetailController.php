<?php

namespace App\Http\Controllers;

use App\Exports\RetailExport;
use App\Models\Barang;
use App\Models\LogKeuangan;
use App\Models\LogToko;
use App\Models\Retail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class RetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::select('nama')->get();
        return view('retail.index', [
            'barangs' => $barangs
        ]);
    }

    public function getRetails(Request $request) {
        if ($request->ajax()) {
            $data = Retail::with(['barangs', 'logKeuangans'])
                ->select('id', 'nama', 'alamat')
                ->get();

            $barangs = Barang::select('id', 'nama')->get();

            $dataTables = DataTables::of($data)->addIndexColumn();

            foreach ($barangs as $barang) {
                $dataTables
                ->addColumn(str_replace(' ', '_', $barang->nama), function($row) use($barang) {
                    $jumlah = $row->barangs->firstWhere('id', $barang->id)->pivot->jumlah;

                    return $jumlah;
                })
                ->rawColumns([str_replace(' ', '_', $barang->nama)]);
            }

            $dataTables
            ->addColumn('omset', function($row) {
                return 'Rp ' . number_format($row->logKeuangans->sum('nominal'),0,',','.');
            })
            ->rawColumns(['omset'])

            ->addColumn('aksi', function($row){
                $csrfToken = csrf_field();
                $methodField = method_field('DELETE');
                $showUrl = route('stok.retail.show', ['retail' => $row->id]);
                $editUrl = route('stok.retail.edit', ['retail' => $row->id]);
                $deleteUrl = route('stok.retail.destroy', ['retail' => $row->id]);
                $namaRetail = $row->nama;

                $btn = '<form action="'.$deleteUrl.'" method="POST" class="d-flex gap-1">';
                $btn .= '<a href="'.$showUrl.'" type="button" class="btn btn-info btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z"/>
                    </svg>
                    </a>';
                $btn .= '<a href="'.$editUrl.'" type="button" class="btn btn-warning btn-sm"><i class="mdi mdi-lead-pencil"></i></a>';
                $btn .= $csrfToken;
                $btn .= $methodField;
                $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus retail '.$namaRetail.'?\')"><i class="mdi mdi-delete"></i></button>';
                $btn .= '</form>';

                return $btn;
            })
            ->rawColumns(['aksi']);

            return $dataTables->make(true);
        }
    }

    public function getRincian(Request $request) {
        if ($request->ajax()) {
            $retailId = $request->route('retail');
            $barangs = Barang::with('logKeuangans')->get();

            $data = $barangs->map(function($barang) use($retailId) {
                return [
                    'kode_barang' => $barang->kode_barang,
                    'nama' => $barang->nama,
                    'hpp' => $barang->harga,
                    'jumlah' => $barang->logKeuangans->where('retail_id', $retailId)->sum('jumlah'),
                    'jumlah_x_hpp' => $barang->logKeuangans->where('retail_id', $retailId)->sum('jumlah') * $barang->harga,
                    'omset' => $barang->logKeuangans->where('retail_id', $retailId)->sum('nominal'),
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
                ;

            return $datatable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('retail.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'alamat' => '',
        ]);

        try {
            Retail::create([
                'nama' => $validated['nama'],
                'alamat' => $validated['alamat'],
            ]);

            $barangs = Barang::all();
            $retail = Retail::get()->last();

            foreach ($barangs as $barang) {
                DB::table('barang_retail')->insert([
                    'barang_id' => $barang->id,
                    'retail_id' => $retail->id,
                    'jumlah' => 0,
                ]);
            }
        } catch (Exception $e) {
            return redirect()->route('stok.retail.index')->with('error', $e->getMessage());
        }

        return redirect()->route('stok.retail.index')->with('success', 'Retail berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $retailId = $request->route('retail');
        $retail = Retail::find($retailId);
        $logToko = LogToko::where('bulan', Carbon::now()->month)->where('tahun', Carbon::now()->year)->first();
        $logKeuangans = LogKeuangan::where('retail_id', $retailId)->get();
        $omset = $logKeuangans->sum('nominal');

        return view('retail.show', [
            'retail' => $retail,
            'omset' => $omset,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->route('retail');

        $retail = Retail::find($id);

        return view('retail.edit', ['retail' => $retail]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
        ]);

        $id = $request->route('retail');
        $retail = Retail::find($id);

        $retail->nama = $validated['nama'];
        $retail->alamat = $request->alamat ?? null;

        try {
            $retail->save();
        } catch (Exception $e) {
            return redirect()->route('stok.retail.index')->with('error', $e->getMessage());
        }

        return redirect()->route('stok.retail.index')->with('success', 'Retail berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->route('retail');

        try {
            $retail = Retail::find($id);
            $retail->delete();
        } catch (Exception $e) {
            return redirect()->route('stok.retail.index')->with('error', $e->getMessage());
        }
        return redirect()->route('stok.retail.index')->with('success', 'Retail berhasil dihapus!');
    }
}
