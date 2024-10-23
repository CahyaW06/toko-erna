<?php

namespace App\Http\Controllers;

use App\Models\LogRetail;
use App\Models\LogKeuangan;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreLogKeuanganRequest;
use App\Http\Requests\UpdateLogKeuanganRequest;
use Illuminate\Http\Request;

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
            $data = LogKeuangan::with('logRetail.barang', 'logRetail.retail')->get()->reverse();

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $logRetails = LogRetail::where('status', 'Diterima')->with('barang', 'retail')->get()->reverse();

        return view('log.transaksi.create', [
            'logRetails' => $logRetails,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLogKeuanganRequest $request)
    {
        //
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
