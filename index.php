<?php
// Carga de controladores
// ⚠️ CORRECCIÓN: valor por defecto correcto
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Cargar la configuración de la base de datos
// IMPORTANTE: Verifica que el nombre del archivo sea exactamente este
if (file_exists('./config/DB.php')) {
    require_once './config/DB.php';
} else {
    die("Error: No se encontró el archivo config/DB.php. Revisa el nombre.");
}

// 2. Conectar a la base de datos
$conexion = DB::conectar(); 

// 3. Carga de controladores
require_once './controllers/DashboardController.php';
require_once './controllers/ClientesController.php';
require_once './controllers/CursosController.php';
require_once './controllers/ModulosController.php';
require_once './controllers/NotasModuloController.php';
require_once './controllers/RegistroCapacitacionController.php';
require_once './controllers/LibrosRegistroController.php';

// 4. Captura de acción
$accion = $_REQUEST['accion'] ?? 'inicio';
// Instanciación (pasando conexión donde corresponde)
$dashboardController            = new DashboardController($conexion);
$clientesController             = new ClientesController(); 
$cursosController               = new CursosController(); 
$modulosController              = new ModulosController();
$notasModuloController          = new NotasModuloController();
$registroCapacitacionController = new RegistroCapacitacionController();
$librosRegistroController       = new LibrosRegistroController();
    

switch($accion) {

    case 'inicio':
        $dashboardController->index();  
        break;

    // --- CLIENTES ---
    case 'clientes':
        $clientesController->listarClientes(); 
        break;

    case 'guardar_cliente':
        $clientesController->guardarCliente();
        break;

    case 'modificar_cliente':
        $clientesController->modificarCliente();
        break;
        
    case 'eliminar_cliente':
        $clientesController->eliminarCliente();
        break;

    // --- CURSOS ---
    case 'cursos':
        $cursosController->cargar(); 
        break;

    case 'cargar_diplomados':
        $cursosController->cargarD(); 
        break;

    case 'cargar_certificados':
        $cursosController->cargarC(); 
        break;

    case 'guardar_curso':
        $cursosController->guardarCurso();
        break;

    case 'modificar_curso': 
        $cursosController->modificarCurso();
        break;

    case 'eliminar_curso': 
        $cursosController->eliminarCurso();
        break;

    

    // --- MODULOS ---
    case 'modulos':
        $modulosController->cargar(); 
        break;

    case 'guardar_modulo':
        $modulosController->guardar();
        break;

    case 'modificar_modulo':
        $modulosController->modificar();
        break;
                                                                                                                                                            
    case 'cambiar_estado_modulo':
        $modulosController->cambiarEstado();
        break;
    
    case 'eliminar_modulo':
        $modulosController->eliminar();
        break;


    // --- LIBROS ---
    case 'libros_registro':
        $librosRegistroController->cargar();
        break;

    case 'guardar_libro':
        $librosRegistroController->guardar();
        break;
    
    case 'modificar_libro':
        $librosRegistroController->modificar();
        break;

    case 'eliminar_libro':
        $librosRegistroController->eliminar();
        break;
        

    // --- NOTAS ---
    case 'notas':
        $notasModuloController->listarNotas(); 
        break;

    case 'guardar_nota':
        $notasModuloController->guardarNota(); 
        break;

    case 'modificar_nota':
        $notasModuloController->modificar(); 
        break;
        
    // --- CAPACITACION ---
    case 'registros_capacitacion':
        $registroCapacitacionController->cargar();
        break;

    case 'guardar_registro':
        $registroCapacitacionController->guardar();
        break;


    // --- ERROR ---
    default:
        header("HTTP/1.0 404 Not Found");
        if (file_exists('views/404.php')) {
            require 'views/404.php';
        } else {
            echo "<h1>404 - Página no encontrada</h1>";
            echo "<a href='index.php?accion=inicio'>Volver al inicio</a>";
        }
        break;
}