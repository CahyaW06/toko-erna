<?php

namespace App\Http\Controllers;

use App\Models\LogRetail;
use App\Models\LogKeuangan;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreLogKeuanganRequest;
use App\Http\Requests\UpdateLogKeuanganRequest;
use App\Models\Barang;
use App\Models\Retail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $data = LogKeuangan::with('barang', 'retail')->get()->reverse();

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
                    $editUrl = route('log.keuangan.edit', ['keuangan' => $row->id]);
                    $deleteUrl = route('log.keuangan.destroy', ['keuangan' => $row->id]);

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

    public function chart() {
        $labels = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

        $thisWeekData = LogKeuangan::selectRaw('DATE(created_at) as date, SUM(nominal) as nominal') // Ambil tanggal dan agregasi (contoh: SUM)
        ->where('status', 'Laku')
        ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->groupBy('date') // Group berdasarkan tanggal
        ->orderBy('date', 'asc') // Urutkan berdasarkan tanggal
        ->get()
        ->keyBy('date'); // Mengubah hasil menjadi array dengan key = tanggal

        $lastWeekData = LogKeuangan::selectRaw('DATE(created_at) as date, SUM(nominal) as nominal') // Ambil tanggal dan agregasi (contoh: SUM)
        ->where('status', 'Laku')
        ->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])
        ->groupBy('date') // Group berdasarkan tanggal
        ->orderBy('date', 'asc') // Urutkan berdasarkan tanggal
        ->get()
        ->keyBy('date'); // Mengubah hasil menjadi array dengan key = tanggal

        // Langkah 2: Buat daftar semua hari dalam seminggu
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $datesOfWeek = [];
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $datesOfWeek[] = $date->format('Y-m-d');
        }

        $startOfLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endOfLastWeek = Carbon::now()->subWeek()->endOfWeek();
        $datesOfLastWeek = [];
        for ($date = $startOfLastWeek; $date->lte($endOfLastWeek); $date->addDay()) {
            $datesOfLastWeek[] = $date->format('Y-m-d');
        }

        // Langkah 3: Gabungkan data dari database dengan hari kosong
        $resultThisWeek = collect($datesOfWeek)->map(function ($date) use ($thisWeekData) {
            return
            ['nominal' => $thisWeekData->get($date)->nominal ?? 0];
        });
        $resultLastWeek = collect($datesOfLastWeek)->map(function ($date) use ($lastWeekData) {
            return
            ['nominal' => $lastWeekData->get($date)->nominal ?? 0];
        });

        return [
            'labels' => $labels,
            'thisWeekData' => $resultThisWeek->flatten(),
            'lastWeekData' => $resultLastWeek->flatten(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        $retails = Retail::all();

        return view('log.transaksi.create', ['barangs' => $barangs, 'retails' => $retails]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $laku = [];
        $rugi = [];

        foreach ($request->status as $key => $status) {
            if ($status == 1) {
                $laku[] = $key;
            } else {
                $rugi[] = $key;
            }
        }

        try {
            if ($laku != []) {
                foreach ($laku as $key => $value) {
                    $retail = Retail::with('barangs')->find($request->retail[$value]);
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogKeuangan::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
                        'keterangan' => $request->keterangan[$value],
                        'created_at' => $request->tanggal[$value],
                    ]);

                    if ($request->keterangan[$value] == 1) {
                        $retail->barangs->find($request->barang[$value])->pivot->jumlah = $retail->barangs->find($request->barang[$value])->pivot->jumlah - $jumlah;
                        $retail->push();
                    } else {
                        $gudang->jumlah -= $jumlah;
                        $gudang->save();
                    }
                }
            }

            if ($rugi != []) {
                foreach ($rugi as $key => $value) {
                    $retail = Retail::with('barangs')->find($request->retail[$value]);
                    $gudang = Barang::find($request->barang[$value]);
                    $jumlah = str_replace(".", "", $request->jumlah[$value]);
                    $nominal = str_replace(".", "", $request->nominal[$value]);

                    LogKeuangan::create([
                        'barang_id' => $request->barang[$value],
                        'retail_id' => $request->retail[$value],
                        'status' => $request->status[$value],
                        'jumlah' => $jumlah,
                        'nominal' => $nominal,
                        'keterangan' => $request->keterangan[$value],
                        'created_at' => $request->tanggal[$value],
                    ]);

                    if ($request->keterangan[$value] == 1) {
                        $retail->barangs->find($request->barang[$value])->pivot->jumlah = $retail->barangs->find($request->barang[$value])->pivot->jumlah + $jumlah;
                        $retail->push();
                    }

                    $gudang->jumlah = $gudang->jumlah - $jumlah;
                    $gudang->save();
                }
            }
        } catch(Exception $e) {
            return redirect()->route('log.keuangan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.keuangan.index')->with('success', 'Log Berhasil Dicatat!');
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
        $log = LogKeuangan::find($request->route('keuangan'));
        $barangs = Barang::all();
        $retails = Retail::all();

        return view('log.transaksi.edit', [
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
            'keterangan' => 'required',
            'tanggal' => 'required',
        ]);

        try {
            $logId = $request->route('keuangan');
            $log = LogKeuangan::find($logId);

            // Kembalikan kondisi gudang
            $retailLama = Retail::find($log->retail_id);
            $statusLama = $log->status;
            $ketLama = $log->keterangan;

            if ($ketLama == "Konsinyasi") {
                if ($statusLama == "Laku") {
                    $retailLama->barangs->find($log->barang_id)->pivot->jumlah += $log->jumlah;
                    $retailLama->push();
                } else {
                    $retailLama->barangs->find($log->barang_id)->pivot->jumlah -= $log->jumlah;
                    $retailLama->push();

                    $barang = Barang::find($log->barang_id);
                    $barang->jumlah += $log->jumlah;
                    $barang->save();
                }
            } else {
                $barang = Barang::find($log->barang_id);
                $barang->jumlah += $log->jumlah;
                $barang->save();
            }

            // Update log
            $log->barang_id = $validated['barang'];
            $log->retail_id = $validated['retail'];
            $log->status = $validated['status'];
            $log->jumlah = str_replace('.', '', $validated['jumlah']);
            $log->nominal = str_replace('.', '', $validated['nominal']);
            $log->keterangan = $validated['keterangan'];
            $log->created_at = $validated['tanggal'];
            $log->save();

            // Update kondisi gudang
            $retailBaru = Retail::find($validated['retail']);
            $statusBaru = $validated['status'];
            $ketBaru = $validated['keterangan'];

            if ($ketBaru == 1) {
                if ($statusBaru == 1) {
                    $retailBaru->barangs->find($validated['barang'])->pivot->jumlah -= str_replace('.', '', $validated['jumlah']);
                    $retailBaru->push();
                } else {
                    $retailBaru->barangs->find($validated['barang'])->pivot->jumlah += str_replace('.', '', $validated['jumlah']);
                    $retailBaru->push();

                    $barang = Barang::find($validated['barang']);
                    $barang->jumlah -= str_replace('.', '', $validated['jumlah']);
                    $barang->save();
                }
            } else {
                $barang = Barang::find($validated['barang']);
                $barang->jumlah -= str_replace('.', '', $validated['jumlah']);
                $barang->save();
            }

        } catch (Exception $e) {
            return redirect()->route('log.keuangan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.keuangan.index')->with('success', 'Log berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $logId = $request->route('keuangan');
            $log = LogKeuangan::find($logId);

            // Kembalikan kondisi gudang
            $retailLama = Retail::find($log->retail_id);
            $statusLama = $log->status;
            $ketLama = $log->keterangan;

            if ($ketLama == 'Konsinyasi') {
                if ($statusLama == "Laku") {
                    $retailLama->barangs->find($log->barang_id)->pivot->jumlah += $log->jumlah;
                    $retailLama->push();
                } else {
                    $retailLama->barangs->find($log->barang_id)->pivot->jumlah -= $log->jumlah;
                    $retailLama->push();

                    $barang = Barang::find($log->barang_id);
                    $barang->jumlah += $log->jumlah;
                    $barang->save();
                }
            } else {
                $barang = Barang::find($log->barang_id);
                $barang->jumlah += $log->jumlah;
                $barang->save();
            }

            $log->delete();
        } catch (Exception $e) {
            return redirect()->route('log.keuangan.index')->with('error', $e->getMessage());
        }

        return redirect()->route('log.keuangan.index')->with('success', 'Log berhasil dihapus!');
    }
}
