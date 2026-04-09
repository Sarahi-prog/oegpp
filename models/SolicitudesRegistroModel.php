<?php
require_once __DIR__ . '/../config/DB.php'; 

class SolicitudesRegistroModel {
    private $conexion;

    public function __construct() {
        $this->conexion = DB::conectar(); 
    }

    // 1. Crear nueva solicitud de registro
    public function crearSolicitud($usuario, $correo, $password) {
        try {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO solicitudes_registro 
                    (usuario, correo, password_hash, estado) 
                    VALUES (?, ?, ?, 'pendiente')
                    RETURNING id_solicitud";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$usuario, $correo, $password_hash]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['id_solicitud'] ?? false;
        } catch (PDOException $e) {
            error_log("Error al crear solicitud: " . $e->getMessage());
            return false;
        }
    }

    // 2. Obtener todas las solicitudes pendientes
    public function obtenerPendientes() {
        try {
            $sql = "SELECT * FROM solicitudes_registro 
                    WHERE estado = 'pendiente' 
                    ORDER BY fecha_solicitud DESC";
            
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error al obtener solicitudes pendientes: " . $e->getMessage());
            return [];
        }
    }

    // 3. Obtener una solicitud específica
    public function obtenerSolicitud($id_solicitud) {
        try {
            $sql = "SELECT * FROM solicitudes_registro WHERE id_solicitud = ?";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id_solicitud]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener solicitud: " . $e->getMessage());
            return null;
        }
    }

    // 4. Obtener historial de solicitudes (todas)
    public function obtenerHistorial($filtro_estado = null) {
        try {
            if ($filtro_estado) {
                $sql = "SELECT * FROM solicitudes_registro 
                        WHERE estado = ? 
                        ORDER BY fecha_solicitud DESC";
                $stmt = $this->conexion->prepare($sql);
                $stmt->execute([$filtro_estado]);
            } else {
                $sql = "SELECT sr.*, a.usuario as autorizado_por_usuario 
                        FROM solicitudes_registro sr
                        LEFT JOIN administradores a ON sr.autorizado_por = a.id_admin
                        ORDER BY sr.fecha_solicitud DESC";
                $stmt = $this->conexion->query($sql);
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error al obtener historial: " . $e->getMessage());
            return [];
        }
    }

    // 5. Aprobar solicitud (y crear usuario en trabajadores si es necesario)
    public function aprobarSolicitud($id_solicitud, $id_admin) {
        try {
            $this->conexion->beginTransaction();
            
            // Obtener datos de la solicitud
            $solicitud = $this->obtenerSolicitud($id_solicitud);
            if (!$solicitud) {
                throw new Exception("Solicitud no encontrada");
            }

            // Actualizar estado a aprobado
            $sql = "UPDATE solicitudes_registro 
                    SET estado = 'aprobado', 
                        fecha_autorizacion = CURRENT_TIMESTAMP,
                        autorizado_por = ?
                    WHERE id_solicitud = ?";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id_admin, $id_solicitud]);

            // Crear trabajador asociado si es necesario
            // (Opcional: solo si tienes una tabla trabajadores que se vincule con usuarios)
            
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            error_log("Error al aprobar solicitud: " . $e->getMessage());
            return false;
        }
    }

    // 6. Rechazar solicitud
    public function rechazarSolicitud($id_solicitud, $id_admin, $motivo = null) {
        try {
            $sql = "UPDATE solicitudes_registro 
                    SET estado = 'rechazado', 
                        fecha_autorizacion = CURRENT_TIMESTAMP,
                        motivo_rechazo = ?,
                        autorizado_por = ?
                    WHERE id_solicitud = ?";
            
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$motivo, $id_admin, $id_solicitud]);
        } catch (PDOException $e) {
            error_log("Error al rechazar solicitud: " . $e->getMessage());
            return false;
        }
    }

    // 7. Verificar si usuario ya existe
    public function usuarioExiste($usuario) {
        try {
            $sql = "SELECT COUNT(*) as cantidad FROM solicitudes_registro WHERE usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$usuario]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['cantidad'] > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar usuario: " . $e->getMessage());
            return false;
        }
    }

    // 8. Verificar si correo ya existe
    public function correoExiste($correo) {
        try {
            $sql = "SELECT COUNT(*) as cantidad FROM solicitudes_registro WHERE correo = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$correo]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['cantidad'] > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar correo: " . $e->getMessage());
            return false;
        }
    }

    // 9. Obtener estadísticas
    public function obtenerEstadisticas() {
        try {
            $sql = "SELECT 
                        COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as pendientes,
                        COUNT(CASE WHEN estado = 'aprobado' THEN 1 END) as aprobados,
                        COUNT(CASE WHEN estado = 'rechazado' THEN 1 END) as rechazados,
                        COUNT(*) as total
                    FROM solicitudes_registro";
            
            $stmt = $this->conexion->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ?: [];
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas: " . $e->getMessage());
            return [];
        }
    }

    // 10. Obtener solicitud aprobada por usuario y contraseña (para login)
    public function verificarCredencialesSolicitud($usuario, $password) {
        try {
            $sql = "SELECT * FROM solicitudes_registro 
                    WHERE usuario = ? AND estado = 'aprobado'
                    LIMIT 1";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$usuario]);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && password_verify($password, $resultado['password_hash'])) {
                return $resultado;
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Error al verificar credenciales: " . $e->getMessage());
            return null;
        }
    }
}
?>
