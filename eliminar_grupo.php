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

    // Eliminar grupo
    $sql = "DELETE FROM grupos WHERE id_grupo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_grupo);

    if ($stmt->execute()) {
        $mensaje = "Grupo eliminado con éxito.";
    } else {
        $error = "Error al eliminar el grupo.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Grupo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Eliminar Grupo</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php else: ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php endif; ?>
    </div>
</body>
</html>
