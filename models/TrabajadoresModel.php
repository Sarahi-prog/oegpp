<?php  
require_once(__DIR__ . '/../config/DB.php');

class TrabajadoresModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function cargar() {
        $sql = "SELECT id_trabajador, dni, nombres, apellidos, correo FROM trabajadores";
        $ps = $this->db->prepare($sql);
        $ps->execute();

        $filas = $ps->fetchAll(PDO::FETCH_ASSOC);

        return $filas;
    }
}
?>