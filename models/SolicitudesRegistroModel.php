<?php
require_once __DIR__ . '/../config/DB.php'; 

class SolicitudesRegistroModel {
    private $conexion;

    public function __construct() {
        $this->conexion = DB::conectar(); 
    }

    // 1. Crear nueva solicitud de registro
    public function crearSolicitud($usuario, $correo, $password, $rol) {
        try {
            // Insertar en la tabla solicitudes_registro (NO en administradores)
            $sql = "INSERT INTO solicitudes_registro (usuario, correo, password_hash, rol, estado, fecha_solicitud) 
                    VALUES (:usuario, :correo, :password, :rol, 'pendiente', CURRENT_TIMESTAMP)";
            
            $ps = $this->conexion->prepare($sql);
            $ps->bindValue(":usuario", $usuario);
            $ps->bindValue(":correo", $correo);
            $ps->bindValue(":password", $password); // Ya debe venir encriptado del controlador
            $ps->bindValue(":rol", $rol);
            
            return $ps->execute();
        } catch (PDOException $e) {
            error_log("Error en crearSolicitud: " . $e->getMessage());
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
        
        // 1. Obtener datos de la solicitud
        $solicitud = $this->obtenerSolicitud($id_solicitud);
        if (!$solicitud) {
            throw new Exception("Solicitud no encontrada");
        }

        // 2. Actualizar estado a aprobado
        $sql = "UPDATE solicitudes_registro 
                SET estado = 'aprobado', 
                    fecha_autorizacion = CURRENT_TIMESTAMP,
                    autorizado_por = ?
                WHERE id_solicitud = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id_admin, $id_solicitud]);

        // 3. ✅ INSERTAR en la tabla administradores para que pueda hacer login
        $sqlInsert = "INSERT INTO administradores (usuario, correo, password, rol, verificado) 
                      VALUES (:usuario, :correo, :password, :rol, true)";
        $ps = $this->conexion->prepare($sqlInsert);
        $ps->bindValue(":usuario", $solicitud['usuario']);
        $ps->bindValue(":correo",  $solicitud['correo']);
        $ps->bindValue(":password", $solicitud['password_hash']); // Ya está hasheada con bcrypt
        $ps->bindValue(":rol",     $solicitud['rol']);
        $ps->execute();

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

    // 10. Eliminar solicitud
    public function eliminarSolicitud($id_solicitud) {
        try {
            $sql = "DELETE FROM solicitudes_registro WHERE id_solicitud = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_solicitud]);
        } catch (PDOException $e) {
            error_log("Error al eliminar solicitud: " . $e->getMessage());
            return false;
        }
    }
}
?>
