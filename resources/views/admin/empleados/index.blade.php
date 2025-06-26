@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de clientes </b></h1>
    <hr>
@stop
@section('content')
    <div class="container">
        <h2>Empleados</h2>

        <button class="btn btn-primary mb-3" id="btnCreate">Nuevo Empleado</button>

        <table id="indexTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Puesto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('admin.empleados.parciales.modal')
@stop
@section('css')
    @include('admin.css.css')
@stop
@section('js')
    <!-- DataTables + jQuery + SweetAlert -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    @include('admin.empleados.parciales.js')
@stop
