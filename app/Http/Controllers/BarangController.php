<?php

namespace App\Http\Controllers;

use App\Exports\GudangExport;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::orderby('kode_barang')->paginate(50);
        // return $barangs;
        return view('gudang.index', [
            'barangs' => $barangs
        ]);
    }

    public function printedData() {
        return view('gudang.print', [
            'barangs' => Barang::all()
        ]);
    }

    public function getDatas(Request $request) {
        if ($request->ajax()) {
            $data = Barang::select('id', 'kode_barang', 'nama', 'harga', 'jumlah', 'updated_at')->orderby('kode_barang');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('harga', function($row) {
                    return "Rp " . number_format($row->harga,0,',','.');
                })
                ->editColumn('jumlah', function($row) {
                    return number_format($row->jumlah,0,',','.') . " pcs";
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d M Y');
                })
                ->addColumn('aksi', function($row){
                    $csrfToken = csrf_field();
                    $methodField = method_field('DELETE');
                    $editUrl = route('gudang.edit', ['gudang' => $row->id]);
                    $deleteUrl = route('gudang.destroy', ['gudang' => $row->id]);
                    $namaBarang = $row->nama;

                    $btn = '<form action="'.$deleteUrl.'" method="POST" class="d-flex gap-1">';
                    $btn .= $csrfToken;
                    $btn .= $methodField;
                    $btn .= '<a href="'.$editUrl.'" type="button" class="btn btn-outline-warning btn-sm"><i class="mdi mdi-lead-pencil"></i></a>';
                    $btn .= '<button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus item '.$namaBarang.'?\')"><i class="mdi mdi-delete"></i></button>';
                    $btn .= '</form>';

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function exportExcel() {
        $date = date('Y_m_d');
        return Excel::download(new GudangExport, $date . '_gudang.xlsx');
    }

    public function exportPdf() {
        $barangs = Barang::all(); // Ambil semua data
        $date = date('Y_m_d');
        $pdf = Pdf::loadView("gudang.print", compact('barangs'));
        return $pdf->download($date . '_gudang.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gudang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required',
            'nama' => 'required',
            'harga' => 'required',
            'jumlah' => 'required'
        ]);

        Barang::create([
            'kode_barang' => $validated['kode_barang'],
            'nama' => $validated['nama'],
            'harga' => str_replace('.', '', $validated['harga']),
            'jumlah' => str_replace('.', '', $validated['jumlah']),
        ]);

        return redirect()->route('gudang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->route('gudang');
        $barang = Barang::find($id);

        return view('gudang.edit', [
            'barang' => $barang
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required',
            'nama' => 'required',
            'harga' => 'required',
            'jumlah' => 'required'
        ]);

        $id = $request->route('gudang');
        $barang = Barang::find($id);

        $barang->kode_barang = $validated['kode_barang'];
        $barang->nama = $validated['nama'];
        $barang->harga = str_replace('.', '', $validated['harga']);
        $barang->jumlah = str_replace('.', '', $validated['jumlah']);

        $barang->save();

        return redirect()->route('gudang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->route('gudang');

        try {
            $barang = Barang::find($id);
            $barang->delete();
        } catch (Exception $e) {
            return redirect()->route('gudang.index')->with('error', 'Barang gagal dihapus!');
        }
        return redirect()->route('gudang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
