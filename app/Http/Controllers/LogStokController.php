<?php

namespace App\Http\Controllers;

use App\Models\LogStok;
use App\Http\Requests\StoreLogStokRequest;
use App\Http\Requests\UpdateLogStokRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
                ->make(true);
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
    public function store(StoreLogStokRequest $request)
    {
        //
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
    public function edit(LogStok $logStok)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLogStokRequest $request, LogStok $logStok)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogStok $logStok)
    {
        //
    }
}
