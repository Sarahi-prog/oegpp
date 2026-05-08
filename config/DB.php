<?php
 
class DB {
    private static $conn = null;

    public static function conectar() {
        if (self::$conn !== null) return self::$conn; // reutiliza si ya existe

        $host     = "aws-1-us-west-2.pooler.supabase.com";
        $port     = "5432"; // transaction mode
        $dbname   = "postgres";
        $user     = "postgres.tprhfmrbkpqtqsvlmxym";
        $password = "loussianaJ9"; // ← cambiala en Supabase

        try {
            self::$conn = new PDO(
                "pgsql:host=$host;port=$port;dbname=$dbname",
                $user,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return self::$conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}