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
                            <h5 class="mb-0">Año {{ $anio }}</h5>

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
                    <input type="text" class="form-control" id="recibo" required>
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
            <h3 class="card-title">Historial de Pagos</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" ><i class="fas fa-minus"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="display: block;">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Fecha de Registro</th>
                        <th>Mes Pagado</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pagosPorAño as $año => $pagos)
                        <tr class="table-primary">
                            <th colspan="3">{{ $año }}</th>
                        </tr>
                        @foreach ($pagos as $pago)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($pago->created_at)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($pago->mes_pago)->translatedFormat('F Y') }}</td>
                                <td>{{ $pago->monto ?? '—' }}</td>
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
@endsection
