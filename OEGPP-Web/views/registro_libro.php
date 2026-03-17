<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Libro</title>
</head>
<body>

<h2>Registro de Libro</h2>

<form action="/OEGPP-Web/controllers/librosController.php" method="POST">
    Tipo:
    <select name="tipo">
        <option value="cursos">Cursos</option>
        <option value="diplomados">Diplomados</option>
    </select><br>

    Número Libro: <input type="number" name="numero"><br>
    Año: <input type="number" name="anio"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>