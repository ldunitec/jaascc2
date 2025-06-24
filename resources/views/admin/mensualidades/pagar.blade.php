@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
   <div class="container">
    <h3>Pago para {{ $cliente->nombre }}</h3>

    <form method="POST" action="{{ route('mensualidades.pagar', $cliente->id) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <h5>Selecciona los meses a pagar:</h5>
                <ul class="list-group">
                    @foreach ($mensualidades as $m)
                        <li class="list-group-item">
                            <input type="checkbox" name="meses[]" value="{{ $m->id }}" class="form-check-input me-2">
                            {{ $m->mes }} {{ $m->año }} - L. {{ number_format($m->monto, 2) }}
                        </li>
                    @endforeach
                </ul>
                <br>
                <button type="submit" class="btn btn-primary">Realizar Pago</button>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')

@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop