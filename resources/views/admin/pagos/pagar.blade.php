@extends('adminlte::page')

@section('content')
    {{-- inicio de ficha de cliente  --}}
    <div class="small-box bg-warning">
        <div class="inner">
            <h3>{{ $cliente->nombre }}</h3>
            <div class="row">
                <div class="col-md-1">Dni:</div>
                <div class="col-md-1"><b>{{ $cliente->dni }}</b></div>
                <div class="col-md-1">Telefono:</div>
                <div class="col-md-2"><b>{{ $cliente->telefono }}</b></div>
                <div class="col-md-1">Direccion:</div>
                <div class="col-md-2"><b>{{ $cliente->direccion }}</b></div>
            </div>
        </div>
        <div class="icon">
            <i class="fas fa-user-plus"></i>
        </div>

    </div>
    {{-- fin de ficha de cliente  --}}
    {{-- inicio de pago  --}}
    @php
        use Carbon\Carbon;

        // Obtener pagos ya hechos
        $pagados = $cliente->pagos->pluck('mes_pago')->map(fn($f) => Carbon::parse($f)->format('Y-m'))->toArray();

        // Generar meses faltantes del año actual hasta fin del siguiente año
        $fechaInicio = now()->startOfYear();
        $fechaFin = now()->addYear()->endOfYear();

        $mesesMostrar = [];
        while ($fechaInicio <= $fechaFin) {
            $mes = $fechaInicio->format('Y-m');
            if (!in_array($mes, $pagados)) {
                $mesesMostrar[] = $mes;
            }
            $fechaInicio->addMonth();
        }

        // Agrupar por año
        $mesesAgrupados = collect($mesesMostrar)
            ->sort()
            ->groupBy(function ($mes) {
                return \Carbon\Carbon::createFromFormat('Y-m', $mes)->year;
            });

    @endphp

    <form action="{{ route('admin.pagos.store') }}" method="POST">
        @csrf
        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

        <div class="row">
            @foreach ($mesesAgrupados as $anio => $meses)
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div>
                                <h5 class="mb-0">Año {{ $anio }}</h5>
                            </div>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" style="display: block;">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($meses as $index => $mes)
                                    @php $fecha = Carbon::createFromFormat('Y-m', $mes); @endphp
                                    <div class="form-check">
                                        <input class="form-check-input mes-checkbox" type="checkbox" name="meses[]"
                                            value="{{ $mes }}" data-index="{{ $loop->iteration }}">
                                        <label style="margin-inline-end: 20px"
                                            class="form-check-label">{{ $fecha->translatedFormat('F') }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-3">
                <div>
                    <label>Recibo #:</label>
                    <input type="text" class="form-control" value="{{ $recibo->recibo + 1 }}" name="recibo"
                        id="recibo" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Método de Pago</label><br>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input metodo-radio" type="radio" name="metodo_pago" id="efectivo"
                            value="Efectivo" checked required>
                        <label class="form-check-label" for="efectivo">Efectivo</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input metodo-radio" type="radio" name="metodo_pago" id="transferencia"
                            value="Transferencia">
                        <label class="form-check-label" for="transferencia">Transferencia</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input metodo-radio" type="radio" name="metodo_pago" id="deposito"
                            value="Deposito">
                        <label class="form-check-label" for="deposito">Depósito</label>
                    </div>
                </div>

                <!-- Campo referencia -->
                <div class="form-group" id="referencia-group" style="display: none;">
                    <label for="referencia">Número de Referencia</label>
                    <input type="text" name="referencia" id="referencia" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <h4>Total a pagar: Lps. <span id="total">0.00</span></h4>
            </div>
            <div class="col-md-1"> <button class="btn btn-success">Pagar</button></div>
        </div>
    </form>

    {{-- fin de pagos --}}
    <hr>
    {{-- inicio de historial  --}}







    @php
        $pagosPorAño = $cliente->pagos->sortByDesc('mes_pago')->groupBy(function ($pago) {
            return \Carbon\Carbon::parse($pago->created_at)->format('Y');
        });
    @endphp
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><b>Historial de Pagos</b></h3>
            <div class="card-tools">
                <button id="btnExportarWhatsapp" class="btn btn-success">📤 Exportar y enviar PDF</button>

                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="display: block;">
            <div class="card-body">
                <table id="historialTable" class="table table-bordered table-hover table-striped table-sm">

                    <thead>
                        <tr>
                            <th>Fecha de pago</th>
                            <th>Mes Pagado</th>
                            <th>Recibo</th>
                            <th>Metodo</th>
                            <th>Referencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagosPorAño as $año => $pagos)
                            {{-- <tr class="table-primary">
                            <th colspan="3">{{ $año }}</th>
                        </tr> --}}
                            @foreach ($pagos as $pago)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pago->mes_pago)->translatedFormat('F Y') }}</td>
                                    <td>{{ $pago->recibo ?? '—' }}</td>
                                    <td>{{ $pago->metodo_pago ?? '—' }}</td>
                                    <td>{{ $pago->referencia ?? '—' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>



        {{-- fin de historial  --}}
    @endsection

    @section('js')
        <script>
            const precioMensual = 100;

            function actualizarTotal() {
                const total = $('.mes-checkbox:checked').length * precioMensual;
                $('#total').text(total.toFixed(2));
            }

            $(document).on('change', '.mes-checkbox', function() {
                const checkboxes = $('.mes-checkbox').sort((a, b) => $(a).data('index') - $(b).data('index'));

                const index = $(this).data('index');

                if (this.checked) {
                    // Validar que todos los anteriores estén marcados
                    let valido = true;
                    // checkboxes.each(function () {
                    //     if ($(this).data('index') < index && !this.checked) {
                    //         valido = false;
                    //         return false;
                    //     }
                    // });

                    if (!valido) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Selección inválida',
                            text: 'No puedes saltarte meses. Marca los anteriores primero.',
                        });
                        $(this).prop('checked', false);
                    }

                } else {
                    // Si desmarcas uno, desmarca todos los posteriores
                    checkboxes.each(function() {
                        if ($(this).data('index') > index) {
                            $(this).prop('checked', false);
                        }
                    });
                }

                actualizarTotal();
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const radios = document.querySelectorAll('.metodo-radio');
                const referenciaGroup = document.getElementById('referencia-group');
                const referenciaInput = document.getElementById('referencia');

                radios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.value === 'Transferencia' || this.value === 'Deposito') {
                            referenciaGroup.style.display = 'block';
                            referenciaInput.setAttribute('required', true);
                        } else {
                            referenciaGroup.style.display = 'none';
                            referenciaInput.removeAttribute('required');
                            referenciaInput.value = '';
                        }
                    });
                });
            });
        </script>
        {{-- datatable  --}}
        {{-- <script>
     $(function() {
            $("#historialTable").DataTable({
                "pageLength": 12,
                 "order": [[ 4, "desc" ]],
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ meses",
                    "infoEmpty": "Mostrando 0 a 0 de 0 meses",
                    "infoFiltered": "(Filtrado de _MAX_ total meses)",
                    "lengthMenu": "Mostrar _MENU_ meses",
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
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
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
                ]
         }).buttons().container().appendTo('#historialTable_wrapper .row:eq(0)');
            });
</script> --}}

        {{-- pdf  --}}


        {{-- <script>
                $(document).ready(function() {

                    $('#historialTable').DataTable({
                        dom: 'Bfrtip',
                        "pageLength": 12,
                        "order": [
                            [3, "desc"]
                        ],
                        "responsive": true,
                        "lengthChange": true,
                        "autoWidth": false,
                        "language": {
                            "emptyTable": "No hay información",
                            "info": "Mostrando _START_ a _END_ de _TOTAL_ meses",
                            "infoEmpty": "Mostrando 0 a 0 de 0 meses",
                            "infoFiltered": "(Filtrado de _MAX_ total meses)",
                            "lengthMenu": "Mostrar _MENU_ meses",
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
                        buttons: [{
                                extend: 'pdfHtml5',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: 'btn btn-danger',
                                title: 'Reporte Historial de Pagos',
                                filename: 'historial_pagos_' + cliente.nombre.replace(/\s+/g, '_'),
                                orientation: 'portrait', // o 'portrait'
                                pageSize: 'A4',
                                customize: function(doc) {
                                    // Cambiar márgenes
                                    doc.pageMargins = [60, 20, 40, 60];
        
                                    // Cambiar estilos del título
                                    doc.styles.title = {
                                        fontSize: 18,
                                        bold: true,
                                        alignment: 'center',
                                        color: '#3366cc'
                                    };

                                    // Pie de página personalizado
                                    doc.footer = function(currentPage, pageCount) {
                                        return {
                                            columns: [{
                                                    text: 'Generado el: ' + new Date()
                                                        .toLocaleDateString(),
                                                    alignment: 'left',
                                                    margin: [40, 0]
                                                },
                                                {
                                                    text: 'Página ' + currentPage.toString() +
                                                        ' de ' + pageCount,
                                                    alignment: 'right',
                                                    margin: [0, 0, 40]
                                                }
                                            ],
                                            fontSize: 10
                                        }
                                    };

                                    

                                    // Cambiar color de encabezados
                                    var headerCells = doc.content[1].table.headerRows ? doc.content[1].table
                                        .body[0] : [];
                                    headerCells.forEach(function(cell) {
                                        cell.fillColor = '#d2e3fc';
                                        cell.color = '#000';
                                        cell.alignment = 'center';
                                        cell.bold = true;
                                    });





                                    // Alinear celdas del cuerpo
                                    doc.content[1].table.body.slice(1).forEach(function(row) {
                                        row.forEach(function(cell) {
                                            cell.alignment = 'left';
                                        });
                                    });

                                    // Anchos de columnas personalizados
                                    doc.content[1].table.widths = ['20%', '25%', '15%', '20%', '20%'];
                                }
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
                        ]
                    });
                });
            </script> --}}
        {{-- <script>
            const cliente = @json($cliente); // Esto viene desde el controlador Laravel
            @include('admin.pagos.logo')
            $(document).ready(function() {
                $('#historialTable').DataTable({
                    dom: 'Bfrtip',
                    "pageLength": 12,
                    "order": [
                        [2, "desc"]
                    ],
                    buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger',
                        title: '',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        filename: 'historial_pagos_' + cliente.nombre.replace(/\s+/g, '_'),
                        customize: function(doc) {
                            // Encabezado con logo y datos del cliente
                            const encabezado = {
                                margin: [0, 0, 0, 20],
                                columns: [{
                                        image: logoBase64,
                                        width: 70
                                    },
                                    {
                                        margin: [10, 0, 0, 0],
                                        alignment: 'center',
                                        stack: [{
                                                text: 'Junta Administradora de agua y Saneamiento ',
                                                fontSize: 16,
                                                bold: true,
                                                alignment: 'center',
                                                margin: [0, 0, 40, 5]
                                            },
                                            {
                                                text: ' Colonia El Carpintero',
                                                fontSize: 16,
                                                bold: true,
                                                alignment: 'center',
                                                margin: [0, 0, 0, 10]
                                            },
                                            {
                                                text: 'Cliente: ' + cliente.nombre,
                                                fontSize: 12,
                                                bold: true
                                            },
                                            {
                                                text: 'DNI: ' + cliente.dni,
                                                fontSize: 10,
                                                bold: true
                                            },
                                            {
                                                text: 'Correo: ' + cliente.correo,
                                                fontSize: 10,
                                                bold: true
                                            },
                                            {
                                                text: 'Teléfono: ' + cliente.telefono,
                                                fontSize: 10,
                                                bold: true
                                            },
                                         
                                            {
                                                text: 'Historial de Pagos',
                                                fontSize: 16,
                                                bold: true,
                                                alignment: 'center',
                                                margin: [0, 0, 0, 5]
                                            },
                                        ]
                                    }
                                ]
                            };

                            // Inserta el encabezado antes de la tabla
                            doc.content.splice(0, 0, encabezado);

                            // Ancho de columnas
                            doc.content[1].table.widths = ['20%', '20%', '20%', '20%', '20%'];

                            // Estilo del encabezado de la tabla
                            const headers = doc.content[1].table.body[0];
                            headers.forEach(h => {
                                h.fillColor = '	#5DC1B9';
                                h.alignment = 'center';
                                h.bold = true;
                            });

                            // Pie de página
                            doc.footer = function(currentPage, pageCount) {
                                return {
                                    columns: [{
                                            text: 'JAASCC',
                                            alignment: 'left',
                                            margin: [40, 0]
                                        },
                                           {
                                                text: 'Fecha: ' + new Date()
                                                    .toLocaleDateString(),
                                                    alignment: 'center',
                                                fontSize: 10
                                            },
                                        {
                                            text: 'Página ' + currentPage + ' de ' +
                                                pageCount,
                                            alignment: 'right',
                                            margin: [0, 0, 40]
                                        }
                                    ],
                                    fontSize: 9
                                };
                            };
                        }
                    }]
                });
            });
        </script> --}}

        <script>
            const cliente = @json($cliente);
            @include('admin.pagos.logo')

            $('#btnExportarWhatsapp').on('click', function() {
                const table = $('#historialTable').DataTable();

                const docDefinition = {
                    pageSize: 'A4',
                    pageMargins: [40, 120, 40, 60],
                    content: [{
                            columns: [{
                                    image: logoBase64,
                                    width: 60
                                },
                                {
                                    margin: [20, 0],
                                    stack: [{
                                            text: 'Historial de Pagos',
                                            fontSize: 16,
                                            bold: true
                                        },
                                        {
                                            text: 'Cliente: ' + cliente.nombre,
                                            fontSize: 12
                                        },
                                        {
                                            text: 'DNI: ' + cliente.dni,
                                            fontSize: 10
                                        },
                                        {
                                            text: 'Teléfono: ' + cliente.telefono,
                                            fontSize: 10
                                        },
                                        {
                                            text: 'Fecha: ' + new Date().toLocaleDateString(),
                                            fontSize: 10
                                        }
                                    ]
                                }
                            ],
                            margin: [0, 0, 0, 20]
                        },
                        {
                            table: {
                                headerRows: 1,
                                widths: ['20%', '20%', '20%', '20%', '20%'],
                                body: [
                                    // encabezados
                                    ['Fecha de Pago', 'Mes Pagado', 'Método', 'Referencia', 'Recibo'].map(
                                        h => ({
                                            text: h,
                                            fillColor: '#f1f1f1',
                                            bold: true,
                                            alignment: 'center'
                                        })),
                                    // datos
                                    ...table.rows({
                                        search: 'applied'
                                    }).data().toArray().map(row => {
                                        return row.map(col => ({
                                            text: col,
                                            alignment: 'center',
                                            fontSize: 10
                                        }));
                                    })
                                ]
                            }
                        }
                    ]
                };
                 const url = "{{ url('admin/pagos/guardar-pdf') }}";
                pdfMake.createPdf(docDefinition).getBase64(function(base64) {
                    $.post(url, {
                        _token: '{{ csrf_token() }}',
                        base64pdf: base64,
                        nombre: 'historial_' + cliente.dni + '.pdf'
                    }, function(urlFinal) {
                        const mensaje =
                            `Hola *${cliente.nombre}*, aquí está tu comprobante de pago:\n${urlFinal}`;
                        const telefono = cliente.telefono.replace(/[^0-9]/g, ''); // Limpiar caracteres
                        const enlace =
                            `https://web.whatsapp.com/send?phone=+504${telefono}&text=${encodeURIComponent(mensaje)}`;
                        window.open(enlace, '_blank');
                    });
                });
            });
        </script>

    @endsection
