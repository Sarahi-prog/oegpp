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
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cursos WHERE activo = 1");
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['total'] ?? 0;
        } catch (Exception $e) {
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
 