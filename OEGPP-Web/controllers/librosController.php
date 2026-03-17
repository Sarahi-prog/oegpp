<?php
require_once("../models/librosModel.php");

$tipo = $_POST['tipo'];
$numero = $_POST['numero'];
$anio = $_POST['anio'];

$resultado = insertarLibro($tipo, $numero, $anio);

echo $resultado ? "Libro registrado" : "Error";
?>