<!DOCTYPE html>
<html>
<head>
    <title>Registrar Trabajador</title>
</head>
<body>

<h2>Registro de Trabajador</h2>

<form action="/OEGPP-Web/controllers/trabajadoresController.php" method="POST">
    DNI: <input type="text" name="dni"><br>
    Nombres: <input type="text" name="nombres"><br>
    Apellidos: <input type="text" name="apellidos"><br>
    Correo: <input type="email" name="correo"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>