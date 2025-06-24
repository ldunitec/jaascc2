<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junta Administradora de Agua</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Encabezado -->
    <header class="bg-primary text-white text-center py-4">
        <h1>Junta Administradora de Agua</h1>
        <p>Comprometidos con el servicio de agua potable para nuestra comunidad</p>
    </header>

    <!-- Botón de Inicio de Sesión -->
    <div class="text-end p-3">
        <a href="{{ route('login') }}" class="btn btn-outline-primary">Iniciar Sesión</a>
    </div>

    <!-- Sección de Noticias -->
    <div class="container mt-4">
        <h2 class="mb-4">Noticias Recientes</h2>
        <div class="row">

            <!-- Noticia 1 -->
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Noticia 1">
                    <div class="card-body">
                        <h5 class="card-title">Mantenimiento programado</h5>
                        <p class="card-text">Este sábado se realizará mantenimiento en la red principal. El servicio se verá afectado de 8:00 a.m. a 12:00 m.</p>
                    </div>
                </div>
            </div>

            <!-- Noticia 2 -->
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Noticia 2">
                    <div class="card-body">
                        <h5 class="card-title">Nuevo pozo habilitado</h5>
                        <p class="card-text">La comunidad ya cuenta con un nuevo pozo para garantizar el suministro de agua en época de verano.</p>
                    </div>
                </div>
            </div>

            <!-- Noticia 3 -->
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Noticia 3">
                    <div class="card-body">
                        <h5 class="card-title">Capacitación a usuarios</h5>
                        <p class="card-text">Se impartirá una charla sobre el uso responsable del agua el próximo martes en el salón comunal.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Pie de página -->
    <footer class="bg-light text-center py-4 mt-5">
        <p>&copy; 2025 Junta Administradora de Agua. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
