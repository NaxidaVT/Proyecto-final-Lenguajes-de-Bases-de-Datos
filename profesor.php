<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] !== 'Profesor') {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";
$examenes = [];
$registroCambios = [];
$estudiantes = [];
$grupos = [];
$grupoSeleccionado = null;

// Obtener los grupos
try {
    $sql_grupos = "SELECT * FROM TABLAS_PROFESOR.GRUPOS";
    $stmt = $pdo->query($sql_grupos);
    $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al obtener los grupos: " . $e->getMessage();
}

// Manejar filtro por grupo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_grupo'])) {
    $grupoSeleccionado = $_POST['id_grupo'];


    // Obtener exámenes relacionados con el grupo seleccionado
    try {
        if ($grupoSeleccionado === "todos") {
            $sql_examenes = "SELECT E.*, G.NOMBRE_GRUPO FROM TABLAS_PROFESOR.EXAMENES E
                            JOIN TABLAS_PROFESOR.EXAMENES_GRUPO EG ON E.ID_EXAMEN = EG.ID_EXAMEN
                            JOIN TABLAS_PROFESOR.GRUPOS G ON EG.ID_GRUPO = G.ID_GRUPO";
            $stmt = $pdo->query($sql_examenes);
        } else {
            $sql_examenes = "SELECT E.*, G.NOMBRE_GRUPO FROM TABLAS_PROFESOR.EXAMENES E
                            JOIN TABLAS_PROFESOR.EXAMENES_GRUPO EG ON E.ID_EXAMEN = EG.ID_EXAMEN
                            JOIN TABLAS_PROFESOR.GRUPOS G ON EG.ID_GRUPO = G.ID_GRUPO
                            WHERE G.ID_GRUPO = :id_grupo";
            $stmt = $pdo->prepare($sql_examenes);
            $stmt->bindParam(':id_grupo', $grupoSeleccionado, PDO::PARAM_INT);
            $stmt->execute();
        }
        $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje = "Error al obtener los exámenes: " . $e->getMessage();
    }

    // Obtener registros de cambios para los exámenes del grupo seleccionado
    try {
        if ($grupoSeleccionado === "todos") {
            $sql_registro = "SELECT R.* FROM ACCESO_PHP.REGISTRO_CAMBIOS_EXAMENES R";
            $stmt = $pdo->query($sql_registro);
        } else {
            $sql_registro = "SELECT R.* FROM ACCESO_PHP.REGISTRO_CAMBIOS_EXAMENES R
                             JOIN TABLAS_PROFESOR.EXAMENES E ON R.ID_EXAMEN = E.ID_EXAMEN
                             JOIN TABLAS_PROFESOR.EXAMENES_GRUPO EG ON E.ID_EXAMEN = EG.ID_EXAMEN
                             WHERE EG.ID_GRUPO = :id_grupo";
            $stmt = $pdo->prepare($sql_registro);
            $stmt->bindParam(':id_grupo', $grupoSeleccionado, PDO::PARAM_INT);
            $stmt->execute();
        }
        $registroCambios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje = "Error al obtener los registros de cambios: " . $e->getMessage();
    }
}

// Manejar actualizaciones de exámenes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_examen'])) {
    $idExamen = $_POST['id_examen'];
    $nuevoNombre = $_POST['nuevo_nombre'] ?? '';
    $nuevaFecha = $_POST['nueva_fecha'] ?? '';

    try {
        $sql_update = "UPDATE TABLAS_PROFESOR.EXAMENES 
                       SET NOMBRE = :nuevo_nombre, FECHA = TO_DATE(:nueva_fecha, 'YYYY-MM-DD') 
                       WHERE ID_EXAMEN = :id_examen";
        $stmt = $pdo->prepare($sql_update);
        $stmt->bindParam(':nuevo_nombre', $nuevoNombre, PDO::PARAM_STR);
        $stmt->bindParam(':nueva_fecha', $nuevaFecha, PDO::PARAM_STR);
        $stmt->bindParam(':id_examen', $idExamen, PDO::PARAM_INT);
        $stmt->execute();

        $mensaje = "Examen actualizado correctamente.";

        // Recargar exámenes
        if ($accion === 'todos') {
            $stmt = $pdo->query($sql_examenes);
        } elseif ($accion === 'grupo' && $idGrupo) {
            $stmt = $pdo->prepare($sql_examenes);
            $stmt->bindParam(':id_grupo', $idGrupo, PDO::PARAM_INT);
            $stmt->execute();
        }
        $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mensaje = "Error al actualizar el examen: " . $e->getMessage();
    }
}

// Obtener los registros de cambios
try {
    $sql_registro = "SELECT * FROM ACCESO_PHP.REGISTRO_CAMBIOS_EXAMENES";
    $stmt = $pdo->query($sql_registro);
    $registro_cambios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al obtener los registros de cambios: " . $e->getMessage();
}

// Obtener los grupos
try {
    $sql_grupos = "SELECT ID_GRUPO, NOMBRE_GRUPO FROM TABLAS_PROFESOR.GRUPOS";
    $stmt = $pdo->query($sql_grupos);
    $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al obtener los grupos: " . $e->getMessage();
}

