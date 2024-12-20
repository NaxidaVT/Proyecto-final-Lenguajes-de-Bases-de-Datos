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

// Verificar si se proporcionó el ID del grupo
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_grupo = $_GET['id'];

    // Obtener datos actuales del grupo
    $sql = "SELECT * FROM grupos WHERE id_grupo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $result = $stmt->get_result();
    $grupo = $result->fetch_assoc();
    $stmt->close();

    if (!$grupo) {
        $error = "Grupo no encontrado.";
    }
}

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_grupo'])) {
    $id_grupo = $_POST['id_grupo'];
    $nombre_grupo = $_POST['nombre_grupo'];

    $sql_update = "UPDATE grupos SET nombre_grupo = ? WHERE id_grupo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $nombre_grupo, $id_grupo);

    if ($stmt_update->execute()) {
        $mensaje = "Grupo actualizado con éxito.";
    } else {
        $error = "Error al actualizar el grupo.";
    }

    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Grupo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Grupo</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="id_grupo" value="<?= htmlspecialchars($grupo['id_grupo']); ?>">
                <div class="mb-3">
                    <label for="nombre_grupo" class="form-label">Nombre del Grupo</label>
                    <input type="text" id="nombre_grupo" name="nombre_grupo" class="form-control" value="<?= htmlspecialchars($grupo['nombre_grupo']); ?>" required>
                </div>
                <button type="submit" name="editar_grupo" class="btn btn-primary">Actualizar Grupo</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
