<?php
session_start();

require_once './controllers/TrabajadoresController.php';
require_once './controllers/LoginController.php';
require_once './controllers/VerificacionController.php';
require_once './controllers/SolicitudesRegistroController.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'login';

$accionesPublicas = ['login', 'procesar_login', 'logout', 'registro', 'procesarRegistro', 'verificar', 'procesarVerificacion', 'registrarUsuario', 'verificarUsuario', 'registroUsuario'];
$isPublic = in_array($accion, $accionesPublicas);

if (!$isPublic && !isset($_SESSION['admin_id'])) {
    header('Location: index.php?accion=login');
    exit();
}

$controllerTrabajadores = new TrabajadoresController();
$controllerLogin = new LoginController();
$controllerVerificacion = new VerificacionController();
$controllerSolicitudes = new SolicitudesRegistroController();

switch ($accion) {
    case 'login':
        $controllerLogin->mostrarLogin();
        break;

    case 'procesar_login':
        $controllerLogin->procesarLogin();
        break;

    case 'logout':
        $controllerLogin->logout();
        break;

    case 'registro':
        $controllerVerificacion->mostrarRegistro();
        break;

    case 'procesarRegistro':
        $controllerVerificacion->procesarRegistro();
        break;

    case 'verificar':
        $controllerVerificacion->mostrarFormularioVerificacion();
        break;

    case 'procesarVerificacion':
        $controllerVerificacion->procesarVerificacionToken();
        break;

    case 'panelVerificacion':
        $controllerVerificacion->mostrarPanelVerificacion();
        break;

    case 'aprobarAdmin':
        $controllerVerificacion->aprobar();
        break;

    case 'rechazarAdmin':
        $controllerVerificacion->rechazar();
        break;

    // ===== Nuevas Acciones: Autorización de Usuarios =====
    case 'registroUsuario':
        require_once './views/inicio/registro.php';
        break;

    case 'registrarUsuario':
        $controllerSolicitudes->crearSolicitud();
        break;

    case 'verificarUsuario':
        header('Content-Type: application/json');
        $usuario = $_GET['usuario'] ?? '';
        $model = new SolicitudesRegistroModel();
        $existe = $model->usuarioExiste($usuario);
        echo json_encode(['disponible' => !$existe]);
        exit();
        break;

    case 'autorizacionUsuarios':
        $controllerSolicitudes->mostrarVista();
        break;

    case 'obtenerSolicitudesPendientes':
        $controllerSolicitudes->obtenerPendientes();
        break;

    case 'obtenerHistorialSolicitudes':
        $controllerSolicitudes->obtenerHistorial();
        break;

    case 'obtenerEstadisticasSolicitudes':
        $controllerSolicitudes->obtenerEstadisticas();
        break;

    case 'aprobarSolicitud':
        $controllerSolicitudes->aprobarSolicitud();
        break;

    case 'rechazarSolicitud':
        $controllerSolicitudes->rechazarSolicitud();
        break;

    // ===== Acciones Existentes =====
    case 'inicio':
        require_once 'views/dashboard.php';
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

    default:
        echo "<h1>Error 404: Página no encontrada</h1>";
        break;
}