// Obtener estudiantes según selección
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    if ($accion === 'todos') {
        // Obtener estudiantes de todos los grupos
        try {
            $sql_estudiantes = "SELECT E.ID_ESTUDIANTE, E.NOMBRE, G.NOMBRE_GRUPO
                                FROM TABLAS_ESTUDIANTE.ESTUDIANTES E
                                JOIN TABLAS_PROFESOR.GRUPOS_ESTUDIANTES GE ON E.ID_ESTUDIANTE = GE.ID_ESTUDIANTE
                                JOIN TABLAS_PROFESOR.GRUPOS G ON GE.ID_GRUPO = G.ID_GRUPO";
            $stmt = $pdo->query($sql_estudiantes);
            $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $mensaje = "Error al obtener estudiantes: " . $e->getMessage();
        }
    } elseif ($accion === 'grupo' && isset($_POST['id_grupo'])) {
        $idGrupo = $_POST['id_grupo'];
        // Obtener estudiantes de un grupo específico
        try {
            $sql_estudiantes = "SELECT E.ID_ESTUDIANTE, E.NOMBRE, G.NOMBRE_GRUPO
                                FROM TABLAS_ESTUDIANTE.ESTUDIANTES E
                                JOIN TABLAS_PROFESOR.GRUPOS_ESTUDIANTES GE ON E.ID_ESTUDIANTE = GE.ID_ESTUDIANTE
                                JOIN TABLAS_PROFESOR.GRUPOS G ON GE.ID_GRUPO = G.ID_GRUPO
                                WHERE G.ID_GRUPO = :id_grupo";
            $stmt = $pdo->prepare($sql_estudiantes);
            $stmt->bindParam(':id_grupo', $idGrupo, PDO::PARAM_INT);
            $stmt->execute();
            $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $mensaje = "Error al obtener estudiantes: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Profesor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Vista Profesor</h1>
        <a href="logout.php" class="btn btn-danger mb-4">Cerrar Sesión</a>

        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <!-- Formulario para seleccionar estudiantes -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Consultar Estudiantes</div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="accion" class="form-label">Seleccionar acción</label>
                        <select id="accion" name="accion" class="form-select" required>
                            <option value="todos">Ver estudiantes de todos los grupos</option>
                            <option value="grupo">Ver estudiantes de un grupo específico</option>
                        </select>
                    </div>
                    <div class="mb-3" id="grupoSelector" style="display: none;">
                        <label for="id_grupo" class="form-label">Seleccionar Grupo</label>
                        <select id="id_grupo" name="id_grupo" class="form-select">
                            <option value="">-- Seleccione un grupo --</option>
                            <?php foreach ($grupos as $grupo): ?>
                                <option value="<?= htmlspecialchars($grupo['ID_GRUPO']); ?>">
                                    <?= htmlspecialchars($grupo['NOMBRE_GRUPO']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Consultar</button>
                </form>
            </div>
        </div>

        <!-- Mostrar estudiantes si están disponibles -->
        <?php if (!empty($estudiantes)): ?>
            <h3>Estudiantes</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Estudiante</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes as $estudiante): ?>
                        <tr>
                            <td><?= htmlspecialchars($estudiante['ID_ESTUDIANTE']); ?></td>
                            <td><?= htmlspecialchars($estudiante['NOMBRE']); ?></td>
                            <td><?= htmlspecialchars($estudiante['NOMBRE_GRUPO']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Tabla de exámenes existente aquí -->
        <h3>Exámenes</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Examen</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($examenes as $examen): ?>
                    <tr>
                        <td><?= htmlspecialchars($examen['ID_EXAMEN']); ?></td>
                        <td><?= htmlspecialchars($examen['NOMBRE']); ?></td>
                        <td><?= htmlspecialchars($examen['FECHA']); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id_examen"
                                    value="<?= htmlspecialchars($examen['ID_EXAMEN']); ?>">
                                <div class="mb-2">
                                    <input type="text" name="nuevo_nombre" placeholder="Nuevo Nombre"
                                        class="form-control mb-2" required>
                                    <input type="date" name="nueva_fecha" class="form-control mb-2" required>
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Mostrar registros de cambios -->
        <h3>Registros de Cambios</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Registro</th>
                    <th>ID Examen</th>
                    <th>Campo Modificado</th>
                    <th>Valor Anterior</th>
                    <th>Valor Nuevo</th>
                    <th>Fecha Modificación</th>
                    <th>Usuario Modificación</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registroCambios as $registro): ?>
                    <tr>
                        <td><?= htmlspecialchars($registro['ID_REGISTRO']); ?></td>
                        <td><?= htmlspecialchars($registro['ID_EXAMEN']); ?></td>
                        <td><?= htmlspecialchars($registro['CAMPO_MODIFICADO']); ?></td>
                        <td><?= htmlspecialchars($registro['VALOR_ANTERIOR']); ?></td>
                        <td><?= htmlspecialchars($registro['VALOR_NUEVO']); ?></td>
                        <td><?= htmlspecialchars($registro['FECHA_MODIFICACION']); ?></td>
                        <td><?= htmlspecialchars($registro['USUARIO_MODIFICACION']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Mostrar/ocultar selector de grupo según la acción seleccionada
        document.getElementById('accion').addEventListener('change', function () {
            const grupoSelector = document.getElementById('grupoSelector');
            if (this.value === 'grupo') {
                grupoSelector.style.display = 'block';
            } else {
                grupoSelector.style.display = 'none';
            }
        });
    </script>
</body>