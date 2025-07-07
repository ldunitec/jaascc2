@extends('adminlte::page')

@section('content')
    <h3>Pagar mensualidad - {{ $cliente->nombre }}</h3>

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
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Año {{ $anio }}</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($meses as $index => $mes)
                                @php $fecha = Carbon::createFromFormat('Y-m', $mes); @endphp
                                <div class="form-check">
                                  <input class="form-check-input mes-checkbox" type="checkbox" name="meses[]" value="{{ $mes }}" data-index="{{ $loop->iteration }}">

                                    <label class="form-check-label">{{ $fecha->translatedFormat('F') }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <h4>Total a pagar: Lps. <span id="total">0.00</span></h4>
        <button class="btn btn-success">Pagar</button>
    </form>
@endsection

@section('js')
<script>
    const precioMensual = 100;

    function actualizarTotal() {
        const total = $('.mes-checkbox:checked').length * precioMensual;
        $('#total').text(total.toFixed(2));
    }

    $(document).on('change', '.mes-checkbox', function () {
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
            checkboxes.each(function () {
                if ($(this).data('index') > index) {
                    $(this).prop('checked', false);
                }
            });
        }

        actualizarTotal();
    });
</script>

@endsection
