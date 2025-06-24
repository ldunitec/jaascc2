@extends('adminlte::page')

@section('content')
<div class="container">
    <h3>Historial de Pagos - {{ $cliente->nombre }}</h3>

    <form method="GET" action="{{ route('clientes.historial', $cliente->id) }}" class="mb-3">
        <label for="año">Filtrar por Año:</label>
        <select name="año" onchange="this.form.submit()" class="form-select w-auto d-inline">
            @foreach($añosDisponibles as $op)
                <option value="{{ $op }}" {{ $año == $op ? 'selected' : '' }}>{{ $op }}</option>
            @endforeach
        </select>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Mes</th>
                <th>Año</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Fecha de Pago</th>
                <th>Recibo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mensualidades as $m)
                <tr>
                    <td>{{ $m->mes }}</td>
                    <td>{{ $m->año }}</td>
                    <td>L. {{ number_format($m->monto, 2) }}</td>
                    <td>
                        @if($m->estado === 'pagado')
                            <span class="badge bg-success">Pagado</span>
                        @else
                            <span class="badge bg-danger">Pendiente</span>
                        @endif
                    </td>
                    <td>{{ $m->fecha_pago ? \Carbon\Carbon::parse($m->fecha_pago)->format('d/m/Y') : '-' }}</td>
                    <td>
                        @if($m->recibo_id)
                            <a href="{{ route('recibos.ver', $m->recibo_id) }}" class="btn btn-sm btn-secondary">Ver Recibo</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay registros para el año seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('mensualidades.formulario', $cliente->id) }}" class="btn btn-primary mt-2">Realizar Pago</a>
</div>
@endsection
