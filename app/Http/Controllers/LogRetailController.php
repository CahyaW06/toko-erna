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
                ->editColumn('nominal', function($row) {
                    return "Rp " . number_format($row->nominal,0,',','.');
                })
                ->addColumn('aksi', function($row){
                    $csrfToken = csrf_field();
                    $methodField = method_field('DELETE');
                    $editUrl = route('log.barang.edit', ['barang' => $row->id]);
                    $deleteUrl = route('log.barang.destroy', ['barang' => $row->id]);

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
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogRetail::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
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
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogRetail::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
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
    public function edit(Request $request)
    {
        $log = LogRetail::find($request->route('barang'));
        $barangs = Barang::all();
        $retails = Retail::all();

        return view('log.retail.edit', [
            'log' => $log,
            'barangs' => $barangs,
            'retails' => $retails,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'barang' => 'required',
            'retail' => 'required',
            'status' => 'required',
            'jumlah' => 'required',
            'nominal' => 'required',
        ]);

        try {
            $logId = $request->route('barang');
            $log = LogRetail::find($logId);

            // Kembalikan kondisi gudang
            $retailLama = Retail::find($log->retail_id);
            $statusLama = $log->status;
            if ($statusLama == "Diterima") {
                $retailLama->barangs->find($log->barang_id)->pivot->jumlah -= $log->jumlah;
                $retailLama->push();
            } else {
                $retailLama->barangs->find($log->barang_id)->pivot->jumlah += $log->jumlah;
                $retailLama->push();
            }

            // Update log
            $log->barang_id = $validated['barang'];
            $log->retail_id = $validated['retail'];
            $log->status = $validated['status'];
            $log->jumlah = str_replace('.', '', $validated['jumlah']);
            $log->nominal = str_replace('.', '', $validated['nominal']);
            $log->save();

            // Update kondisi gudang
            $retailBaru = Retail::find($validated['retail']);
            $statusBaru = $validated['status'];
            if ($statusBaru == 1) {
                $retailBaru->barangs->find($validated['barang'])->pivot->jumlah += str_replace('.', '', $validated['jumlah']);
                $retailBaru->push();
            } else {
                $retailBaru->barangs->find($validated['barang'])->pivot->jumlah -= str_replace('.', '', $validated['jumlah']);
                $retailBaru->push();
            }

        } catch (Exception $e) {
            return redirect()->route('log.barang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.barang.index')->with('success', 'Log berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $logId = $request->route('barang');
            $log = LogRetail::find($logId);

            $retailLama = Retail::find($log->retail_id);
            $statusLama = $log->status;
            if ($statusLama == "Diterima") {
                $retailLama->barangs->find($log->barang_id)->pivot->jumlah -= $log->jumlah;
                $retailLama->push();
            } else {
                $retailLama->barangs->find($log->barang_id)->pivot->jumlah += $log->jumlah;
                $retailLama->push();
            }

            $log->delete();
        } catch (Exception $e) {
            return redirect()->route('log.barang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.barang.index')->with('success', 'Log berhasil dihapus!');
    }
}
