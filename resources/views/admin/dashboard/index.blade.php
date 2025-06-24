@extends('adminlte::page')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container">
    <h3>Dashboard</h3>

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-light p-3">
                <h5>Total Clientes</h5>
                <h2>{{ $totalClientes }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-danger text-white p-3">
                <h5>Clientes en Mora</h5>
                <h2>{{ $clientesEnMora }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white p-3">
                <h5>Clientes al Día</h5>
                <h2>{{ $clientesAlDia }}</h2>
            </div>
        </div>
    </div>
</div>

<br>
<div class="card">
    <div class="card-header">
        <h5>Clientes en Mora</h5>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Meses Pendientes</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientesMoraDetalle as $cliente)
                    <tr>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>
                            @foreach($cliente->mensualidades as $m)
                                <span class="badge bg-danger">{{ $m->mes }} {{ $m->año }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('mensualidades.formulario', $cliente->id) }}" class="btn btn-sm btn-primary">
                                Pagar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> 
    $(document).ready(function () {
        $('.table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script> 
@stop
