<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Pago</title>
    <style>
        body { font-family: DejaVu Sans; }
        .titulo { font-size: 20px; font-weight: bold; text-align: center; }
        .detalle { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="titulo">RECIBO DE PAGO</div>
    <p><strong>Cliente:</strong> {{ $recibo->cliente->nombre }}</p>
    <p><strong>Fecha de emisión:</strong> {{ $recibo->fecha_emision->format('d/m/Y H:i') }}</p>
    <p><strong>Total pagado:</strong> L. {{ number_format($recibo->total_pagado, 2) }}</p>

    <div class="detalle">
        <h4>Meses Pagados:</h4>
        <ul>
            @foreach($recibo->mensualidades as $m)
                <li>{{ $m->mes }} {{ $m->año }} - L. {{ number_format($m->monto, 2) }}</li>
            @endforeach
        </ul>
    </div>

    <br><br>
    <p>______________________________</p>
    <p>Firma del Responsable</p>
</body>
</html>
