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
//     public function data()
// {
//     $clientes = Cliente::select(['id', 'nombre','correo','telefono','direccion','activo']);

//     return DataTables::of($clientes)
//         ->addIndexColumn()
//         ->editColumn('activo', function ($row) {
//             return $row->activo 
//                 ? '<span class="badge bg-success">Sí</span>' 
//                 : '<span class="badge bg-danger">No</span>';
//         })
//         ->addColumn('action', function ($row) {
//             return '<div class="btn-group">
//                         <button class="btn btn-success btn-sm editCliente" data-id="' . $row->id . '">
//                             <i class="fas fa-pencil-alt"></i>
//                         </button>
//                         <button class="btn btn-danger btn-sm deleteCliente" data-id="' . $row->id . '">
//                             <i class="fas fa-trash"></i>
//                         </button>
//                     </div>';
//         })
//         ->rawColumns(['action', 'activo'])
//         ->make(true);
// }


    public function data()
    {
        $clientes = Cliente::select(['id','nombre','correo','telefono','direccion','activo']);
        // return response()->json($clientes);

        return DataTables::of($clientes)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                        <button class="btn btn-success btn-sm editCliente" id="btnEdit" data-id="' . $row->id . '">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn btn-danger btn-sm deleteCliente" id="btnDelete" data-id="' . $row->id . '">
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
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
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}
