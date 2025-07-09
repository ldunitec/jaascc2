<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function historial(Cliente $cliente)
    {
        $historial = collect();
        $inicio = now()->subYears(1)->startOfYear();
        $actual = now()->startOfMonth();

        while ($inicio <= $actual) {
            $historial->push($inicio->copy());
            $inicio->addMonth();
        }
        return response()->json($historial);
        // return view('admin.pagos.historial', compact('cliente', 'historial'));
    }
    public function guardar(Request $request)
    {
        $nombre = $request->input('nombre');
        $base64 = $request->input('base64pdf');

        // Guardar PDF en disco
        $ruta =
            public_path("/pdfs/{$nombre}");
        Storage::put($ruta, base64_decode($base64));

        // Retornar URL pública
        $url = asset("storage/pdfs/{$nombre}");
        return response()->json($url);
    }


    public function pagar(Cliente $cliente)
    {
        $historial = collect();
        $inicio = now()->subYears(1)->startOfYear();
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
        // $datos= $request->all();
        // return response()->json($datos);

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'meses' => 'required|array',
            'recibo' => 'required|string',
            'metodo_pago' => 'required|string',
            'referencia' => 'nullable|string|max:100'
        ]);

        foreach ($request->meses as $mes) {
            $mesFormateado = \Carbon\Carbon::createFromFormat('Y-m', $mes)->startOfMonth()->toDateString();
            Pago::create([
                'cliente_id' => $request->cliente_id,
                'mes_pago' => $mesFormateado,
                'recibo' => $request->recibo,
                'metodo_pago' => $request->metodo_pago,
                'referencia' => in_array($request->metodo_pago, ['Transferencia', 'Deposito']) ? $request->referencia : null,
                'created_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Pago registrado correctamente.');
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
