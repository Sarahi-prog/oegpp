<?php
require_once("../models/modulosModel.php");

$curso_id = $_POST['curso_id'];
$nombre = $_POST['nombre'];
$horas = $_POST['horas'];
$inicio = $_POST['inicio'];
$fin = $_POST['fin'];

$resultado = insertarModulo($curso_id, $nombre, $horas, $inicio, $fin);

echo $resultado ? "Módulo registrado" : "Error";
?>