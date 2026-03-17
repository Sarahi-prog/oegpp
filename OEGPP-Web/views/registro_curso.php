<!DOCTYPE html>
<html>
<head>
    <title>Registrar Curso</title>
</head>
<body>

<h2>Registro de Curso</h2>

<form action="/OEGPP-Web/controllers/cursosController.php" method="POST">
    Código: <input type="text" name="codigo"><br>
    Nombre: <input type="text" name="nombre"><br>
    
    Tipo:
    <select name="tipo">
        <option value="certificado">Certificado</option>
        <option value="diplomado">Diplomado</option>
    </select><br>

    Horas Totales: <input type="number" name="horas"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>