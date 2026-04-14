<?php
/* ====================================================================
   ARCHIVO PRINCIPAL (ENRUTADOR) - index.php
   ====================================================================
   Este archivo es el "cerebro" de tu aplicación. Absolutamente todas 
   las peticiones del usuario pasan por aquí primero. 
   Dependiendo de la "accion" que venga en la URL (ej. index.php?accion=cursos), 
   este archivo decide qué controlador y qué función debe ejecutarse.
==================================================================== */

// ==========================================
// 1. CARGA DE DEPENDENCIAS (INCLUDES)
// ==========================================
// Aquí traemos los "planos" (clases) de los controladores para poder usarlos.
require_once './controllers/ClientesController.php';
require_once './controllers/CursosController.php';
require_once './controllers/ModulosController.php';
require_once './controllers/NotasModuloController.php';
require_once './controllers/RegistroCapacitacionController.php';


// ==========================================
// 2. CAPTURAR LA ACCIÓN (RUTAS)
// ==========================================
// Leemos la URL buscando la variable '?accion='. 
// Si no existe (ej. el usuario acaba de entrar a la web), por defecto será 'inicio'.
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'inicio'; 

// ==========================================
// 3. INSTANCIAR CONTROLADORES
// ==========================================
// "Despertamos" a los controladores creando objetos para que estén listos para trabajar.
$controller                     = new ClientesController(); 
$cursosController               = new CursosController(); 
$modulosController              = new ModulosController();
$notasModuloController          = new NotasModuloController();
$registroCapacitacionController = new RegistroCapacitacionController();

// ==========================================
// 4. EL SEMÁFORO (EVALUAR LA ACCIÓN)
// ==========================================
// Un bloque switch que actúa como un "agente de tránsito", dirigiendo
// al usuario a la vista o función correcta según la $accion que pidió en la URL.
switch($accion) {

    // --------------------------------------
    // [A] INICIO / DASHBOARD PRINCIPAL
    // --------------------------------------
    case 'inicio':
        // Carga la pantalla principal de bienvenida
        $controller->inicioDashboard(); 
        break;

    // --------------------------------------
    // [B] MÓDULO: TRABAJADORES
    // --------------------------------------
    case 'clientes':
        // Abre la página del directorio (la que tiene el buscador y el botón verde)
        $controller->listarClientes(); 
        break;

    case 'guardar_cliente':
        // Procesa los datos ocultos cuando el usuario le da a "Guardar" en el formulario
        $controller->guardarCliente(); 
        break;

    // --------------------------------------
    // [C] MÓDULO: CURSOS
    // --------------------------------------
    case 'cursos':
        // Muestra todos los cursos generales
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
        // Procesa el formulario para registrar un nuevo curso en la base de datos
        $cursosController->guardar();
        break;

    // --------------------------------------
    // [D] MÓDULO: CONFIGURACIÓN DE MÓDULOS
    // --------------------------------------
    case 'modulos':
        // Muestra la lista de módulos de estudio
        $modulosController->listar(); 
        break;

    case 'guardar_modulo':
        // Guarda un módulo nuevo
        $modulosController->guardar();
        break;

    // --------------------------------------
    // [E] MÓDULO: NOTAS Y CAPACITACIONES
    // --------------------------------------
    case 'notas':
    case 'notas_modulo':
        // IMPORTANTE: Abre la vista académica para gestionar las calificaciones
        $notasModuloController->listarNotas(); 
        break;

    case 'guardar_nota':
        // Procesa el formulario para registrar una nueva calificación
        $notasModuloController->guardarNota(); 
        break;

    case 'modificar_nota':
        // Actualiza una calificación ya existente en el sistema
        $notasModuloController->modificar(); 
        break;

    case 'registros_capacitacion':
        // IMPORTANTE: Abre la lista de capacitaciones
        $registroCapacitacionController->cargar();
        break;

    case 'guardar_capacitacion':
        // IMPORTANTE: Procesa y guarda un registro de capacitación
        $registroCapacitacionController->guardar();
        break;

    case 'modificar_capacitacion':
        // Actualiza los datos de un registro de capacitación existente
        $registroCapacitacionController->modificar();
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

