<?php
require_once("../models/trabajadoresModel.php");

$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];

$resultado = insertarTrabajador($dni, $nombres, $apellidos, $correo);

if ($resultado) {
    echo "Trabajador registrado (sin empresa)";
} else {
    echo "Error al registrar";
}
?>