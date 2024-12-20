<?php
try {
    // Configuración de conexión
    $username = 'acceso_php';
    $password = 'php_password';
    $dsn = 'oci:dbname=localhost/orcl;charset=AL32UTF8';

    // Crear conexión PDO
    $pdo = new PDO($dsn, $username, $password);

    // Configurar excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Ejemplo de consulta: Mostrar los datos de la tabla `AULA`
    //$query = 'SELECT ID_AULA, NOMBRE_AULA, CAPACIDAD FROM tablas_administrativo.AULA';
    //$stmt = $pdo->query($query);

    // Mostrar ejemplo
    //echo "<table border='1'>";
    //echo "<tr><th>ID_AULA</th><th>NOMBRE_AULA</th><th>CAPACIDAD</th></tr>";
    //while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //    echo "<tr>";
    //    echo "<td>" . htmlspecialchars($row['ID_AULA']) . "</td>";
    //    echo "<td>" . htmlspecialchars($row['NOMBRE_AULA']) . "</td>";
    //    echo "<td>" . htmlspecialchars($row['CAPACIDAD']) . "</td>";
    //    echo "</tr>";
    //}
    //echo "</table>";

} catch (PDOException $e) {
    echo 'Error en la conexión: ' . $e->getMessage();
}
?>