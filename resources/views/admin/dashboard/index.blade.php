@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div class="row">

        <!-- Card Total Clientes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info" onclick="window.location='{{ route('admin.clientes.index') }}'">
                <div class="inner">
                    <h3>{{ $totalClientes }}</h3>
                    <p>Total de Clientes</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <!-- Card Clientes en Mora -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger" onclick="window.location='{{ route('admin.clientes.clientesmora') }}'">
                <div class="inner">
                    <h3>{{ $clientesEnMora }}</h3>
                    <p>Clientes en Mora</p>
                </div>
                <div class="icon"><i class="fas fa-user-clock"></i></div>
            </div>
        </div>

        <!-- Card Próximos a Corte -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning" onclick="window.location='{{ route('admin.clientes.prox_corte') }}'">
                <div class="inner">
                    <h3>{{ $clientesProxCorte }}</h3>
                    <p>Próximos a Corte</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
        </div>


        <!-- Card Cobros del Día -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary" onclick="window.location='{{ route('admin.pagos.hoy') }}'">
                <div class="inner">
                    <h3>L {{ number_format($cobrosDelDia, 2) }}</h3>
                    <p>Cobros del Día</p>
                </div>
                <div class="icon"><i class="fas fa-cash-register"></i></div>
            </div>
        </div>
        <!-- Card Cobros del mes -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-ligth" onclick="window.location='{{ route('admin.pagos.hoy') }}'">
                <div class="inner">
                    <h3>L </h3>
                    <p>Cobros del Mes</p>
                </div>
                <div class="icon"><i class="fas fa-cash-register"></i></div>
            </div>
        </div>

        <!-- Card Cobros del Día Efectivo -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success" onclick="window.location='{{ route('admin.pagos.hoy') }}'">
                <div class="inner">
                    <h3>L {{ number_format($cobrosEfectivo, 2) }}</h3>
                    <p>Cobros en efectivo</p>
                </div>
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
        </div>
        <!-- Card Cobros del Día  Depositos-->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info" onclick="window.location='{{ route('admin.pagos.hoy') }}'">
                <div class="inner">
                    <h3>L {{ number_format($cobrosDeposito, 2) }}</h3>
                    <p>Cobros por Deposito</p>
                </div>
                <div class="icon"><i class="fas fa-solid fa-landmark"></i></div>
            </div>
        </div>

    </div>

    <!-- Gráfico de Cobros por Mes -->



    {{-- segundo grafico  --}}
    <div class="row">
        <div class="col-12">

            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">cobro por mes</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-header">Cobros por Mes</div>
                    <div class="card-body">
                        <canvas id="cobrosMesChart" height="100"></canvas>
                    </div>
                    {{-- <div class="chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="stackedBarChart"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 567px;"
                            width="453" height="200" class="chartjs-render-monitor"></canvas>
                    </div> --}}
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        const labels = @json($cobrosPorMes->pluck('mes'));
        const data = @json($cobrosPorMes->pluck('total'));


        const ctx = document.getElementById('cobrosMesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total L.',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'blue',
                    borderWidth: 1
                }]
            }
        });
    </script>

    {{-- segunda grafica  --}}

    {{-- <script>
        const meses = @json($meses);
        const efectivo = @json($efectivo);
        const deposito = @json($deposito);

        const ctx = document.getElementById('stackedBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                        label: 'Efectivo',
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        data: efectivo,
                        stack: 'Pagos'
                    },
                    {
                        label: 'Depósito ',
                        backgroundColor: 'rgba(255, 159, 64, 0.7)',
                        data: deposito,
                        stack: 'Pagos'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Cobros por Método de Pago'
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#000',
                        formatter: function(value) {
                            return 'L ' + value.toLocaleString();
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script> --}}

@endsection
