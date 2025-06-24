@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
   <div class="container">
    <a href="{{ route('recibos.pdf', $recibo->id) }}" class="btn btn-outline-dark" target="_blank">
    Descargar PDF
</a>

    <h3>Recibo de Pago</h3>
    <p><strong>Cliente:</strong> {{ $recibo->cliente->nombre }}</p>
    <p><strong>Fecha:</strong> {{ $recibo->fecha_emision }}</p>
    <p><strong>Total pagado:</strong> L. {{ number_format($recibo->total_pagado, 2) }}</p>

    <h5>Meses pagados:</h5>
    <ul>
        @foreach ($recibo->mensualidades as $m)
            <li>{{ $m->mes }} {{ $m->año }} - L. {{ number_format($m->monto, 2) }}</li>
        @endforeach
    </ul>

    <a href="#" onclick="window.print()" class="btn btn-success">Imprimir</a>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop