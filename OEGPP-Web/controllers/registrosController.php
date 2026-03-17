<?php
require_once("../models/registrosModel.php");

$trabajador = $_POST['trabajador'];
$curso = $_POST['curso'];
$libro = $_POST['libro'];
$codigo = $_POST['codigo'];
$horas = $_POST['horas'];
$inicio = $_POST['inicio'];
$fin = $_POST['fin'];
$emision = $_POST['emision'];

$resultado = insertarRegistro($trabajador, $curso, $libro, $codigo, $horas, $inicio, $fin, $emision);

echo $resultado ? "Registro guardado" : "Error";

//--Consulta Dni
if (isset($_POST['buscar'])) {

    $dni = $_POST['dni'];
    $resultado = buscarPorDNI($dni);

    if ($resultado && pg_num_rows($resultado) > 0) {
        while ($row = pg_fetch_assoc($resultado)) {
            echo "<p>";
            echo "Nombre: " . $row['nombres'] . " " . $row['apellidos'] . "<br>";
            echo "Curso: " . $row['nombre_curso'] . "<br>";
            echo "Horas: " . $row['horas_realizadas'] . "<br>";
            echo "</p><hr>";
        }
    } else {
        echo "❌ No encontrado";
    }
}


//--REPORTE DE HORAS--
if (isset($_POST['reporte'])) {

    $resultado = obtenerReporteHoras();

    if ($resultado && pg_num_rows($resultado) > 0) {

        echo "<h2>Reporte de Horas</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Nombre</th><th>Curso</th><th>Horas</th></tr>";

        while ($row = pg_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>" . $row['nombres'] . " " . $row['apellidos'] . "</td>";
            echo "<td>" . $row['nombre_curso'] . "</td>";
            echo "<td>" . $row['horas_realizadas'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

    } else {
        echo "❌ No hay datos";
    }
}
?>

