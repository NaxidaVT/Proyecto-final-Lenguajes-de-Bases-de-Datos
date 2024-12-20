<?php
session_start();
if ($_SESSION['usuario'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

// Mensajes de error o éxito
$mensaje = "";
$error = "";

// Validar si se recibió el ID del estudiante a editar
if (!isset($_GET['id'])) {
    header("Location: administrador.php");
    exit();
}

$id_estudiante = $_GET['id'];

// Consultar los datos actuales del estudiante
$sql = "SELECT e.id_estudiante, e.carrera, u.id_usuario, u.nombre AS usuario_nombre, u.email 
        FROM estudiantes e
        JOIN usuarios u ON e.id_usuario = u.id_usuario
        WHERE e.id_estudiante = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $error = "El estudiante no existe.";
    $stmt->close();
} else {
    $estudiante = $result->fetch_assoc();
    $stmt->close();

    // Procesar la actualización del estudiante
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario_nombre = $_POST['usuario_nombre'];
        $email = $_POST['email'];
        $carrera = $_POST['carrera'];

        // Actualizar datos del usuario
        $sql_usuario = "UPDATE usuarios SET nombre = ?, email = ? WHERE id_usuario = ?";
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("ssi", $usuario_nombre, $email, $estudiante['id_usuario']);
        if ($stmt_usuario->execute()) {
            // Actualizar datos del estudiante
            $sql_estudiante = "UPDATE estudiantes SET carrera = ? WHERE id_estudiante = ?";
            $stmt_estudiante = $conn->prepare($sql_estudiante);
            $stmt_estudiante->bind_param("si", $carrera, $id_estudiante);
            if ($stmt_estudiante->execute()) {
                $mensaje = "Estudiante actualizado con éxito.";
            } else {
                $error = "Error al actualizar los datos del estudiante.";
            }
            $stmt_estudiante->close();
        } else {
            $error = "Error al actualizar los datos del usuario.";
        }
        $stmt_usuario->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Estudiante</h1>
        <p class="text-center">Modifica la información del estudiante.</p>
        <a href="administrador.php" class="btn btn-secondary mb-4">Volver</a>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!isset($error) || !$error): ?>
            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="usuario_nombre" class="form-label">Nombre del Usuario</label>
                    <input type="text" id="usuario_nombre" name="usuario_nombre" class="form-control" value="<?= htmlspecialchars($estudiante['usuario_nombre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($estudiante['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="carrera" class="form-label">Carrera</label>
                    <input type="text" id="carrera" name="carrera" class="form-control" value="<?= htmlspecialchars($estudiante['carrera']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
