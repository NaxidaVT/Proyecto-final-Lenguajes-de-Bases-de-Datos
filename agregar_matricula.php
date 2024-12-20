<?php
session_start();

// Verificar si el usuario tiene permisos de administrador
if ($_SESSION['usuario'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

$mensaje = "";
$error = "";

// Obtener estudiantes existentes
$sql_estudiantes = "SELECT e.id_estudiante, u.nombre AS estudiante_nombre 
                    FROM estudiantes e
                    JOIN usuarios u ON e.id_usuario = u.id_usuario";
$result_estudiantes = $conn->query($sql_estudiantes);

// Obtener grupos existentes
$sql_grupos = "SELECT id_grupo, nombre_grupo FROM grupos";
$result_grupos = $conn->query($sql_grupos);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_POST['id_estudiante'];
    $id_grupo = $_POST['id_grupo'];
    $semestre = $_POST['semestre'];

    $sql_insert = "INSERT INTO matriculas (id_estudiante, id_grupo, semestre) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iis", $id_estudiante, $id_grupo, $semestre);

    if ($stmt->execute()) {
        $mensaje = "Matrícula agregada con éxito.";
    } else {
        $error = "Error al agregar la matrícula.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Matrícula</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Agregar Matrícula</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="id_estudiante" class="form-label">Estudiante</label>
                    <select id="id_estudiante" name="id_estudiante" class="form-select" required>
                        <option value="">-- Seleccione un estudiante --</option>
                        <?php while ($row = $result_estudiantes->fetch_assoc()): ?>
                            <option value="<?= $row['id_estudiante']; ?>"><?= htmlspecialchars($row['estudiante_nombre']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_grupo" class="form-label">Grupo</label>
                    <select id="id_grupo" name="id_grupo" class="form-select" required>
                        <option value="">-- Seleccione un grupo --</option>
                        <?php while ($row = $result_grupos->fetch_assoc()): ?>
                            <option value="<?= $row['id_grupo']; ?>"><?= htmlspecialchars($row['nombre_grupo']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre</label>
                    <input type="text" id="semestre" name="semestre" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Matrícula</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
