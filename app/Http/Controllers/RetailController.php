<?php

namespace App\Http\Controllers;

use App\Exports\RetailExport;
use App\Models\Barang;
use App\Models\Retail;
use Barryvdh\DomPDF\Facade\Pdf;
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
            $data = Retail::with('barangs')
                ->select('id', 'nama', 'alamat')
                ->orderby('nama')
                ->get();

            $barangs = Barang::select('id', 'nama')->orderBy('kode_barang')->get();

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
            ->addColumn('aksi', function($row){
                $csrfToken = csrf_field();
                $methodField = method_field('DELETE');
                $editUrl = route('stok.retail.edit', ['retail' => $row->id]);
                $deleteUrl = route('stok.retail.destroy', ['retail' => $row->id]);
                $namaRetail = $row->nama;

                $btn = '<form action="'.$deleteUrl.'" method="POST" class="d-flex gap-1">';
                $btn .= $csrfToken;
                $btn .= $methodField;
                $btn .= '<a href="'.$editUrl.'" type="button" class="btn btn-outline-warning btn-sm"><i class="mdi mdi-lead-pencil"></i></a>';
                $btn .= '<button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus item '.$namaRetail.'?\')"><i class="mdi mdi-delete"></i></button>';
                $btn .= '</form>';

                return $btn;
            })
            ->rawColumns(['aksi']);

            return $dataTables->make(true);
        }
    }

    public function exportExcel() {
        $date = date('Y_m_d');
        return Excel::download(new RetailExport, $date . '_retail.xlsx');
    }

    public function exportPdf() {
        $retails = Retail::with('barangs')->get(); // Ambil semua data
        $barangs = Barang::all();
        $date = date('Y_m_d');
        $pdf = Pdf::loadView("retail.print", compact('retails', 'barangs'))
            ->setPaper('a4', 'landscape');
        return $pdf->download($date . '_retail.pdf');
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
    public function show(Retail $retail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Retail $retail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Retail $retail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Retail $retail)
    {
        //
    }
}