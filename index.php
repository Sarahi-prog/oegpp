<?php
// index.php
require_once './controllers/TrabajadoresController.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'inicio'; 
$controller = new TrabajadoresController();

switch($accion) {
    case 'inicio':
        $controller->inicioDashboard(); 
        break;

    case 'trabajadores':
        $controller->listarTrabajadores(); 
        break;

    case 'nueva_asignacion':
        $controller->mostrarFormularioAsignacion(); 
        break;

    case 'guardar_asignacion':
        $controller->procesarAsignacion(); 
        break;

    default:
        echo "<h1>Error 404: Página no encontrada</h1>";
        break;
}
?>