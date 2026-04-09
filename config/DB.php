<?php
class DB {
    public static function conectar() {
        $host = "127.0.0.1";
        $port = "5432"; 
        $dbname = "OEGPP";
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

