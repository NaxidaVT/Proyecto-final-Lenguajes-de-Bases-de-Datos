<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Redirigir según el rol del usuario
switch ($usuario) {
    case 'Estudiante':
        header("Location: estudiante.php");
        break;
    case 'Admin':
        header("Location: administrador.php");
        break;
    case 'Profesor':
        header("Location: profesor.php");
        break;
    default:
        session_destroy();
        header("Location: login.php");
        break;
}
exit();
?>
