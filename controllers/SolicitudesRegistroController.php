<?php
// controllers/SolicitudesRegistroController.php

require_once __DIR__ . '/../models/SolicitudesRegistroModel.php';

class SolicitudesRegistroController {
    private $model;

    public function __construct() {
        $this->model = new SolicitudesRegistroModel();
    }

    // ✅ Mostrar vista de autorización
    public function mostrarVista() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] === 'asistente') {
            header('Location: index.php?accion=inicio&error=no_autorizado');
            exit();
        }
        
        require_once __DIR__ . '/../views/autorizacionUsuarios.php';
    }

    // ✅ Obtener historial de solicitudes
    public function obtenerHistorial() {
        header('Content-Type: application/json');
        $datos = $this->model->obtenerHistorial();
        echo json_encode(['success' => true, 'data' => $datos]);
        exit();
    }

    // ✅ Crear nueva solicitud de registro
    public function crearSolicitud() {
        header('Content-Type: application/json');
        
        $datos = json_decode(file_get_contents('php://input'), true);
        
        // Validar datos
        if (!isset($datos['usuario']) || !isset($datos['correo']) || !isset($datos['password'])) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit();
        }
        
        // Validar que las contraseñas coincidan
        if ($datos['password'] !== $datos['confirmar_password']) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
            exit();
        }
        
        // Encriptar password antes de enviar al modelo
        $passHash = password_hash($datos['password'], PASSWORD_BCRYPT);
        $rol = $datos['rol'] ?? 'asistente';
        
        $res = $this->model->crearSolicitud($datos['usuario'], $datos['correo'], $passHash, $rol);
        
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Solicitud registrada correctamente. Espera aprobación del administrador.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar solicitud']);
        }
        exit();
    }

    // ✅ Aprobar solicitud
    public function aprobarSolicitud() {
        header('Content-Type: application/json');
        
        // Verificar permisos
        if (!isset($_SESSION['admin_general']) || $_SESSION['admin_general'] !== true) {
            echo json_encode(['success' => false, 'message' => 'No tienes permisos para aprobar solicitudes']);
            exit();
        }
        
        $datos = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($datos['id_solicitud'])) {
            echo json_encode(['success' => false, 'message' => 'ID de solicitud requerido']);
            exit();
        }
        
        $res = $this->model->aprobarSolicitud($datos['id_solicitud'], $_SESSION['admin_id']);
        
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Usuario aprobado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al aprobar solicitud']);
        }
        exit();
    }

    // ✅ Rechazar solicitud
    public function rechazarSolicitud() {
        header('Content-Type: application/json');
        
        // Verificar permisos
        if (!isset($_SESSION['admin_general']) || $_SESSION['admin_general'] !== true) {
            echo json_encode(['success' => false, 'message' => 'No tienes permisos para rechazar solicitudes']);
            exit();
        }
        
        $datos = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($datos['id_solicitud'])) {
            echo json_encode(['success' => false, 'message' => 'ID de solicitud requerido']);
            exit();
        }
        
        $motivo = $datos['motivo'] ?? null;
        $res = $this->model->rechazarSolicitud($datos['id_solicitud'], $_SESSION['admin_id'], $motivo);
        
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Solicitud rechazada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al rechazar solicitud']);
        }
        exit();
    }

    // ✅ Eliminar solicitud
    public function eliminarSolicitud() {
        header('Content-Type: application/json');
        
        // Verificar permisos
        if (!isset($_SESSION['admin_general']) || $_SESSION['admin_general'] !== true) {
            echo json_encode(['success' => false, 'message' => 'No tienes permisos para eliminar solicitudes']);
            exit();
        }
        
        $datos = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($datos['id_solicitud'])) {
            echo json_encode(['success' => false, 'message' => 'ID de solicitud requerido']);
            exit();
        }
        
        $res = $this->model->eliminarSolicitud($datos['id_solicitud']);
        
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Solicitud eliminada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar solicitud']);
        }
        exit();
    }


 // ✅ Obtener lista de admins para resetear contraseña
public function obtenerAdmins() {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['admin_general']) || $_SESSION['admin_general'] !== true) {
        echo json_encode(['success' => false, 'message' => 'No tienes permisos']);
        exit();
    }
    
    $model = new AdministradoresModel();
    $admins = $model->obtenerTodos();
    echo json_encode(['success' => true, 'data' => $admins]);
    exit();
}

// ✅ Resetear contraseña
public function resetearPassword() {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['admin_general']) || $_SESSION['admin_general'] !== true) {
        echo json_encode(['success' => false, 'message' => 'No tienes permisos']);
        exit();
    }
    
    $datos = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($datos['id_admin']) || !isset($datos['nueva_password'])) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit();
    }
    
    $passHash = password_hash($datos['nueva_password'], PASSWORD_BCRYPT);
    $model = new AdministradoresModel();
    $res = $model->resetearPassword($datos['id_admin'], $passHash);
    
    if ($res) {
        echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar contraseña']);
    }
    exit();
}
}
?>
