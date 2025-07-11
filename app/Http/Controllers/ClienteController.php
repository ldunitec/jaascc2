<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Plan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;


class ClienteController extends Controller
{

    public function historial($id, Request $request)
    {

        $cliente = Cliente::findOrFail($id);

        $año = $request->get('año', now()->year); // Año actual por defecto

        $mensualidades = $cliente->mensualidades()
            ->where('año', $año)
            ->orderBy('año')->orderByRaw("FIELD(mes, 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre')")
            ->get();

        $añosDisponibles = $cliente->mensualidades()
            ->select('año')->distinct()->orderBy('año', 'desc')->pluck('año');

        return view('admin.clientes.historial', compact('cliente', 'mensualidades', 'año', 'añosDisponibles'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('admin.clientes.index', compact('clientes'));
    }


    public function data()
    {
        $clientes = Cliente::select(['id', 'nombre', 'dni', 'correo', 'telefono', 'direccion', 'activo']);
        // return response()->json($clientes);

        return DataTables::of($clientes)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                <button class="btn btn-info btn-sm btnPago" data-id="' . $row->id . '">
                    <i class="fas fa-coins"></i>
                </button>
                <button class="btn btn-warning btn-sm btnHist" data-id="' . $row->id . '">
                    <i class="fas fa-book"></i>
                </button>
                <button class="btn btn-success btn-sm btnEdit" data-id="' . $row->id . '">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button class="btn btn-danger btn-sm btnDelete" data-id="' . $row->id . '">
                    <i class="fas fa-trash"></i>
                </button>
            </div>';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function list()
    {
        return response()->json(['data' => Cliente::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $planes = Plan::all();
        return view('clientes.create', compact('planes'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:clientes,nombre',
                'correo' => 'required|email|unique:clientes,correo',
                'telefono' => 'required|unique:clientes,telefono',
                'direccion' => 'required|string',
            ]);

            Cliente::create($validated);

            return response()->json(['message' => 'Cliente creado correctamente']);
        } catch (\Throwable $e) {
            // Forzar mostrar error exacto en el navegador (útil para desarrollo)
            return response()->json([
                'message' => 'ERROR EN TIEMPO DE EJECUCIÓN',
                'error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile(),
            ], 500);
        }
    }




    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Cliente::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, $id)
{
    $cliente = Cliente::findOrFail($id);

    $request->validate([
        'nombre' => 'required',
        'correo' => 'required|email|unique:clientes,correo,' . $id,
        'telefono' => 'required|unique:clientes,telefono,' . $id,
        'direccion' => 'required',
    ]);

    $cliente->update($request->all());

    return response()->json(['message' => 'Cliente actualizado correctamente']);
}


    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $cliente = Cliente::findOrFail($id);
    $cliente->delete();

    return response()->json(['message' => 'Cliente eliminado correctamente']);
}

}
