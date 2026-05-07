<?php

require_once __DIR__ . '/../config/DB.php';
require_once __DIR__ . '/../models/DashboardModel.php'; 

class DashboardController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function index() {
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