<?php
require_once __DIR__ . '/../models/SolicitudesRegistroModel.php';

class SolicitudesRegistroController {
    private $model;

    public function __construct() {
        $this->model = new SolicitudesRegistroModel();
    }

    // ✅ Verificar si usuario es administrador general
    private function esAdministradorGeneral() {
        return isset($_SESSION['admin_general']) && $_SESSION['admin_general'] === true;
    }

    // ✅ Crear nueva solicitud (Público - Sin autenticación)
    public function crearSolicitud() {
        header('Content-Type: application/json');

        $datos = json_decode(file_get_contents('php://input'), true);

        // Validaciones
        $validaciones = $this->validarDatos($datos);
        if (!$validaciones['valido']) {
            echo json_encode([
                'success' => false,
                'message' => $validaciones['errores']
            ]);
            exit();
        }

        // Verificar si usuario o correo ya existen
        if ($this->model->usuarioExiste($datos['usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'El usuario ya está registrado o en espera de autorización'
            ]);
            exit();
        }

        if ($this->model->correoExiste($datos['correo'])) {
            echo json_encode([
                'success' => false,
                'message' => 'El correo ya está registrado'
            ]);
            exit();
        }

        // Crear solicitud
        $resultado = $this->model->crearSolicitud(
            $datos['usuario'],
            $datos['correo'],
            $datos['password']
        );

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Solicitud registrada correctamente. Espera aprobación del administrador.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar solicitud'
            ]);
        }
        exit();
    }

    // ✅ Obtener solicitudes pendientes (Solo Admin General)
    public function obtenerPendientes() {
        header('Content-Type: application/json');

        if (!$this->esAdministradorGeneral()) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permisos para ver solicitudes'
            ]);
            exit();
        }

        $solicitudes = $this->model->obtenerPendientes();
        echo json_encode([
            'success' => true,
            'data' => $solicitudes
        ]);
        exit();
    }

    // ✅ Obtener historial completo (Solo Admin General)
    public function obtenerHistorial() {
        header('Content-Type: application/json');

        if (!$this->esAdministradorGeneral()) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permisos'
            ]);
            exit();
        }

        $filtro = $_GET['estado'] ?? null;
        $historial = $this->model->obtenerHistorial($filtro);
        
        echo json_encode([
            'success' => true,
            'data' => $historial
        ]);
        exit();
    }

    // ✅ Obtener estadísticas (Solo Admin General)
    public function obtenerEstadisticas() {
        header('Content-Type: application/json');

        if (!$this->esAdministradorGeneral()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit();
        }

        $stats = $this->model->obtenerEstadisticas();
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
        exit();
    }

    // ✅ Aprobar solicitud (Solo Admin General)
    public function aprobarSolicitud() {
        header('Content-Type: application/json');

        if (!$this->esAdministradorGeneral()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No tienes permisos']);
            exit();
        }

        $datos = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($datos['id_solicitud'])) {
            echo json_encode(['success' => false, 'message' => 'ID de solicitud requerido']);
            exit();
        }

        $id_admin = $_SESSION['admin_id'];
        $resultado = $this->model->aprobarSolicitud($datos['id_solicitud'], $id_admin);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Usuario autorizado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al autorizar usuario'
            ]);
        }
        exit();
    }

    // ✅ Rechazar solicitud (Solo Admin General)
    public function rechazarSolicitud() {
        header('Content-Type: application/json');

        if (!$this->esAdministradorGeneral()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'No tienes permisos']);
            exit();
        }

        $datos = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($datos['id_solicitud'])) {
            echo json_encode(['success' => false, 'message' => 'ID de solicitud requerido']);
            exit();
        }

        $id_admin = $_SESSION['admin_id'];
        $motivo = $datos['motivo'] ?? null;
        
        $resultado = $this->model->rechazarSolicitud($datos['id_solicitud'], $id_admin, $motivo);

        if ($resultado) {
            echo json_encode([
                'success' => true,
                'message' => 'Solicitud rechazada'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al rechazar solicitud'
            ]);
        }
        exit();
    }

    // ✅ Mostrar vista de autorización
    public function mostrarVista() {
        if (!$this->esAdministradorGeneral()) {
            header('Location: index.php?accion=inicio');
            exit();
        }
        require_once './views/autorizacionUsuarios.php';
    }

    // ✅ Validar datos de registro
    private function validarDatos($datos) {
        $errores = [];

        if (empty($datos['usuario']) || strlen($datos['usuario']) < 4) {
            $errores[] = 'Usuario debe tener al menos 4 caracteres';
        }

        if (empty($datos['correo']) || !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Correo inválido';
        }

        if (empty($datos['password']) || strlen($datos['password']) < 8) {
            $errores[] = 'Contraseña debe tener al menos 8 caracteres';
        }

        if (empty($datos['confirmar_password']) || $datos['password'] !== $datos['confirmar_password']) {
            $errores[] = 'Las contraseñas no coinciden';
        }

        return [
            'valido' => count($errores) === 0,
            'errores' => $errores
        ];
    }
}
?>
