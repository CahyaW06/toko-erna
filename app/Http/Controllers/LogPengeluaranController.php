<?php

namespace App\Http\Controllers;

use App\Models\LogPengeluaran;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LogPengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('log.pengeluaran.index');
    }

    public function getDatas(Request $request)
    {
        if ($request->ajax()) {
            $data = LogPengeluaran::get()->reverse();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    if ($row->created_at) {
                        return $row->created_at->format('d M Y');
                    }

                    return "";
                })
                ->editColumn('nominal', function($row) {
                    return "Rp" . number_format($row->nominal,0,',','.');
                })
                ->addColumn('aksi', function($row){
                    $csrfToken = csrf_field();
                    $methodField = method_field('DELETE');
                    $editUrl = route('log.pengeluaran.edit', ['pengeluaran' => $row->id]);
                    $deleteUrl = route('log.pengeluaran.destroy', ['pengeluaran' => $row->id]);

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
        return view('log.pengeluaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            foreach ($request->nama as $key => $value) {
                $nominal = str_replace(".", "", $request->nominal[$key]);

                LogPengeluaran::create([
                    'nama' => $request->nama[$key],
                    'nominal' => $nominal,
                    'created_at' => $request->tanggal[$key],
                ]);
            }
        } catch (Exception $e) {
            return redirect()->route('log.pengeluaran.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.pengeluaran.index')->with('success', 'Log Berhasil Dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LogPengeluaran $logPengeluaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $log = LogPengeluaran::find($request->route('pengeluaran'));

        return view('log.pengeluaran.edit', [
            'log' => $log,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'nominal' => 'required',
            'tanggal' => 'required',
        ]);

        try {
            $logId = $request->route('pengeluaran');
            $log = LogPengeluaran::find($logId);
            $validated['nominal'] = str_replace('.', '', $validated['nominal']);

            $log->update($validated);
        } catch (Exception $e) {
            return redirect()->route('log.pengeluaran.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.pengeluaran.index')->with('success', 'Log berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $logId = $request->route('pengeluaran');
            $log = LogPengeluaran::find($logId);

            $log->delete();
        } catch (Exception $e) {
            return redirect()->route('log.pengeluaran.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.pengeluaran.index')->with('success', 'Log berhasil dihapus!');
    }
}
