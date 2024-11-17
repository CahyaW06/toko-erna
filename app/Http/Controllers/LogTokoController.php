<?php

namespace App\Http\Controllers;

use App\Models\LogStok;
use App\Models\LogToko;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLogTokoRequest;
use App\Http\Requests\UpdateLogTokoRequest;
use App\Models\LogKeuangan;
use Carbon\Carbon;
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
                ->editColumn('pengeluaran', function($row) {
                    return 'Rp ' . number_format($row->pengeluaran,0,',','.');
                })
                ->editColumn('bersih', function($row) {
                    return 'Rp ' . number_format($row->bersih,0,',','.');
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
    public function store(StoreLogTokoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LogToko $logToko)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogToko $logToko)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLogTokoRequest $request, LogToko $logToko)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogToko $logToko)
    {
        //
    }
}
