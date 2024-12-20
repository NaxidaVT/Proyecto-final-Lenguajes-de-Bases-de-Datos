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

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_grupo = $_POST['nombre_grupo'];

    $sql_insert = "INSERT INTO grupos (nombre_grupo) VALUES (?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("s", $nombre_grupo);

    if ($stmt->execute()) {
        $mensaje = "Grupo agregado con éxito.";
    } else {
        $error = "Error al agregar el grupo.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Grupo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Agregar Grupo</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php else: ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="nombre_grupo" class="form-label">Nombre del Grupo</label>
                    <input type="text" id="nombre_grupo" name="nombre_grupo" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Grupo</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
