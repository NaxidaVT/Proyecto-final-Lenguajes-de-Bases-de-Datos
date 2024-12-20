<?php
include 'conexion.php';

if (isset($_POST['submit'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $sql = "INSERT INTO usuarios (nombre, email) VALUES ('$nombre', '$email')";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error al agregar el usuario: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Usuario</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Agregar Usuario</h1>
    <form method="post" action="">
        Nombre:<br>
        <input type="text" name="nombre" required><br><br>
        Email:<br>
        <input type="email" name="email" required><br><br>
        <input type="submit" name="submit" value="Agregar">
    </form>
    <br>
    <a href="index.php">Regresar a la lista</a>
</body>
</html>
