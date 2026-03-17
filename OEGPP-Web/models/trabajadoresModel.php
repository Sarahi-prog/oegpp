<?php
require_once("../config.php");

function insertarTrabajador($dni, $nombres, $apellidos, $correo) {
    global $conexion;

    $sql = "INSERT INTO trabajadores (dni, nombres, apellidos, correo)
            VALUES ($1, $2, $3, $4)";

    $result = pg_query_params($conexion, $sql, array(
        $dni, $nombres, $apellidos, $correo
    ));

    return $result;
}
?>