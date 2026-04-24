<?php
 
require_once 'models/DashboardModel.php';
 
class DashboardController {
    private $conexion;
 
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }
 
    public function index() {
        if (!$this->conexion) {
            die("Error: No hay conexión a la base de datos.");
        }
 
        $modelo = new DashboardModel($this->conexion);
 
        $datos = [
            'cursos_totales'  => $modelo->obtenerTotalCursos(),
            'cursos_activos'  => $modelo->obtenerTotalCursosActivos(),
            'certificaciones' => $modelo->obtenerTotalCertificados(),
            'diplomas'        => $modelo->obtenerTotalDiplomados()
        ];
 
        require_once 'views/dashboard.php';
    }
}