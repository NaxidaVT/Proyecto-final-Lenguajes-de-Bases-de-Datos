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

// Verificar si se proporcionó el ID de matrícula
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_matricula = $_GET['id'];

    // Eliminar matrícula
    $sql = "DELETE FROM matriculas WHERE id_matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_matricula);

    if ($stmt->execute()) {
        $mensaje = "Matrícula eliminada con éxito.";
    } else {
        $error = "Error al eliminar la matrícula.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Matrícula</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Eliminar Matrícula</h1>

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
