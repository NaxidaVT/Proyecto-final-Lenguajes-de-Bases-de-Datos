<?php
include 'conexion.php';

$id_usuario = $_GET['id'];

if (isset($_POST['submit'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "UPDATE usuarios SET nombre='$nombre', email='$email' WHERE id_usuario=$id_usuario";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM usuarios WHERE id_usuario=$id_usuario";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Editar Usuario</h1>
    <form method="post" action="">
        Nombre:<br>
        <input type="text" name="nombre" value="<?= $row['nombre']; ?>" required><br><br>
        Email:<br>
        <input type="email" name="email" value="<?= $row['email']; ?>" required><br><br>
        <input type="submit" name="submit" value="Actualizar">
    </form>
    <br>
    <a href="index.php">Regresar a la lista</a>
</body>
</html>
