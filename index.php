<?php
require_once './controllers/ClientesController.php';
require_once './controllers/CursosController.php';
require_once './controllers/ModulosController.php';
require_once './controllers/NotasModuloController.php';
require_once './controllers/RegistroCapacitacionController.php';
require_once './controllers/LibrosRegistroController.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'inicio'; 

$clientescontroller             = new ClientesController(); 
$cursosController               = new CursosController(); 
$modulosController              = new ModulosController();
$notasModuloController          = new NotasModuloController();
$registroCapacitacionController = new RegistroCapacitacionController();
$librosRegistroController       = new LibrosRegistroController();

switch($accion) {

    case 'inicio':
        $clientescontroller->inicioDashboard(); 
        break;

    case 'clientes':
        $clientescontroller->listarClientes(); 
        break;

    case 'guardar_cliente':
        // Antes decía $controller, debe ser $clientescontroller
        $clientescontroller->guardarCliente();
        break;

    case 'modificar_cliente':
        // Antes decía $controller, debe ser $clientescontroller
        $clientescontroller->modificarCliente();
        break;
    
   
        
    case 'eliminar_cliente':
        // Aquí es donde te saltaba el Fatal Error línea 37
        $clientescontroller->eliminarCliente();
        break;

    // --------------------------------------
    case 'cursos':
        $cursosController->cargar(); 
        break;
        
    case 'cursos_diplomados':
        // Filtra y muestra solo los diplomados
        $cursosController->cargarD();
        break;
        
    case 'cursos_certificados':
        // Filtra y muestra solo los certificados
        $cursosController->cargarC();
        break;
        
    case 'guardar_curso':
        $cursosController->guardar();
        break;

    // --------------------------------------
    case 'modulos':
        $modulosController->listar(); 
        break;

    case 'guardar_modulo':
        $modulosController->guardar();
        break;

    // --------------------------------------
    case 'notas':
        $notasModuloController->listarNotas(); 
        break;

    case 'guardar_nota':
        $notasModuloController->guardarNota(); 
        break;

    case 'modificar_nota':
        $notasModuloController->modificar(); 
        break;
    // --------------------------------------

    case 'registros_capacitacion':
        $registroCapacitacionController->cargar();
        break;

    case 'guardar_capacitacion':
        $registroCapacitacionController->guardar();
        break;

    case 'modificar_capacitacion':
        $registroCapacitacionController->modificar();
        break;

    // --------------------------------------


    case 'libros_registro':
        $librosRegistroController->cargar();
        break;
    
    case 'guardar_libro':
        $librosRegistroController->guardar();
        break;

    case 'modificar_libro':
        $librosRegistroController->modificar();
        break;
        
    case 'buscar_dni':
        $registroCapacitacionController->buscar();  
        break;


    // --------------------------------------
    // [F] ERROR (RUTA NO ENCONTRADA)
    // --------------------------------------
    default:
        // Si el usuario altera la URL y pone algo que no existe (ej. ?accion=batman)
        echo "<div style='text-align: center; padding: 50px; font-family: sans-serif;'>";
        echo "<h1 style='color: #ef4444;'>Error 404: Página no encontrada</h1>";
        echo "<p>La acción solicitada (<strong>" . htmlspecialchars($accion) . "</strong>) no existe en el sistema.</p>";
        echo "<a href='index.php?accion=inicio' style='color: #3b82f6; text-decoration: none;'>Volver al inicio</a>";
        echo "</div>";
        break;
}
?>

