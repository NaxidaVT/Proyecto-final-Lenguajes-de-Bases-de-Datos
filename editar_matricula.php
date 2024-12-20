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

    // Obtener datos actuales de la matrícula
    $sql = "SELECT * FROM matriculas WHERE id_matricula = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_matricula);
    $stmt->execute();
    $result = $stmt->get_result();
    $matricula = $result->fetch_assoc();
    $stmt->close();

    if (!$matricula) {
        $error = "Matrícula no encontrada.";
    }
}

// Procesar formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_matricula'])) {
    $id_matricula = $_POST['id_matricula'];
    $semestre = $_POST['semestre'];

    $sql_update = "UPDATE matriculas SET semestre = ? WHERE id_matricula = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $semestre, $id_matricula);

    if ($stmt_update->execute()) {
        $mensaje = "Matrícula actualizada con éxito.";
    } else {
        $error = "Error al actualizar la matrícula.";
    }

    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Matrícula</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Matrícula</h1>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <a href="administrador.php" class="btn btn-primary mt-3">Volver al Panel de Administración</a>
        <?php else: ?>
            <form method="POST">
                <input type="hidden" name="id_matricula" value="<?= htmlspecialchars($matricula['id_matricula']); ?>">
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre</label>
                    <input type="text" id="semestre" name="semestre" class="form-control" value="<?= htmlspecialchars($matricula['semestre']); ?>" required>
                </div>
                <button type="submit" name="editar_matricula" class="btn btn-primary">Actualizar Matrícula</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
