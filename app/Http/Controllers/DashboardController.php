<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Mensualidad;


class DashboardController extends Controller
{
   
public function index()
{
    $totalClientes = Cliente::count();

    $hoy = Carbon::now();
    $añoActual = $hoy->year;
    $mesActual = $hoy->format('F'); // Ej: 'June', 'July', etc.

    // IDs de clientes con mensualidades pendientes hasta hoy
    $clientesConMora = Mensualidad::where('estado', 'pendiente')
        ->where(function ($query) use ($añoActual, $mesActual) {
            $query->where('año', '<', $añoActual)
                  ->orWhere(function ($q) use ($añoActual, $mesActual) {
                      $q->where('año', $añoActual)
                        ->where('mes', '<=', $mesActual);
                  });
        })
        ->pluck('cliente_id')
        ->unique();

        $clientesMoraDetalle = Cliente::whereIn('id', $clientesConMora)
        ->with(['mensualidades' => function($q) use ($añoActual, $mesActual) {
            $q->where('estado', 'pendiente')
              ->where(function ($query) use ($añoActual, $mesActual) {
                  $query->where('año', '<', $añoActual)
                        ->orWhere(function ($q) use ($añoActual, $mesActual) {
                            $q->where('año', $añoActual)
                              ->where('mes', '<=', $mesActual);
                        });
              });
        }])
        ->get();

    $clientesEnMora = Cliente::whereIn('id', $clientesConMora)->count();
    $clientesAlDia = $totalClientes - $clientesEnMora;

    return view('admin.dashboard.index', compact('totalClientes', 'clientesEnMora', 'clientesAlDia','clientesMoraDetalle'));
}
//
}
