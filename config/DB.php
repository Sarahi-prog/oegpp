<?php
class DB {
    public static function conectar(){
        $host = "aws-1-us-west-2.pooler.supabase.com";
        $port = "5432";
        $dbname = "postgres";
        $user = "postgres.tprhfmrbkpqtqsvlmxym";
        $password = "loussianaJ9";
        
        try {
            $conn = new PDO(
                "pgsql:host=$host;port=$port;dbname=$dbname",
                $user,
                $password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
?>