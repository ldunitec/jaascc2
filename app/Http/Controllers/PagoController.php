<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pago;
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
        return response()->json($historial);
        $pagosRealizados = $cliente->pagos()
    ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes_pagado")
    ->pluck('mes_pagado')
    ->toArray();

        // return view('admin.pagos.historial', compact('cliente', 'historial'));
    }





    public function guardar(Request $request)
    {
        try {
            $request->validate([
                'base64pdf' => 'required|string',
                'nombre' => 'required|string',
            ]);

            $nombreArchivo = preg_replace('/[^a-zA-Z0-9_\-.]/', '', $request->nombre);
            $pdfData = base64_decode($request->base64pdf, true);

            if ($pdfData === false) {
                return response()->json(['error' => 'Base64 inválido'], 422);
            }

            // ✅ Ruta relativa correcta (NO uses public_path aquí)
            $ruta = 'pdfs/' . $nombreArchivo;

            // ✅ Guarda en storage/app/public/pdfs
            Storage::disk('public')->put($ruta, $pdfData);

            // ✅ Devuelve URL pública
            $url = asset('storage/' . $ruta);

            return response()->json($url);
        } catch (\Exception $e) {
            // \Log::error('Error al guardar PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }



    public function pagar(Cliente $cliente)
    {
        Carbon::setLocale('es'); // Establece español
        $historial = collect();
        $inicio = $cliente->created_at->copy()->addMonth()->startOfMonth();

        $actual = now()->startOfMonth();
        $recibo = Pago::latest('recibo')->first();

        while ($inicio <= $actual) {
            $historial->push($inicio->copy());
            $inicio->addMonth();
        }
        return view('admin.pagos.pagar', compact('cliente', 'historial', 'recibo'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'meses' => 'required|array',
            'recibo' => 'required|string',
            'metodo_pago' => 'required|string',
            'referencia' => 'nullable'
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

        // Total pagado
        $montoTotal = count($mesesPagados) * 100; // suponiendo Lps. 100 por mes

        // Generar el PDF
        $pdf = Pdf::loadView('admin.pagos.recibo', [
            'cliente' => $cliente,
            'recibo' => $request->recibo,
            'fecha' => now()->format('d/m/Y'),
            'monto' => $montoTotal,
            'metodo_pago' => $request->metodo_pago,
            'referencia' => $request->referencia,
            'meses' => $mesesPagados
        ]);

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
