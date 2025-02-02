<?php

namespace App\Http\Controllers;

use App\Exports\GudangExport;
use App\Models\Barang;
use App\Models\Retail;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('gudang.index');
    }

    public function getListBarang() {
        return Barang::select('nama')
            ->get()
            ->map(function ($item) {
                // Mengubah spasi menjadi underscore pada kolom 'nama'
                $item->nama = str_replace(' ', '_', $item->nama);
                return $item;
            })
            ->toArray();
    }

    public function getDatas(Request $request) {
        if ($request->ajax()) {
            $data = Barang::select('id', 'kode_barang', 'nama', 'jumlah', 'harga', 'updated_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('jumlah', function($row) {
                    return number_format($row->jumlah,0,',','.');
                })
                ->editColumn('harga', function($row) {
                    return "Rp " . number_format($row->harga,0,',','.');
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d M Y');
                })
                ->addColumn('aksi', function($row){
                    $csrfToken = csrf_field();
                    $methodField = method_field('DELETE');
                    $editUrl = route('stok.gudang.edit', ['gudang' => $row->id]);
                    $deleteUrl = route('stok.gudang.destroy', ['gudang' => $row->id]);
                    $namaBarang = $row->nama;

                    $btn = '<form action="'.$deleteUrl.'" method="POST" class="d-flex gap-1">';
                    $btn .= $csrfToken;
                    $btn .= $methodField;
                    $btn .= '<a href="'.$editUrl.'" type="button" class="btn btn-warning btn-sm"><i class="mdi mdi-lead-pencil"></i></a>';
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus item '.$namaBarang.'?\')"><i class="mdi mdi-delete"></i></button>';
                    $btn .= '</form>';

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
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

        try {
            $barang = Barang::create([
                'kode_barang' => $validated['kode_barang'],
                'nama' => $validated['nama'],
                'harga' => str_replace('.', '', $validated['harga']),
                'jumlah' => str_replace('.', '', $validated['jumlah']),
            ]);

            $retails = Retail::all();
            foreach ($retails as $key => $value) {
                $value->barangs()->attach($barang->id);
            }
        } catch (Exception $e) {
            return redirect()->route('stok.gudang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('stok.gudang.index')->with('success', 'Barang berhasil ditambahkan!');
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
            'jumlah' => 'required',
            'harga' => 'required'
        ]);

        $id = $request->route('gudang');
        $barang = Barang::find($id);

        $barang->kode_barang = $validated['kode_barang'];
        $barang->nama = $validated['nama'];
        $barang->jumlah = str_replace('.', '', $validated['jumlah']);
        $barang->harga = str_replace('.', '', $validated['harga']);

        try {
            $barang->save();
        } catch (Exception $e) {
            return redirect()->route('stok.gudang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('stok.gudang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->route('gudang');

        try {
            $barang = Barang::find($id);
            $barang->retails()->detach();
            $barang->delete();
        } catch (Exception $e) {
            return redirect()->route('stok.gudang.index')->with('error', $e->getMessage());
        }
        return redirect()->route('stok.gudang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
