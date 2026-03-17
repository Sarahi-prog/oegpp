<?php
require_once("../models/cursosModel.php");

$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$tipo = $_POST['tipo'];
$horas = $_POST['horas'];

$resultado = insertarCurso($codigo, $nombre, $tipo, $horas);

if ($resultado) {
    echo "Curso registrado";
} else {
    echo "Error al registrar curso";
}
?>