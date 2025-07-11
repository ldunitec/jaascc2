<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recibo de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .container {
            border: 1px solid #000;
            padding: 20px;
            width: 600px;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .seccion {
            margin-bottom: 10px;
        }

        .etiqueta {
            font-weight: bold;
            width: 130px;
            display: inline-block;
        }

        .meses {
            margin-top: 10px;
        }

        .meses span {
            display: inline-block;
            padding: 2px 8px;
            border: 1px solid #000;
            margin: 2px;
        }
    </style>
</head>

<body>
    {{-- <div class="row">
    <div class="col-md-2"><img src="" alt="Logo"></div>
    <div class="col-md-10" style="text-align: center">
        <b><h1>JAASCC</h1></b><br>
        <h3>Colonia El Carpintero, Distrito Central</h3>
        <h3>Francisco Morazan, km 12 CarreteraVieja a Olancho</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-4">Cuida el agua,es la fuerza motriz de la naturaleza</div>
    <div class="col-md-4"><b><h2>RECIBO No. {{ $recibo }} </h2></b></div>
    <div class="col-md-4"></div>
</div> --}}
    <div class="container">
        <div class="titulo">
            JAASCC<br>                          
            Colonia El Carpintero, Distrito Central <br>
            Francisco Morazan, km 12 Carretera Vieja a Olancho<br>
            <b>
                <h2>Recibo No. {{ $recibo }}</h2>
            </b>
        </div>

        <div class="row">
            <div class="col-md-9">Recibimos de: {{ $cliente->nombre }}</div>
            <div class="col-md-2">Fecha de: </div>
        </div>
        <div class="row">
            <div class="col-md-9">La cantidad de: cantidad en letras</div>
            <div class="col-md-2">Fecha de: </div>
        </div>
        <div class="row">
            <div class="col-md-9">Por concepto de:  Pago de servicio</div>
            <div class="col-md-2">Fecha de: </div>
        </div>
        {{-- <div class="seccion"><span class="etiqueta">Recibimos de:</span> {{ $cliente->nombre }}</div>
        <div class="seccion"><span class="etiqueta">Fecha:</span> {{ $fecha }}</div>
        <div class="seccion"><span class="etiqueta">POr concepto:</span> {{ $cliente->dni }}</div>
        <div class="seccion"><span class="etiqueta">Teléfono:</span> {{ $cliente->telefono }}</div>
        <div class="seccion"><span class="etiqueta">Método de Pago:</span> {{ $metodo_pago }}</div> --}}

        @if ($referencia)
            <div class="seccion"><span class="etiqueta">Referencia:</span> {{ $referencia }}</div>
        @endif

        <div class="seccion"><span class="etiqueta">Total Pagado:</span> L. {{ number_format($monto, 2) }}</div>

        <div class="meses">
            <strong>Meses Pagados:</strong><br>
            @foreach ($meses as $mes)
                <span>{{ $mes }}</span>
            @endforeach
        </div>

        <br><br>
        <div style="text-align: right">Firma ____________________</div>
    </div>
</body>

</html>
