<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <title>Historial de Pagos</title>

    {{-- <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .container {
            display: grid;
            grid-template-areas:
                "logo header "
                "recibos recibos"
                "meses meses";
            grid-template-columns: 1fr 3fr;
            gap: 5px;
            background-color: #2196F3;
            padding: 5px;
        }

        .container>div {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
        }

        .container>div.header {
            grid-area: header;
            text-align: center;
        }


        .container>div.recibos {
            grid-area: recibos;
        }

        .container>div.meses {
            grid-area: meses;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style> --}}
</head>
<header>
    <div class="row">
        <div class="col-md-2">
            <img src="{{ public_path('img/jaascc.png') }}" alt="Logo" style="max-height: 80px;">
        </div>
        <div style="text-align: center" class="col-md-8">

            <h3>JUNTA ADMINISTRADORA DE AGUA Y SANEAMIENTO
                COL. EL CARPINTERO</h3>

        </div>
        <div class="col-md-2"> <img src="{{ public_path('img/jaascc.png') }}" alt="Logo" style="max-height: 80px;">
        </div>
    </div>

</header>

<body>


    <div class="container">
        <div class="header-container" style="text-align: center; margin-bottom: 10px;">
            <div class="logo" style="margin-bottom: 10px;">
                <img src="{{ public_path('img/jaascc.png') }}" alt="Logo" style="max-height: 80px;">
            </div>



            <div class="header-text">
                <h2 style="margin: 0;">JAASCC</h2>
                <p style="margin: 0;">Protege el agua</p>
            </div>

            <hr style="margin-top: 10px;">
        </div>


        <div class="recibos">
            <div class="titulo">
                <p>
                <h3>Historial de Pagos</h3>
                </p>
                <p>
                <h4>{{ $cliente->nombre }}</h4>
                </p>

            </div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Mes Pagado</th>
                            <th>Método</th>
                            <th>Referencia</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($historialAgrupado as $recibo => $pagos)
                            @foreach ($pagos as $index => $pago)
                                <tr>
                                    @if ($index == 0)
                                        <td rowspan="{{ $pagos->count() }}">{{ $recibo }}</td>
                                    @endif
                                    <td>{{ \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ $pago->mes_pago->translatedFormat('F Y') }}</td>
                                    <td>{{ ucfirst($pago->metodo_pago) }}</td>
                                    <td>{{ ucfirst($pago->referencia) }}</td>
                                </tr>
                            @endforeach
                        @endforeach

                    </tbody>
                </table>
            </div>
            <hr>
            <div class="meses">
                <h4>meses</h4>
            </div>
        </div>








</body>

</html>
