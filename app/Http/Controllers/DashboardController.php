<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Pago;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{


    public function index()
    {
        $hoy = Carbon::now()->format('Y-m-d');

        $totalClientes = Cliente::count();

        $clientesEnMora = Cliente::where(function ($query) {
            $query->whereDoesntHave('pagos') // sin pagos en absoluto
                ->orWhereDoesntHave('pagos', function ($subquery) {
                    $subquery->whereColumn('created_at', '>', 'clientes.created_at');
                });
        })->count();


        $cobrosDelDia = Pago::whereDate('created_at', $hoy)->sum('monto');
        $cobrosMensual = Pago::whereDate('created_at', 'mes')->sum('monto');
        $cobrosEfectivo = Pago::where('metodo_pago', 'Efectivo')->whereDate('created_at', $hoy)->sum('monto');
        $cobrosDeposito = Pago::where('metodo_pago', 'Deposito')->whereDate('created_at', $hoy)->sum('monto');

        $clientesProxCorte = Cliente::whereDoesntHave('pagos', function ($q) {
            $q->where('created_at', '>=', now()->subMonths(2));
        })->count();

        // Cobros por mes
        $cobrosPorMes = Pago::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mes"),
            DB::raw("SUM(monto) as total")
        )->groupBy('mes')->orderBy('mes', 'asc')->get();

        $pagoPorMesYMetodo = DB::table('pagos')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, metodo_pago, SUM(monto) as total")
            ->groupBy('mes', 'metodo_pago')
            ->orderBy('mes')
            ->get();

        // Agrupamos los datos
        $meses = $pagoPorMesYMetodo->pluck('mes')->unique()->values();

        $efectivo = [];
        $deposito = [];

        foreach ($meses as $mes) {
            $efectivo[] = $pagoPorMesYMetodo->firstWhere('mes', $mes)->metodo_pago === 'Efectivo'
                ? $pagoPorMesYMetodo->where('mes', $mes)->where('metodo_pago', 'Efectivo')->sum('total')
                : 0;

            $deposito[] = $pagoPorMesYMetodo->firstWhere('mes', $mes)->metodo_pago === 'Transferencia' || $pagoPorMesYMetodo->firstWhere('mes', $mes)->metodo_pago === 'Depósito'
                ? $pagoPorMesYMetodo->where('mes', $mes)->whereIn('metodo_pago', ['Transferencia', 'Depósito'])->sum('total')
                : 0;
        }
// return response()->json( $cobrosPorMes);
        return view('admin.dashboard.index', compact(
            'totalClientes',
            'clientesEnMora',
            'cobrosDelDia',
            'clientesProxCorte',
            'cobrosPorMes',
            'cobrosEfectivo',
            'cobrosDeposito',
            'meses',
            'efectivo',
            'deposito',
            'cobrosMensual'
        ));


    }


    // public function index()
    // {
    //     $totalClientes = Cliente::count();

    //     $hoy = Carbon::now();
    //     $añoActual = $hoy->year;
    //     $mesActual = $hoy->format('F'); // Ej: 'June', 'July', etc.

    //     // IDs de clientes con mensualidades pendientes hasta hoy
    //     $clientesConMora = Mensualidad::where('estado', 'pendiente')
    //         ->where(function ($query) use ($añoActual, $mesActual) {
    //             $query->where('año', '<', $añoActual)
    //                   ->orWhere(function ($q) use ($añoActual, $mesActual) {
    //                       $q->where('año', $añoActual)
    //                         ->where('mes', '<=', $mesActual);
    //                   });
    //         })
    //         ->pluck('cliente_id')
    //         ->unique();

    //         $clientesMoraDetalle = Cliente::whereIn('id', $clientesConMora)
    //         ->with(['mensualidades' => function($q) use ($añoActual, $mesActual) {
    //             $q->where('estado', 'pendiente')
    //               ->where(function ($query) use ($añoActual, $mesActual) {
    //                   $query->where('año', '<', $añoActual)
    //                         ->orWhere(function ($q) use ($añoActual, $mesActual) {
    //                             $q->where('año', $añoActual)
    //                               ->where('mes', '<=', $mesActual);
    //                         });
    //               });
    //         }])
    //         ->get();

    //     $clientesEnMora = Cliente::whereIn('id', $clientesConMora)->count();
    //     $clientesAlDia = $totalClientes - $clientesEnMora;

    //     return view('admin.dashboard.index', compact('totalClientes', 'clientesEnMora', 'clientesAlDia','clientesMoraDetalle'));
    // }
    //
}
