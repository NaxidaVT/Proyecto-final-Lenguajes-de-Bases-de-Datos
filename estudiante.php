<?php
session_start();

// Verificar si el usuario tiene una sesión válida como estudiante
if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'Estudiante') {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";
$carrera = "";
$semestre = "";
$materias = [];
$examenes = [];
$anuncios = [];
$notificaciones = [];
$estudiantes = [];

// Obtener la lista de estudiantes ordenada alfabéticamente
try {
    $sql_estudiantes = "SELECT ID_ESTUDIANTE, NOMBRE FROM TABLAS_ESTUDIANTE.ESTUDIANTES ORDER BY NOMBRE ASC";
    $stmt = $pdo->query($sql_estudiantes);
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$estudiantes) {
        $mensaje = "No hay estudiantes disponibles.";
    }
} catch (PDOException $e) {
    $mensaje = "Error al obtener los estudiantes: " . $e->getMessage();
}

// Si el formulario de selección de estudiante es enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_estudiante'])) {
    $idEstudiante = $_POST['id_estudiante'];

    try {
        // Obtener la información básica del estudiante
        $sql = "SELECT NVL(E.CARRERA, 'No asignada') AS CARRERA, NVL(M.SEMESTRE, 'No asignado') AS SEMESTRE 
                FROM TABLAS_ESTUDIANTE.ESTUDIANTES E
                LEFT JOIN TABLAS_ESTUDIANTE.MATRICULAS M 
                ON E.ID_ESTUDIANTE = M.ID_ESTUDIANTE
                WHERE E.ID_ESTUDIANTE = :id_estudiante";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $carrera = $data['CARRERA'];
            $semestre = $data['SEMESTRE'];
        } else {
            $mensaje = "No se encontró información para el estudiante seleccionado.";
        }

        // Obtener aulas y grupos asignados al estudiante
        $sql_materias = "SELECT NVL(G.NOMBRE_GRUPO, 'No asignado') AS GRUPO, NVL(A.NOMBRE_AULA, 'No asignada') AS AULA
                         FROM TABLAS_PROFESOR.GRUPOS_ESTUDIANTES GE
                         LEFT JOIN TABLAS_PROFESOR.GRUPOS G ON GE.ID_GRUPO = G.ID_GRUPO
                         LEFT JOIN TABLAS_ADMINISTRATIVO.AULA A ON G.ID_GRUPO = A.ID_AULA
                         WHERE GE.ID_ESTUDIANTE = :id_estudiante
                         ORDER BY G.NOMBRE_GRUPO ASC";
        $stmt = $pdo->prepare($sql_materias);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener exámenes asignados al estudiante
        $sql_examenes = "SELECT NVL(EX.NOMBRE, 'No asignado') AS EXAMEN, NVL(TO_CHAR(EX.FECHA, 'DD/MM/YYYY'), 'No asignada') AS FECHA
                          FROM TABLAS_PROFESOR.EXAMENES EX
                          JOIN TABLAS_PROFESOR.EXAMENES_GRUPO EG ON EX.ID_EXAMEN = EG.ID_EXAMEN
                          WHERE EG.ID_GRUPO IN (
                              SELECT ID_GRUPO 
                              FROM TABLAS_PROFESOR.GRUPOS_ESTUDIANTES
                              WHERE ID_ESTUDIANTE = :id_estudiante
                          )
                          ORDER BY EX.FECHA ASC";
        $stmt = $pdo->prepare($sql_examenes);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener anuncios relacionados con los grupos del estudiante
        $sql_anuncios = "SELECT NVL(A.TITULO, 'Sin título') AS TITULO, NVL(A.DESCRIPCION, 'Sin descripción') AS DESCRIPCION, NVL(TO_CHAR(A.FECHA, 'DD/MM/YYYY'), 'No asignada') AS FECHA
                          FROM TABLAS_ADMINISTRATIVO.ANUNCIOS A
                          JOIN TABLAS_PROFESOR.GRUPOS_ESTUDIANTES GE ON A.ID_AULA = GE.ID_GRUPO
                          WHERE GE.ID_ESTUDIANTE = :id_estudiante
                          ORDER BY A.FECHA DESC";
        $stmt = $pdo->prepare($sql_anuncios);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener notificaciones asociadas al estudiante
        $sql_notificaciones = "SELECT NVL(TITULO, 'Sin título') AS TITULO, NVL(MENSAJE, 'Sin mensaje') AS MENSAJE, NVL(TO_CHAR(FECHA_CREACION, 'DD/MM/YYYY'), 'No asignada') AS FECHA_CREACION 
                                FROM TABLAS_ESTUDIANTE.NOTIFICACIONES
                                WHERE ID_ESTUDIANTE = :id_estudiante
                                ORDER BY FECHA_CREACION DESC";
        $stmt = $pdo->prepare($sql_notificaciones);
        $stmt->bindParam(':id_estudiante', $idEstudiante, PDO::PARAM_INT);
        $stmt->execute();
        $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $mensaje = "Error al obtener la información del estudiante: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Vista Estudiante</h1>
        <a href="logout.php" class="btn btn-danger mb-4">Cerrar Sesión</a>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <!-- Seleccionar Estudiante -->
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="id_estudiante" class="form-label">Seleccionar Estudiante</label>
                <select id="id_estudiante" name="id_estudiante" class="form-select" required>
                    <option value="">-- Seleccione un estudiante --</option>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <option value="<?= htmlspecialchars($estudiante['ID_ESTUDIANTE']); ?>">
                            <?= htmlspecialchars($estudiante['NOMBRE']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ver Información</button>
        </form>

        <!-- Mostrar información del estudiante seleccionado -->
        <?php if (!empty($carrera) && !empty($semestre)): ?>
            <h3>Información del Estudiante</h3>
            <p><strong>Carrera:</strong> <?= htmlspecialchars($carrera); ?></p>
            <p><strong>Semestre:</strong> <?= htmlspecialchars($semestre); ?></p>

            <!-- Mostrar Materias y Grupos -->
            <h3>Grupos y Aulas</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Grupo</th>
                        <th>Aula</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materias as $materia): ?>
                        <tr>
                            <td><?= htmlspecialchars($materia['GRUPO']); ?></td>
                            <td><?= htmlspecialchars($materia['AULA']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mostrar Exámenes -->
            <h3>Exámenes</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Examen</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($examenes as $examen): ?>
                        <tr>
                            <td><?= htmlspecialchars($examen['EXAMEN']); ?></td>
                            <td><?= htmlspecialchars($examen['FECHA']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mostrar Anuncios -->
            <h3>Anuncios</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anuncios as $anuncio): ?>
                        <tr>
                            <td><?= htmlspecialchars($anuncio['TITULO']); ?></td>
                            <td><?= htmlspecialchars($anuncio['DESCRIPCION']); ?></td>
                            <td><?= htmlspecialchars($anuncio['FECHA']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mostrar Notificaciones -->
            <h3>Notificaciones</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Mensaje</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notificaciones as $notificacion): ?>
                        <tr>
                            <td><?= htmlspecialchars($notificacion['TITULO']); ?></td>
                            <td><?= htmlspecialchars($notificacion['MENSAJE']); ?></td>
                            <td><?= htmlspecialchars($notificacion['FECHA_CREACION']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>