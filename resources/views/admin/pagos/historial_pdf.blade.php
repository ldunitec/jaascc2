<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Pagos</title>
<style>
    body {
    margin: 0;
    font-family: Arial, sans-serif;
}

.container {
    width: 90%;
    margin: auto;
    border: 1px solid #000;
}

.header {
    display: flex;
    border-bottom: 1px solid #000;
}

.logo {
    flex: 1;
    text-align: center;
    padding: 10px;
    border-right: 1px solid #e11313;
}

.logo.right {
    border-right: none;
}

.title {
    flex: 2;
    text-align: center;
    padding: 10px;
}

.blue-bar {
    height: 10px;
    background-color: #3b6cc0;
}

.section-title {
    text-align: center;
    padding: 10px;
    margin: 0;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
}

.table-container {
    height: 400px; /* puedes ajustar este valor */
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid #000;
}

.footer {
    text-align: center;
    padding: 15px;
    border-bottom: 1px solid #000;
}

</style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <header class="header">
            <div class="logo left"><img src="{{ public_path('img/jaascc.png') }}" alt="Logo" style="max-height: 80px;"></div>
            <div class="title">  <h3>JUNTA ADMINISTRADORA DE AGUA Y SANEAMIENTO
                COL. EL CARPINTERO</h3></div>
            <div class="logo right"><img src="{{ public_path('img/jaascc.png') }}" alt="Logo" style="max-height: 80px;"></div>
        </header>

        <!-- Línea azul superior -->
        <div class="blue-bar"></div>

        <!-- Título -->
        <h2 class="section-title">Historial de pagos</h2>

        <!-- Tabla -->
        <div class="table-container">
            <p>Tabla de historial</p>
            <!-- Aquí puedes insertar una tabla real si lo deseas -->
        </div>

        <!-- Logo inferior -->
        <footer class="footer">
            logo
        </footer>

        <!-- Línea azul inferior -->
        <div class="blue-bar"></div>
    </div>
</body>
</html>
