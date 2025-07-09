<?php


namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class EmpleadoController extends Controller
{
    public function index()
    {
        return view('admin.empleados.index');
    }

    public function data()

    {
        $empleados = Empleado::select(['id', 'nombre', 'correo', 'telefono', 'puesto']);
        // return response()->json($empleados);

        return DataTables::of($empleados)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
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


    public function store(Request $request)
    {
        $empleado = Empleado::create($request->all());
        return response()->json($empleado);
    }

    public function show($id)
    {
        return response()->json(Empleado::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update($request->all());
        return response()->json($empleado);
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();
        return response()->json(['mensaje' => 'Empleado eliminado']);
    }
}
