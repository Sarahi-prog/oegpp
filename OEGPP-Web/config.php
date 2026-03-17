<?php
$host = "127.0.0.1";
$port = "4050"; 
$dbname = "oegpp";
$user = "postgres";
$password = "123";

$conexion = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if (!$conexion) {
    echo "❌ Error de conexión a la base de datos";
} else {
    echo "✅ Conexión exitosa";
}
?>