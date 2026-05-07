<?php
ob_start();
session_start();

// 1. CONFIGURACIÓN
$dirControllers = __DIR__ . '/controllers/';
$dirModels = __DIR__ . '/models/';

// 2. CARGA DE CONTROLADORES
$archivos = [
    'LoginController.php',
    'VerificacionController.php',
    'SolicitudesRegistroController.php',
    'CursosController.php',
    'LibrosRegistroController.php',
    'RegistroCapacitacionController.php', // Asegúrate que el archivo sea SINGULAR si la clase lo es
    'NotasModuloController.php',
    'ClientesController.php',            // Corregido de ClientController a ClientesController
    'AdministradoresController.php',
    'ModulosController.php',
    'DashboardController.php',
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

// 4. SEGURIDAD Y TOKENS
$accionesPublicas = [
    'login', 
    'procesar_login', 
    'logout', 
    'registroUsuario', 
    'registrarUsuario',
    'verificarUsuario',
    'autorizacionUsuarios', 
    'obtenerHistorialSolicitudes',
    'buscar_dni',
];

$isPublic = in_array($accion, $accionesPublicas);

if (!$isPublic && !isset($_SESSION['admin_id'])) {
    header('Location: index.php?accion=login');
    exit();
}

// Validación de Token (Solo si no es público)
if (!$isPublic && isset($_SESSION['admin_id'])) {
    require_once $dirModels . 'AdministradoresModel.php'; // IMPORTANTE: Cargar el modelo
    $modelAdmin = new AdministradoresModel();
    $tokenValido = $modelAdmin->verificarTokenSesion(
        $_SESSION['admin_id'], 
        $_SESSION['token_sesion'] ?? ''
    );
    
    if (!$tokenValido) {
        session_destroy();
        header('Location: index.php?accion=login');
        exit();
    }
}

// 5. INSTANCIA DE CONTROLADORES
$controllerLogin        = new LoginController();
$controllerVerificacion = new VerificacionController();
$controllerSolicitudes  = new SolicitudesRegistroController();
$controllerCursos       = new CursosController();
$controllerLibros       = new LibrosRegistroController();
$controllerNotasModulo  = new NotasModuloController();
$controllerRegistrosCap = new RegistroCapacitacionController();
$controllerClientes     = new ClientesController(); // Nombre corregido para el switch
$controllerModulos      = new ModulosController();

require_once __DIR__ . '/config/DB.php';
$controllerDashboard = new DashboardController(DB::conectar());
// 6. ENRUTAMIENTO
ob_clean(); 
switch ($accion) {
    case 'login':           $controllerLogin->mostrarLogin(); break;
    case 'procesar_login':  $controllerLogin->procesarLogin(); break;
    case 'logout':          $controllerLogin->logout(); break;

    case 'registroUsuario': require_once __DIR__ . '/views/inicio/registro.php'; break;
    case 'registrarUsuario': $controllerSolicitudes->crearSolicitud(); break;
    
    case 'verificarUsuario':
        header('Content-Type: application/json');
        require_once $dirModels . 'SolicitudesRegistroModel.php';
        $model = new SolicitudesRegistroModel();
        echo json_encode(['disponible' => !$model->usuarioExiste($_GET['usuario'] ?? '')]);
        exit();

    case 'autorizacionUsuarios':        $controllerSolicitudes->mostrarVista(); break;
    case 'obtenerHistorialSolicitudes': $controllerSolicitudes->obtenerHistorial(); break;
    case 'aprobarSolicitud':            $controllerSolicitudes->aprobarSolicitud(); break;
    case 'rechazarSolicitud':           $controllerSolicitudes->rechazarSolicitud(); break;
    case 'eliminarSolicitud':           $controllerSolicitudes->eliminarSolicitud(); break;

    // Necesitas pasar la conexión — ajusta según cómo la manejes
case 'inicio':
    $controllerDashboard->index();
    break;
    
    
    // Corregidos para usar el controlador de clientes
    case 'clientes':
        // CORREGIDO: Usando el mismo nombre de la instancia
        $controllerClientes->listarClientes(); 
        break;

    case 'buscar_dni':
    $controllerRegistrosCap->buscarPorDni(); // O el nombre del método que tengas en tu controlador
        break;

    case 'guardar_cliente':
        $controllerClientes->guardarCliente();
        break;

    case 'modificar_cliente':
        $controllerClientes->modificarCliente();
        break;
        
    case 'eliminar_cliente':
        $controllerClientes->eliminarCliente();
        break;

    
        case 'notas':
        // Llamamos al método del controlador que ya instanciaste arriba
        $controllerNotasModulo->listarNotas(); 
        break;

        ///cursos
case 'cursos':
    $controllerCursos->cargar(); 
    break;

case 'guardar_curso':
    $controllerCursos->guardarCurso();
    break;

case 'modificar_curso':
    $controllerCursos->modificarCurso();
    break;

case 'eliminar_curso':
    $controllerCursos->eliminarCurso();
    break;

case 'actualizar_estado_curso':
    $controllerCursos->actualizar_estado();
    break;
    
        /// modulos

        case 'modulos':
        $controllerModulos->cargar(); 
        break;

    case 'guardar_modulo':
        $controllerModulos->guardar();
        break;

    case 'modificar_modulo':
        $controllerModulos->modificar();
        break;
    
    ///libros registro
    case 'libros': 
        $controllerLibros->cargar(); 
        break;

    case 'guardar_libro': 
        $controllerLibros->guardar();
        break;

    case 'modificar_libro': 
        $controllerLibros->modificar();
        break;

    // --- AGREGA ESTE CASO QUE FALTA ---
    case 'eliminar_libro':
        $controllerLibros->eliminar(); // Asegúrate que el método en el controlador se llame así
        break;
    // ----------------------------------

    case 'libros_registro':
        $controllerLibros->cargar(); 
        break;



   /// notas modulo
case 'notas':
    $controllerNotasModulo->listarNotas();
    break;

case 'guardar_nota':          // ← FALTA ESTE
    $controllerNotasModulo->guardarNota();
    break;

case 'modificar_nota':
    $controllerNotasModulo->modificar();
    break;

case 'eliminar_nota':
    $controllerNotasModulo->eliminar();
    break;
    
    

    case 'registros_capacitacion':         
        $controllerRegistrosCap->listarRegistros(); 
        break;
    case 'guardar_registro': 
        $controllerRegistrosCap->guardarRegistro(); 
        break;
    case 'modificar_registro':
        $controllerRegistrosCap->modificarRegistro();
        break;
    case 'eliminar_registro':
        $controllerRegistrosCap->eliminarRegistro();
        break;
    case 'obtenerAdmins':    $controllerSolicitudes->obtenerAdmins(); break;
    case 'resetearPassword': $controllerSolicitudes->resetearPassword(); break;

    default:
        http_response_code(404);
        echo "<h1>Error 404: La acción [ $accion ] no existe.</h1>";
        break;
}