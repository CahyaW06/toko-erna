<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Retail;
use App\Models\LogStok;
use App\Models\LogRetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class LogStokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('log.gudang.index');
    }

    public function getDatas(Request $request)
    {
        if ($request->ajax()) {
            $data = LogStok::with('barang')->get()->reverse();

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
                    return 'Rp ' . number_format($row->nominal,0,',','.');
                })
                ->addColumn('aksi', function($row){
                    $csrfToken = csrf_field();
                    $methodField = method_field('DELETE');
                    $editUrl = route('log.gudang.edit', ['gudang' => $row->id]);
                    $deleteUrl = route('log.gudang.destroy', ['gudang' => $row->id]);

                    $btn = '<form action="'.$deleteUrl.'" method="POST" class="d-flex gap-1">';
                    $btn .= $csrfToken;
                    $btn .= $methodField;
                    $btn .= '<a href="'.$editUrl.'" type="button" class="btn btn-warning btn-sm"><i class="mdi mdi-lead-pencil"></i></a>';
                    $btn .= '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus log ini?\')"><i class="mdi mdi-delete"></i></button>';
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
        $barangs = Barang::all();

        return view('log.gudang.create', ['barangs' => $barangs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);

        $masuk = [];
        $keluar = [];

        foreach ($request->status as $key => $status) {
            if ($status == 1) {
                $masuk[] = $key;
            } else {
                $keluar[] = $key;
            }
        }

        try {
            if ($masuk != []) {
                foreach ($masuk as $key => $value) {
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogStok::create([
                        'barang_id' => $request->barang[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
                    ]);

                    $gudang->jumlah = $gudang->jumlah + $jumlah;
                    $gudang->save();
                }
            }

            if ($keluar != []) {
                foreach ($keluar as $key => $value) {
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogStok::create([
                        'barang_id' => $request->barang[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
                    ]);

                    $gudang->jumlah = $gudang->jumlah - $jumlah;
                    $gudang->save();
                }
            }
        } catch(Exception $e) {
            return redirect()->route('log.gudang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.gudang.index')->with('success', 'Log Berhasil Dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LogStok $logStok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $log = LogStok::find($request->route('gudang'));
        $barangs = Barang::all();
        return view('log.gudang.edit', [
            'log' => $log,
            'barangs' => $barangs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'barang' => 'required',
            'status' => 'required',
            'jumlah' => 'required',
            'nominal' => 'required'
        ]);

        try {
            $logId = $request->route('gudang');
            $log = LogStok::find($logId);
            $gudangLama = Barang::find($log->barang_id);

            $log->barang_id = $validated['barang'];
            $log->status = $validated['status'];
            $log->jumlah = str_replace('.', '', $validated['jumlah']);
            $log->nominal = str_replace('.', '', $validated['nominal']);

            $gudang = Barang::find($validated['barang']);
            if ($validated['status'] == 1) {
                $gudangLama->jumlah -= str_replace('.', '', $validated['jumlah']);
                $gudang->jumlah += str_replace('.', '', $validated['jumlah']);
            } else {
                $gudangLama->jumlah += str_replace('.', '', $validated['jumlah']);
                $gudang->jumlah -= str_replace('.', '', $validated['jumlah']);
            }

            $gudangLama->save();
            $gudang->save();
            $log->save();
        } catch (Exception $e) {
            return redirect()->route('log.gudang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.gudang.index')->with('success', 'Log berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $logId = $request->route('gudang');
            $log = LogStok::find($logId);

            $gudang = Barang::find($log->barang_id);
            if ($log->status == "Masuk") {
                $gudang->jumlah -= $log->jumlah;
            } else {
                $gudang->jumlah += $log->jumlah;
            }

            $gudang->save();
            $log->delete();
        } catch (Exception $e) {
            return redirect()->route('log.gudang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.gudang.index')->with('success', 'Log berhasil dihapus!');
    }
}
