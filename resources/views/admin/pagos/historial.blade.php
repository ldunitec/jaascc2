@extends('adminlte::page')

@section('content')
<h3>Historial de Pagos - {{ $cliente->nombre }}</h3>

@php
    $pagosPorAño = $cliente->pagos->sortByDesc('created_at')->groupBy(function($pago) {
        return \Carbon\Carbon::parse($pago->created_at)->format('Y');
    });
@endphp

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Fecha de Registro</th>
            <th>Mes Pagado</th>
            <th>Monto</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pagosPorAño as $año => $pagos)
            <tr class="table-primary">
                <th colspan="3">{{ $año }}</th>
            </tr>
            @foreach($pagos as $pago)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($pago->mes_pago)->translatedFormat('F Y') }}</td>
                    <td>{{ $pago->monto ?? '—' }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
