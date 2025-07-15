@extends('adminlte::page')

@section('content')
    {{-- inicio de ficha de cliente  --}}
    <div class="small-box bg-warning">
        <div class="inner">
            <h3>{{ $cliente->nombre }}</h3>
            <div class="row">
                <div class="col-md-1">Dni:</div>
                <div class="col-md-2"><b>{{ $cliente->dni }}</b></div>
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
            $mesesMostrar[] = $fechaInicio->format('Y-m');
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
            <div class="col-md-12">
                @foreach ($mesesAgrupados as $anio => $meses)
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-11">
                                    <div>
                                        <h5 class="mb-0">Año {{ $anio }}</h5>
                                    </div>
                                </div>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" style="display: block;">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($meses as $index => $mes)
                                    @php
                                        $fecha = Carbon::createFromFormat('Y-m', $mes);
                                        $pagado = in_array($mes, $pagados); // Asegúrate de que $mesesPagados esté disponible
                                    @endphp
                                    <div class="form-check {{ $pagado ? 'text-muted' : '' }}">
                                        <input class="form-check-input mes-checkbox" type="checkbox" name="meses[]"
                                            value="{{ $mes }}" data-index="{{ $loop->iteration }}"
                                            {{ $pagado ? 'checked disabled' : '' }}>
                                        <label style="margin-inline-end: 20px" class="form-check-label">
                                            {{ $fecha->translatedFormat('F') }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div>
                    <label>Recibo #:</label>

                    @if (isset($recibo) && !is_null($recibo->recibo))
                        <input type="text" class="form-control" value="{{ $recibo->recibo + 1 }}" name="recibo"
                            id="recibo" required>
                    @else
                        <input type="text" class="form-control" value="00001" name="recibo" id="recibo" required>
                    @endif
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

                    {{-- <div class="form-check form-check-inline">
                        <input class="form-check-input metodo-radio" type="radio" name="metodo_pago" id="transferencia"
                            value="Transferencia">
                        <label class="form-check-label" for="transferencia">Transferencia</label>
                    </div> --}}

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
            <input type="hidden" type="text" name="monto" id="monto">
            <div class="col-md-1"> <button class="btn btn-success">Pagar</button></div>
        </div>
    </form>

    {{-- fin de pagos --}}
    <hr>
    {{-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: --}}
    <div  class="btn-group">
        <button id="btnRecibos" class="btn btn-info" style="display: block">Ver Recibos</button>
        <button id="btnMeses" class="btn btn-info" style="display: none">Ver Meses</button>
        <a href="{{ route('admin.pagos.pdf', $cliente->id) }}" class="btn btn-danger" target="_blank">
    <i class="fas fa-file-pdf"></i> Exportar PDF
</a>>
    </div>
    <hr>
    @php
        $pagosPorAño = $cliente->pagos->sortByDesc('mes_pago')->groupBy(function ($pago) {
            return \Carbon\Carbon::parse($pago->created_at)->format('Y');
        });
    @endphp
    <div id="cardMeses" style="display: block;" class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><b>Historial de meses pagados</b></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="mesesTable" class="table table-bordered table-hover table-striped table-sm">

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
                        @foreach ($pagos as $pago)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($pago->mes_pago)->translatedFormat('F Y') }}</td>
                                <td>{{ $pago->recibo ?? '—' }}</td>
                                <td>
                                    @if ($pago->metodo_pago == 'Efectivo')
                                        <button class="btn btn-success"> {{ $pago->metodo_pago ?? '—' }}</button>
                                    @else
                                        <button class="btn btn-warning"> {{ $pago->metodo_pago ?? '—' }}</button>
                                    @endif
                                </td>
                                <td>{{ $pago->referencia ?? '—' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    {{-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: --}}

    {{-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: --}}

    @php
        $pagosPorAño = $cliente->pagos->sortByDesc('mes_pago')->groupBy(function ($pago) {
            return \Carbon\Carbon::parse($pago->created_at)->format('Y');
        });
    @endphp
    <div id="cardRecibos" style="display: none;" class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title"><b>Historial de Recibos de Pago</b></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="recibosTable" class="table table-bordered table-hover table-striped table-sm">

                <thead>
                    <tr>
                        <th>Fecha de pago</th>
                        <th>Monto</th>
                        <th style="text-align: center">Recibo</th>
                        <th>Metodo</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($pagosPorAño as $año => $pagos) --}}
                    @foreach ($recibos as $recibo)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($recibo->created_at)->format('d/m/Y') }}</td>
                            <td> L. {{ $recibo->monto ?? '—' }}</td>
                            <td>{{ $recibo->recibo ?? '—' }}</td>
                            <td>
                                @if ($recibo->metodo_pago == 'Efectivo')
                                    <button class="btn btn-success"> {{ $recibo->metodo_pago ?? '—' }}</button>
                                @else
                                    <button class="btn btn-warning"> {{ $recibo->metodo_pago ?? '—' }}</button>
                                @endif
                            </td>
                            <td>{{ $recibo->referencia ?? '—' }}</td>
                        </tr>
                    @endforeach
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    {{-- ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: --}}
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#mesesTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "language": {
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
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#recibosTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "language": {
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
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnRecibos = document.getElementById('btnRecibos');
            const btnMeses = document.getElementById('btnMeses');

            const cardRecibos = document.getElementById('cardRecibos');
            const cardMeses = document.getElementById('cardMeses');

            btnRecibos.addEventListener('click', function() {
                cardRecibos.style.display = 'block';
                cardMeses.style.display = 'none';

                btnRecibos.style.display = 'none';
                btnMeses.style.display = 'inline-block';
            });

            btnMeses.addEventListener('click', function() {
                cardRecibos.style.display = 'none';
                cardMeses.style.display = 'block';

                btnMeses.style.display = 'none';
                btnRecibos.style.display = 'inline-block';
            });
        });
    </script>

    <script>
        const precioMensual = 100;

        // function actualizarTotal() {
        //     const total = $('.mes-checkbox:checked').length * precioMensual;
        //     $('#total').text(total.toFixed(2));
        // }
        function actualizarTotal() {
            let total = 0;
            $('.mes-checkbox:checked:not(:disabled)').each(function() {
                total += precioMensual;
            });
            $('#total').text(total.toFixed(2));
            $('#monto').val(total);
        }


        $(document).on('change', '.mes-checkbox', function() {
            const checkboxes = $('.mes-checkbox').sort((a, b) => $(a).data('index') - $(b).data('index'));

            const index = $(this).data('index');

            if (this.checked) {
                // Validar que todos los anteriores estén marcados
                let valido = true;
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
                }).done(function() {
                    // Esperamos un poco para que el backend termine de guardar (opcional)
                    setTimeout(() => {
                        location.reload(); // Recargar la página después de guardar el PDF
                    }, 500);
                }).fail(function() {
                    alert('Error al guardar el PDF.');
                });

                // ,function(urlFinal) {
                //     const mensaje =
                //         `Hola *${cliente.nombre}*, aquí está tu comprobante de pago:\n${urlFinal}`;
                //     const telefono = cliente.telefono.replace(/[^0-9]/g, ''); // Limpiar caracteres
                //     const enlace =
                //         `https://web.whatsapp.com/send?phone=+504${telefono}&text=${encodeURIComponent(mensaje)}`;
                //     window.open(enlace, '_blank');

            });
        });
    </script>
@endsection
