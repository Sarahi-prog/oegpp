<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './controllers/TrabajadoresController.php';
require_once './controllers/AdministradoresController.php';
require_once './controllers/CursosController.php';
require_once './controllers/LibrosRegistroController.php';
require_once './controllers/RegistroCapacitacionController.php';

    $accion=isset($_GET['accion'])?$_GET['accion']:'inicio';
    switch($accion){
        case 'administradores':
            $controller= new AdministradoresController();
            $controller->cargar();
        break;
        case 'inicio':
            $controller= new TrabajadoresController();
            $controller->inicioDashboard(); 
        break;
        case 'clientes':
            $controller= new TrabajadoresController();
            $controller->cargar(); 
        break;
        case 'cursos':
            $cursosController = new CursosController();
            $cursosController->cargar();
        break;
        case 'guardar_cliente':
            $controller= new TrabajadoresController();
            $controller->guardar();
        break;
        case 'guardar_curso':
            $cursosController = new CursosController();
            $cursosController->guardar();
        break;
        case 'libros_registro':
            $controller= new LibrosRegistroController();
            $controller->cargar();
        break;
        case 'guardar_libro':
            $controller= new LibrosRegistroController();
            $controller->guardar();
        break;
        case 'modificar_libro_registro':
            $controller= new LibrosRegistroController();
            $controller->modificar();
        break;
        case 'registros_capacitacion':
            $controller= new RegistroCapacitacionController();
            $controller->cargar();
        break;
        case 'guardar_capacitacion':
            $controller= new RegistroCapacitacionController();
            $controller->guardar();
        break;
        default:
            echo "<div style='text-align: center; padding: 50px; font-family: sans-serif;'>";
            echo "<h1 style='color: #ef4444;'>Error 404: Página no encontrada</h1>";
            echo "<p>La acción solicitada (<strong>" . htmlspecialchars($accion) . "</strong>) no existe en el sistema.</p>";
            echo "<a href='index.php?accion=inicio' style='color: #3b82f6; text-decoration: none;'>Volver al inicio</a>";
            echo "</div>";
        break;
    }
?>