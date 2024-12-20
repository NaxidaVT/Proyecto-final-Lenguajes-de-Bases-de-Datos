<?php
session_start();
if ($_SESSION['usuario'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

// Mensajes para acciones
$mensaje = "";
$error = "";

// Obtener usuarios disponibles (que no están ya asociados como estudiantes)
$sql_usuarios = "SELECT u.id_usuario, u.nombre, u.email 
                 FROM usuarios u
                 WHERE u.id_usuario NOT IN (SELECT e.id_usuario FROM estudiantes e)";
$result_usuarios = $conn->query($sql_usuarios);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $carrera = $_POST['carrera'];

    if (!$id_usuario || !$carrera) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Insertar nuevo estudiante
        $sql = "INSERT INTO estudiantes (id_usuario, carrera) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $id_usuario, $carrera);

        if ($stmt->execute()) {
            $mensaje = "Estudiante agregado con éxito.";
        } else {
            $error = "Error al agregar el estudiante.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Agregar Estudiante</h1>
        <p class="text-center">Selecciona un usuario existente para agregarlo como estudiante.</p>
        <a href="administrador.php" class="btn btn-secondary mb-4">Volver</a>

        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje); ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($result_usuarios->num_rows > 0): ?>
            <form method="POST" class="mb-4">
                <div class="mb-3">
                    <label for="id_usuario" class="form-label">Usuario</label>
                    <select id="id_usuario" name="id_usuario" class="form-select" required>
                        <option value="">-- Seleccionar Usuario --</option>
                        <?php while ($usuario = $result_usuarios->fetch_assoc()): ?>
                            <option value="<?= $usuario['id_usuario']; ?>">
                                <?= htmlspecialchars($usuario['nombre']); ?> (<?= htmlspecialchars($usuario['email']); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="carrera" class="form-label">Carrera</label>
                    <input type="text" id="carrera" name="carrera" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Estudiante</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info">No hay usuarios disponibles para agregar como estudiantes. Crea nuevos usuarios primero.</div>
        <?php endif; ?>
    </div>
</body>
</html>
