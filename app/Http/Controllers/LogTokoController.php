<?php

namespace App\Http\Controllers;

use App\Models\LogToko;
use App\Http\Requests\StoreLogTokoRequest;
use App\Http\Requests\UpdateLogTokoRequest;

class LogTokoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
