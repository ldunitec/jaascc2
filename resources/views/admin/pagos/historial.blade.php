@extends('adminlte::page')

@section('content')
<h3>Historial de Pagos - {{ $cliente->nombre }}</h3>
<table class="table table-bordered table-sm">
    <thead><tr><th>Mes</th><th>Estado</th></tr></thead>
    <tbody>
        @foreach($historial->sortDesc() as $mes)
            @php
                $pagado = $cliente->pagos->pluck('mes_pago')->contains($mes->format('Y-m-d'));
                $enMora = !$pagado && $mes <= now();
            @endphp
            <tr class="{{ $enMora ? 'table-danger' : '' }}">
                <td>{{ $mes->format('F Y') }}</td>
                <td>{{ $pagado ? 'Pagado' : ($enMora ? 'En mora' : 'Pendiente') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
