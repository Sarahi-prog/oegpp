<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta por DNI</title>
</head>
<body>

<h2>Buscar Trabajador por DNI</h2>

<form action="/OEGPP-Web/controllers/registrosController.php" method="POST">
    DNI: <input type="text" name="dni">
    <button type="submit" name="buscar">Buscar</button>
</form>

</body>
</html>