<?php
include 'conexion.php';

$id_usuario = $_GET['id'];

$sql = "DELETE FROM usuarios WHERE id_usuario=$id_usuario";

if ($conn->query($sql) === TRUE) {
    header('Location: index.php');
    exit;
} else {
    echo "Error al eliminar el usuario: " . $conn->error;
}
?>
