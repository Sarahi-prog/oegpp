<?php
/*
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
*/
?>

<?php
class DB {
    public static function conectar() {
        $host = "127.0.0.1";
        $port = "4050"; 
        $dbname = "oegpp";
        $user = "postgres";
        $password = "123";

        try {
            $cn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
            $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $cn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>

