<?php

class DB {
    private static $conn = null;
    public static function conectar() {
        if (self::$conn !== null) {
            return self::$conn;
        }
        $host     = "aws-1-us-west-2.pooler.supabase.com";
        $port     = "5432";
        $dbname   = "postgres";
        $user     = "postgres.tprhfmrbkpqtqsvlmxym";
        $password = "loussianaJ9";
        try {
            self::$conn = new PDO(
                "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => true
                ]
            );
            return self::$conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}