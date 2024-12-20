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

// Verificar si se recibió el ID del estudiante
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_estudiante = $_GET['id'];

    // Verificar si el estudiante existe
    $sql_verificar = "SELECT id_estudiante FROM estudiantes WHERE id_estudiante = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $id_estudiante);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows > 0) {
        // Eliminar el estudiante
        $sql_eliminar = "DELETE FROM estudiantes WHERE id_estudiante = ?";
        $stmt_eliminar = $conn->prepare($sql_eliminar);
        $stmt_eliminar->bind_param("i", $id_estudiante);

        if ($stmt_eliminar->execute()) {
            $mensaje = "Estudiante eliminado con éxito.";
        } else {
            $error = "Error al eliminar el estudiante. Inténtalo de nuevo.";
        }

        $stmt_eliminar->close();
    } else {
        $error = "El estudiante no existe.";
    }

    $stmt_verificar->close();
} else {
    $error = "ID de estudiante no proporcionado.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Eliminar Estudiante</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php endif; ?>
    </div>
</body>
</html>
