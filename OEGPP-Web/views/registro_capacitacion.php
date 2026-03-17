<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Capacitación</title>
</head>
<body>

<h2>Registro de Capacitación</h2>

<form action="/OEGPP-Web/controllers/registrosController.php" method="POST">
    
    ID Trabajador: <input type="number" name="trabajador"><br>
    ID Curso: <input type="number" name="curso"><br>
    ID Libro: <input type="number" name="libro"><br>

    Código Documento: <input type="text" name="codigo"><br>
    Horas Realizadas: <input type="number" name="horas"><br>

    Fecha Inicio: <input type="date" name="inicio"><br>
    Fecha Fin: <input type="date" name="fin"><br>
    Fecha Emisión: <input type="date" name="emision"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>