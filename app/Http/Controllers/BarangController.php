<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Exception;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::paginate(10);
        return view('gudang.index', [
            'barangs' => $barangs
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
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

        return redirect()->route('gudang.index');
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
        return redirect()->route('gudang.index')->with('error', 'Barang berhasil dihapus!');
    }
}
