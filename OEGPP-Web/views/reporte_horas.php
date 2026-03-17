<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Horas</title>
</head>
<body>

<h2>Reporte de Horas</h2>

<form action="/OEGPP-Web/controllers/registrosController.php" method="POST">
    <button type="submit" name="reporte">Ver Reporte</button>
</form>

</body>
</html>