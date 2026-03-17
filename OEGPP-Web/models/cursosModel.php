<?php
require_once("../config.php");

function insertarCurso($codigo, $nombre, $tipo, $horas) {
    global $conexion;

    $sql = "INSERT INTO cursos (codigo_curso, nombre_curso, tipo, horas_totales)
            VALUES ($1, $2, $3, $4)";

    $result = pg_query_params($conexion, $sql, array(
        $codigo, $nombre, $tipo, $horas
    ));

    return $result;
}
?>