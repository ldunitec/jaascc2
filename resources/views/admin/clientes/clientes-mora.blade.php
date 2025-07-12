@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de clientes </b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div id="colFormulario" class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">clientes registrados</h3>


                    <div class="card-tools">
                        <button id="btnMostrarFormulario" class="btn btn-primary">Crear cliente</button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="clientesTable"
                            class="table table-bordered table-hover table-striped table-sm  ">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Nro</th>
                                    <th>Nombre del cliente</th>
                                    <th>Dni</th>
                                    <th>Correo</th>
                                    <th>Telefono</th>
                                    <th>Direccion</th>
                                    <th>Activo</th>
                                    <th style="text-align: center">Acción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.card -->
        </div>

        

    </div>
@stop

@section('css')
    @include('admin.css.css')
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(function() {
        let tabla = $("#clientesTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.clientes.dataMora') }}", // Asegúrate que esta ruta funcione
            pageLength: 10,
            language: {
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ ",
                "infoEmpty": "Mostrando 0 a 0 de 0 ",
                "infoFiltered": "(Filtrado de _MAX_ total )",
                "lengthMenu": "Mostrar _MENU_ ",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscador:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            order: [
                [1, 'asc']
            ],
            columnDefs: [{
                    targets: 0,
                    width: '50px'
                }, // Índice
                {
                    targets: -1,
                    width: '100px'
                } // Acciones
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                 {
                    data: 'dni',
                    name: 'dni'
                },
                {
                    data: 'correo',
                    name: 'correo'
                },
                {
                    data: 'telefono',
                    name: 'telefono'
                },
                {
                    data: 'direccion',
                    name: 'direccion'
                },
                {
                    data: 'activo',
                    name: 'activo'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            buttons: [{
                    text: '<i class="fas fa-copy"></i> COPIAR',
                    extend: 'copy',
                    className: 'btn btn-default'
                },
                {
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    extend: 'pdf',
                    className: 'btn btn-danger'
                },
                {
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    extend: 'csv',
                    className: 'btn btn-info'
                },
                {
                    text: '<i class="fas fa-file-excel"></i> EXCEL',
                    extend: 'excel',
                    className: 'btn btn-success'
                },
                {
                    text: '<i class="fas fa-print"></i> IMPRIMIR',
                    extend: 'print',
                    className: 'btn btn-warning'
                }
            ],
            dom: 'Bfrtip'
        });

        // tabla.buttons().container().appendTo('#clientesTable_wrapper .col-md-6:eq(0)');
    });
</script>
@stop
