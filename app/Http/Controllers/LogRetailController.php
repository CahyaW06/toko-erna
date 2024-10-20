<?php

namespace App\Http\Controllers;

use App\Exports\LogRetailExport;
use App\Models\Barang;
use App\Models\LogRetail;
use App\Models\Retail;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LogRetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('log.retail.index');
    }

    public function getDatas(Request $request)
    {
        if ($request->ajax()) {
            $data = LogRetail::with('barang', 'retail')->get()->reverse();

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
                ->make(true);
        }
    }

    public function exportExcel() {
        $date = date('Y_m_d');
        return Excel::download(new LogRetailExport, $date . '_retail.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        $retails = Retail::all();

        return view('log.retail.create', ['barangs' => $barangs, 'retails' => $retails]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $diterima = [];
        $dikembalikan = [];

        foreach ($request->status as $key => $status) {
            if ($status == 1) {
                $diterima[] = $key;
            } else {
                $dikembalikan[] = $key;
            }
        }

        try {
            if ($diterima != []) {
                foreach ($diterima as $key => $value) {
                    $retail = Retail::with('barangs')->find($request->retail[$value]);
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);

                    LogRetail::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                    ]);

                    $retail->barangs->find($request->barang[$value])->pivot->jumlah = $retail->barangs->find($request->barang[$value])->pivot->jumlah + $jumlah;
                    $retail->push();

                    $gudang->jumlah = $gudang->jumlah - $jumlah;
                    $gudang->save();
                }
            }

            if ($dikembalikan != []) {
                foreach ($dikembalikan as $key => $value) {
                    $retail = Retail::with('barangs')->find($request->retail[$value]);
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);

                    LogRetail::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                    ]);

                    $retail->barangs->find($request->barang[$value])->pivot->jumlah = $retail->barangs->find($request->barang[$value])->pivot->jumlah - $jumlah;
                    $retail->push();

                    $gudang->jumlah = $gudang->jumlah + $jumlah;
                    $gudang->save();
                }
            }
        } catch(Exception $e) {
            return redirect()->route('log.barang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.barang.index')->with('success', 'Log Berhasil Dicatat!');
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
    public function edit(LogRetail $logRetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogRetail $logRetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogRetail $logRetail)
    {
        //
    }
}
