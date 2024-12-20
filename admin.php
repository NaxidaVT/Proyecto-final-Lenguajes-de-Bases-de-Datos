<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";
$grupos = [];
$estudiantes = [];
$anuncios = [];

// Obtener la lista de grupos
try {
    $sql_grupos = "SELECT G.ID_GRUPO, G.NOMBRE_GRUPO, A.CAPACIDAD, COUNT(GE.ID_ESTUDIANTE) AS ESTUDIANTES_INSCRITOS
                   FROM TABLAS_PROFESOR.GRUPOS G
                   LEFT JOIN TABLAS_PROFESOR.GRUPOS_ESTUDIANTES GE ON G.ID_GRUPO = GE.ID_GRUPO
                   LEFT JOIN TABLAS_ADMINISTRATIVO.AULA A ON G.ID_GRUPO = A.ID_AULA
                   GROUP BY G.ID_GRUPO, G.NOMBRE_GRUPO, A.CAPACIDAD
                   ORDER BY G.NOMBRE_GRUPO ASC";
    $stmt = $pdo->query($sql_grupos);
    $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al obtener los grupos: " . $e->getMessage();
}

// Obtener la lista de estudiantes
try {
    $sql_estudiantes = "SELECT ID_ESTUDIANTE, NOMBRE, EMAIL, CARRERA FROM TABLAS_ESTUDIANTE.ESTUDIANTES ORDER BY NOMBRE ASC";
    $stmt = $pdo->query($sql_estudiantes);
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$estudiantes) {
        $mensaje = "No hay estudiantes disponibles.";
    }
} catch (PDOException $e) {
    $mensaje = "Error al obtener los estudiantes: " . $e->getMessage();
}

// Crear anuncio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_anuncio'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $idGrupo = $_POST['id_grupo'] ?? null;

    try {
        $sql_anuncio = "INSERT INTO TABLAS_ADMINISTRATIVO.ANUNCIOS (TITULO, DESCRIPCION, ID_AULA, FECHA) 
                        VALUES (:titulo, :descripcion, :id_grupo, SYSDATE)";
        $stmt = $pdo->prepare($sql_anuncio);
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':id_grupo', $idGrupo, PDO::PARAM_INT);
        $stmt->execute();
        $mensaje = "Anuncio creado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al crear el anuncio: " . $e->getMessage();
    }
}

// Crear estudiante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_estudiante'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $carrera = $_POST['carrera'];
    $semestre = $_POST['semestre'] ?? '2024-1'; // Semestre por defecto si no se proporciona

    try {
        $pdo->beginTransaction(); // Iniciar transacción

        // Crear el estudiante
        $sql_crear_estudiante = "INSERT INTO TABLAS_ESTUDIANTE.ESTUDIANTES (NOMBRE, EMAIL, CARRERA) 
                                 VALUES (:nombre, :email, :carrera) RETURNING ID_ESTUDIANTE INTO :id_estudiante";
        $stmt = $pdo->prepare($sql_crear_estudiante);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT);
        $stmt->execute();

        // Insertar en la tabla MATRÍCULAS
        $sql_matricula = "INSERT INTO TABLAS_ESTUDIANTE.MATRICULAS (ID_ESTUDIANTE, SEMESTRE) 
                          VALUES (:id_estudiante, :semestre)";
        $stmt = $pdo->prepare($sql_matricula);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->bindParam(':semestre', $semestre, PDO::PARAM_STR);
        $stmt->execute();

        $pdo->commit(); // Confirmar transacción
        $mensaje = "Estudiante creado correctamente con ID: $idEstudiante.";
    } catch (PDOException $e) {
        $pdo->rollBack(); // Revertir cambios si hay un error
        $mensaje = "Error al crear el estudiante: " . $e->getMessage();
    }
}

// Eliminar estudiante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_estudiante'])) {
    $idEstudiante = $_POST['id_estudiante'];

    try {
        // Eliminar al estudiante de las tablas relacionadas primero
        $sql_eliminar_matricula = "DELETE FROM TABLAS_PROFESOR.GRUPOS_ESTUDIANTES WHERE ID_ESTUDIANTE = :id_estudiante";
        $stmt = $pdo->prepare($sql_eliminar_matricula);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();

        $sql_eliminar_matricula = "DELETE FROM TABLAS_ESTUDIANTE.MATRICULAS WHERE ID_ESTUDIANTE = :id_estudiante";
        $stmt = $pdo->prepare($sql_eliminar_matricula);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();

        // Luego eliminar al estudiante de la tabla principal
        $sql_eliminar_estudiante = "DELETE FROM TABLAS_ESTUDIANTE.ESTUDIANTES WHERE ID_ESTUDIANTE = :id_estudiante";
        $stmt = $pdo->prepare($sql_eliminar_estudiante);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();

        $mensaje = "Estudiante eliminado correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar al estudiante: " . $e->getMessage();
    }
}

// Modificar estudiante
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar_estudiante'])) {
    $idEstudiante = $_POST['id_estudiante'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $carrera = $_POST['carrera'];

    try {
        $sql_modificar_estudiante = "UPDATE TABLAS_ESTUDIANTE.ESTUDIANTES 
                                     SET NOMBRE = :nombre, EMAIL = :email, CARRERA = :carrera 
                                     WHERE ID_ESTUDIANTE = :id_estudiante";
        $stmt = $pdo->prepare($sql_modificar_estudiante);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':carrera', $carrera, PDO::PARAM_STR);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $mensaje = "Información del estudiante actualizada correctamente.";
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar la información del estudiante: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Vista Administrador</h1>
        <a href="logout.php" class="btn btn-danger mb-4">Cerrar Sesión</a>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <!-- Formulario para crear estudiante -->
        <div class="card mb-4">
            <div class="card-header">Crear Estudiante</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="crear_estudiante" value="1">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="carrera" class="form-label">Carrera</label>
                        <input type="text" name="carrera" id="carrera" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="semestre" class="form-label">Semestre</label>
                        <input type="text" name="semestre" id="semestre" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Crear Estudiante</button>
                </form>
            </div>
        </div>

        <!-- Formulario para eliminar estudiante -->
        <div class="card mb-4">
            <div class="card-header">Eliminar Estudiante</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="eliminar_estudiante" value="1">
                    <div class="mb-3">
                        <label for="id_estudiante" class="form-label">Estudiante</label>
                        <select name="id_estudiante" id="id_estudiante" class="form-select" required>
                            <option value="">Seleccione un estudiante</option>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <option value="<?= htmlspecialchars($estudiante['ID_ESTUDIANTE']); ?>">
                                    <?= htmlspecialchars($estudiante['NOMBRE']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger">Eliminar Estudiante</button>
                </form>
            </div>
        </div>

        <!-- Formulario para modificar estudiante -->
        <div class="card mb-4">
            <div class="card-header">Modificar Estudiante</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="modificar_estudiante" value="1">
                    <div class="mb-3">
                        <label for="id_estudiante" class="form-label">Seleccionar Estudiante</label>
                        <select name="id_estudiante" class="form-select" required>
                            <option value="">-- Seleccione un estudiante --</option>
                            <?php foreach ($estudiantes as $estudiante): ?>
                                <option value="<?= htmlspecialchars($estudiante['ID_ESTUDIANTE']); ?>">
                                    <?= htmlspecialchars($estudiante['NOMBRE']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="carrera" class="form-label">Carrera</label>
                        <input type="text" name="carrera" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Modificar Estudiante</button>
                </form>
            </div>
        </div>

    </div>
</body>

</html>