<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReciboController extends Controller
{

    public function ver($id)
{
    $recibo = Recibo::with('cliente', 'mensualidades')->findOrFail($id);
    return view('recibos.ver', compact('recibo'));
}




public function generarPDF($id)
{
    $recibo = Recibo::with('cliente', 'mensualidades')->findOrFail($id);

    $pdf = Pdf::loadView('recibos.pdf', compact('recibo'));
    return $pdf->download('Recibo_' . $recibo->cliente->nombre . '.pdf');
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Recibo $recibo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recibo $recibo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recibo $recibo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recibo $recibo)
    {
        //
    }
}
