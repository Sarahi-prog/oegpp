<?php
// 1. CONFIGURACIÓN INICIAL
session_start();

// Definimos la ruta base de controladores para evitar el error "Class not found"
$dirControllers = __DIR__ . '/controllers/';

// 2. CARGA DE CONTROLADORES (CON VALIDACIÓN DE EXISTENCIA)
$archivos = [
    'TrabajadoresController.php',
    'LoginController.php',
    'VerificacionController.php',
    'SolicitudesRegistroController.php'
];

foreach ($archivos as $archivo) {
    if (file_exists($dirControllers . $archivo)) {
        require_once $dirControllers . $archivo;
    } else {
        die("Error Crítico: No se pudo encontrar el controlador: " . $archivo);
    }
}

// 3. DEFINICIÓN DE LA ACCIÓN
$accion = $_GET['accion'] ?? 'login';

// 4. ACCIONES PÚBLICAS (Accesibles sin iniciar sesión)
$accionesPublicas = [
    'login', 
    'procesar_login', 
    'logout', 
    'registroUsuario', 
    'registrarUsuario', 
    'verificarUsuario'
];

$isPublic = in_array($accion, $accionesPublicas);

// Redirigir al login si intenta entrar a zona privada sin sesión
if (!$isPublic && !isset($_SESSION['admin_id'])) {
    header('Location: index.php?accion=login');
    exit();
}

// 5. INSTANCIA DE CONTROLADORES
// Si llegaste aquí es porque los archivos se cargaron correctamente
$controllerTrabajadores = new TrabajadoresController();
$controllerLogin        = new LoginController();
$controllerVerificacion = new VerificacionController();
$controllerSolicitudes  = new SolicitudesRegistroController();

// 6. ENRUTAMIENTO (SWITCH)
switch ($accion) {
    
    // --- LOGIN Y SESIÓN ---
    case 'login':
        $controllerLogin->mostrarLogin();
        break;

    case 'procesar_login':
        $controllerLogin->procesarLogin();
        break;

    case 'logout':
        $controllerLogin->logout();
        break;

    // --- REGISTRO PÚBLICO (Nuevos usuarios como Fransua) ---
    case 'registroUsuario':
        require_once __DIR__ . '/views/inicio/registro.php';
        break;

    case 'registrarUsuario':
        $controllerSolicitudes->crearSolicitud();
        break;

    case 'verificarUsuario':
        // Validación AJAX rápida de disponibilidad de nombre
        header('Content-Type: application/json');
        require_once __DIR__ . '/models/SolicitudesRegistroModel.php';
        $model = new SolicitudesRegistroModel();
        echo json_encode(['disponible' => !$model->usuarioExiste($_GET['usuario'] ?? '')]);
        exit();

    // --- PANEL DE AUTORIZACIÓN (Privado - Solo Administradores) ---
    case 'autorizacionUsuarios':
        $controllerSolicitudes->mostrarVista();
        break;

    case 'obtenerHistorialSolicitudes':
        $controllerSolicitudes->obtenerHistorial();
        break;

    case 'aprobarSolicitud':
        $controllerSolicitudes->aprobarSolicitud();
        break;

    case 'rechazarSolicitud':
        $controllerSolicitudes->rechazarSolicitud();
        break;

    case 'eliminarSolicitud':
        $controllerSolicitudes->eliminarSolicitud();
        break;

    // --- GESTIÓN DE TRABAJADORES ---
    case 'inicio':
        require_once __DIR__ . '/views/dashboard.php';
        break;

    case 'trabajadores':
        $controllerTrabajadores->listarTrabajadores();
        break;

    case 'nueva_asignacion':
        $controllerTrabajadores->mostrarFormularioAsignacion();
        break;

    case 'guardar_asignacion':
        $controllerTrabajadores->procesarAsignacion();
        break;

    // --- DEFAULT: ERROR 404 ---
    default:
        http_response_code(404);
        echo "<h1>Error 404: La acción [ $accion ] no existe.</h1>";
        break;
}