<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universidad XYZ</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            background: url('https://via.placeholder.com/1920x800') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero p {
            font-size: 1.5rem;
        }
        .btn-login {
            background-color: #007bff;
            color: white;
            border-radius: 20px;
        }
        .btn-login:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header class="bg-primary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="m-0">Universidad Fidélitas</h1>
            <a href="login.php" class="btn btn-login px-4 py-2">Iniciar Sesión</a>
        </div>
    </header>

    <!-- Sección hero -->
    <section class="hero text-center">
        <div class="container">
            <h1>Bienvenidos a la Universidad Fidélitas</h1>
            <p>Innovación, excelencia y educación de calidad para todos.</p>
            <a href="login.php" class="btn btn-login btn-lg mt-3">Iniciar Sesión</a>
        </div>
    </section>

    <!-- Sección de información -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">¿Quiénes somos?</h2>
            <p class="text-center">
                La Universidad XYZ es líder en educación superior, ofreciendo programas académicos de alta calidad 
                que preparan a nuestros estudiantes para enfrentar los desafíos del mundo actual. Contamos con una 
                comunidad vibrante de estudiantes, profesores y profesionales comprometidos con el aprendizaje y la 
                investigación.
            </p>
        </div>
    </section>

    <!-- Sección de programas -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Nuestros Programas Académicos</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <h4>Ingeniería en Sistemas</h4>
                    <p>Desarrolla habilidades en programación, bases de datos y redes para convertirte en un profesional de TI.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h4>Administración de Empresas</h4>
                    <p>Aprende a liderar y gestionar empresas en un mundo globalizado y dinámico.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h4>Educación</h4>
                    <p>Forma parte del cambio educativo con programas enfocados en pedagogía y desarrollo estudiantil.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de contacto -->
    <section class="py-5">
        <div class="container text-center">
            <h2>Contáctanos</h2>
            <p>Estamos aquí para ayudarte. Escríbenos o llámanos para obtener más información.</p>
            <p>Email: <a href="mailto:info@universidadfidelitas.edu">info@universidadfidelitas.edu</a></p>
            <p>Teléfono: +506 1234 5678</p>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            <p>© 2024 Universidad Fidelitas. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
