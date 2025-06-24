<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mensualidad;
use App\Models\Recibo;
use App\Models\User;
use Illuminate\Http\Request;

class MensualidadController extends Controller
{


    public function formularioPago($id)
    {
        $cliente = Cliente::findOrFail($id);
        $mensualidades = $cliente->mensualidades()
            ->where('estado', 'pendiente')
            ->orderBy('año')->orderBy('mes')->get();

        return view('mensualidades.pagar', compact('cliente', 'mensualidades'));
    }

    public function realizarPago(Request $request, $id)
    {
        $request->validate([
            'meses' => 'required|array',
        ]);

        $cliente = Cliente::findOrFail($id);
        $idsSeleccionados = $request->input('meses');

        $mensualidades = Mensualidad::whereIn('id', $idsSeleccionados)->get();
        $total = $mensualidades->sum('monto');

        // Crear recibo
        $recibo = Recibo::create([
            'cliente_id' => $cliente->id,
            'fecha_emision' => now(),
            'total_pagado' => $total,
            'usuario_id' => User::auth()->id(),
        ]);

        // Marcar mensualidades como pagadas
        foreach ($mensualidades as $m) {
            $m->update([
                'estado' => 'pagado',
                'fecha_pago' => now(),
                'recibo_id' => $recibo->id,
            ]);
        }

        return redirect()->route('recibos.ver', $recibo->id)->with('success', 'Pago registrado correctamente');
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
    public function create($cliente_id)
    {
        $cliente = Cliente::with('plan')->find($cliente_id);
        $tarifa = $cliente->plan ? $cliente->plan->monto : 0;

        Mensualidad::create([
            'cliente_id' => $cliente->id,
            'mes' => 'Julio',
            'año' => 2025,
            'monto' => $tarifa,
            'estado' => 'pendiente',
        ]);
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
    public function show(Mensualidad $mensualidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mensualidad $mensualidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mensualidad $mensualidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mensualidad $mensualidad)
    {
        //
    }
}
