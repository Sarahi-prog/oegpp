


<!DOCTYPE html>
<html>
<head>
    <title>Registrar Módulo</title>
</head>
<body>

<h2>Registro de Módulo</h2>

<form action="/OEGPP-Web/controllers/modulosController.php" method="POST">
    ID Curso: <input type="number" name="curso_id"><br>
    Nombre Módulo: <input type="text" name="nombre"><br>
    Horas: <input type="number" name="horas"><br>
    Fecha Inicio: <input type="date" name="inicio"><br>
    Fecha Fin: <input type="date" name="fin"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>