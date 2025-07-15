<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Recibo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function historial(Cliente $cliente)
    {
        $historial = collect();
        $pagosRealizados = $cliente->pagos()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes_pagado")
            ->pluck('mes_pagado')
            ->toArray();
        $inicio = now()->subYears(1)->startOfYear();
        $actual = now()->startOfMonth();

        while ($inicio <= $actual) {
            $historial->push($inicio->copy());
            $inicio->addMonth();
        }
        // return response()->json($historial);
        $pagosRealizados = $cliente->pagos()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes_pagado")
            ->pluck('mes_pagado')
            ->toArray();

        // return view('admin.pagos.historial', compact('cliente', 'historial'));
    }


 public function pdf( $id)
{
    //  $datos = $clienteId;
    $cliente = Cliente::findOrFail($id);
    $historial = Pago::where('cliente_id', $id)->orderBy('created_at', 'desc')->get();
    $historialAgrupado = $historial->groupBy('recibo');

    $pdf = Pdf::loadView('admin.pagos.historial_pdf', compact('cliente', 'historial','historialAgrupado'))
              ->setPaper('a4', 'portrait');
 

    return $pdf->download("Historial_{$cliente->nombre}.pdf");
}

    public function pagar(Cliente $cliente)
    {
        // return response()->json($datos);
        Carbon::setLocale('es'); // Establece español
        $historial = collect();
        $actual = now()->startOfMonth();
        $recibo = Pago::latest('recibo')->first();

        $recibos = Recibo::find($cliente);


        return view('admin.pagos.pagar', compact('cliente', 'historial', 'recibo', 'recibos'));
    }


    public function store(Request $request)
    {
        // $datos = $request;
        // return response()->json($datos);
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'meses' => 'required|array',
            'recibo' => 'required|string',
            'metodo_pago' => 'required|string',
            'referencia' => 'nullable',
            'monto' => 'required',
        ]);

        $cliente = Cliente::findOrFail($request->cliente_id);

        $mesesPagados = [];

        foreach ($request->meses as $mes) {
            $mesFormateado = \Carbon\Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
            Pago::create([
                'cliente_id' => $request->cliente_id,
                'mes_pago' => $mesFormateado->toDateString(),
                'recibo' => $request->recibo,
                'metodo_pago' => $request->metodo_pago,
                'referencia' => in_array($request->metodo_pago, ['Transferencia', 'Deposito']) ? $request->referencia : null,
                'created_at' => now(),
            ]);

            $mesesPagados[] = $mesFormateado->translatedFormat('F Y');
        }

        Recibo::create([
            'cliente_id' => $request->cliente_id,
            'recibo' => $request->recibo,
            'metodo_pago' => $request->metodo_pago,
            'referencia' => in_array($request->metodo_pago, ['Transferencia', 'Deposito']) ? $request->referencia : null,
            'monto' => $request->monto,
        ]);

        // Total pagado
        $montoTotal = count($mesesPagados) * 100; // suponiendo Lps. 100 por mes

        // Generar el PDF
        // $pdf = Pdf::loadView('admin.pagos.recibo', [
        //     'cliente' => $cliente,
        //     'recibo' => $request->recibo,
        //     'fecha' => now()->format('d/m/Y'),
        //     'monto' => $montoTotal,
        //     'metodo_pago' => $request->metodo_pago,
        //     'referencia' => $request->referencia,
        //     'meses' => $mesesPagados
        // ]);

        // return $pdf->download('recibo_' . $cliente->dni . '.pdf'); // o ->download(...) si prefieres descarga directa
        return view('admin.clientes.index');
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'cliente_id' => 'required|exists:clientes,id',
    //         'meses' => 'required|array',
    //     ]);

    //     foreach ($request->meses as $mes) {
    //         Pago::updateOrCreate([
    //             'cliente_id' => $request->cliente_id,
    //             'mes_pago' => $mes . '-01'
    //         ], ['monto' => 100]);
    //     }

    //     return redirect()->route('admin.clientes.index')->with('success', 'Pago registrado correctamente');
    // }
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


    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
