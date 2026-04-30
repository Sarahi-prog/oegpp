<?php
 
class DashboardModel {
    private $db;
 
    public function __construct($conexion) {
        $this->db = $conexion;
    }
 
    public function obtenerTotalCursos() {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cursos");
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

   public function obtenerTotalCursosActivos() {
    try {
        // Aseguramos que solo cuente los que tienen 1 exacto
        $sql = "SELECT COUNT(*) as total FROM cursos WHERE estado = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)($res['total'] ?? 0);
    } catch (Exception $e) {
        error_log("Error Dashboard: " . $e->getMessage());
        return 0;
    }
}
 
    public function obtenerTotalCertificados() {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cursos WHERE tipo = 'certificados'");
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
 
    public function obtenerTotalDiplomados() {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cursos WHERE tipo = 'diplomados'");
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}
 