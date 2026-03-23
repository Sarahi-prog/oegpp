<?php

// conexión
$conexion = mysqli_connect("localhost", "root", "", "oegpp");

// verificar conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// capturar datos
$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$celular = $_POST['celular'];
$area = $_POST['area'];

// insertar datos
$sql = "INSERT INTO trabajadores (dni, nombres, apellidos, correo, celular, area)
        VALUES ('$dni', '$nombres', '$apellidos', '$correo', '$celular', '$area')";

// ejecutar
$resultado = mysqli_query($conexion, $sql);

// verificar si funcionó
if ($resultado) {
    // éxito
    header("Location: trabajadores.php");
} else {
    // error
    echo "Error al guardar: " . mysqli_error($conexion);
}

?>