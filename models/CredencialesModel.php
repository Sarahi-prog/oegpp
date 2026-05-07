<?php
require_once './config/DB.php';

class CredencialesModel {
    private $conexion;

    public function __construct() {
        $this->conexion = DB::conectar();
    }

    // Generar token único
    public function generarToken() {
        return bin2hex(random_bytes(32));
    }

    // Registrar nuevo administrador (pendiente de verificación)
    public function registrarAdmin($usuario, $correo, $password, $rol = 'administrador') {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $token = $this->generarToken();
            $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $sql = "INSERT INTO administradores (usuario, correo, password, rol, token_verificacion, token_expiracion) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conexion->prepare($sql);
            $resultado = $stmt->execute([$usuario, $correo, $password_hash, $rol, $token, $fecha_expiracion]);

            if ($resultado) {
                return [
                    'success' => true,
                    'token' => $token,
                    'mensaje' => 'Registro exitoso. Comparte este token para verificar.'
                ];
            }
            return ['success' => false, 'mensaje' => 'Error al registrar'];
        } catch (PDOException $e) {
            return ['success' => false, 'mensaje' => $e->getMessage()];
        }
    }

    // Obtener administradores pendientes de verificación
    public function obtenerAdminPendientes() {
        try {
            $sql = "SELECT id_admin, usuario, correo, rol, fecha_creacion 
                    FROM administradores 
                    WHERE token_verificacion IS NOT NULL 
                    AND (token_expiracion IS NULL OR token_expiracion > NOW())
                    ORDER BY fecha_creacion DESC";
            
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Obtener administradores verificados
    public function obtenerAdminVerificados() {
        try {
            $sql = "SELECT id_admin, usuario, correo, rol 
                    FROM administradores 
                    WHERE token_verificacion IS NULL
                    ORDER BY usuario ASC";
            
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Verificar con token y aprobar
    public function verificarConToken($id_admin, $token) {
        try {
            // Validar token
            $sql = "SELECT * FROM administradores 
                    WHERE id_admin = ? 
                    AND token_verificacion = ? 
                    AND (token_expiracion IS NULL OR token_expiracion > NOW())";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id_admin, $token]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin) {
                return ['success' => false, 'mensaje' => 'Token inválido o expirado'];
            }

            // Limpiar token (aprobado)
            $sql_update = "UPDATE administradores 
                          SET token_verificacion = NULL, token_expiracion = NULL
                          WHERE id_admin = ?";
            
            $stmt_update = $this->conexion->prepare($sql_update);
            if ($stmt_update->execute([$id_admin])) {
                return ['success' => true, 'mensaje' => 'Administrador verificado exitosamente'];
            }
            
            return ['success' => false, 'mensaje' => 'Error al verificar'];
        } catch (PDOException $e) {
            return ['success' => false, 'mensaje' => $e->getMessage()];
        }
    }

    // Rechazar administrador (desde admin general)
    public function rechazarAdmin($id_admin, $motivo = '') {
        try {
            // Guardar razón en comentario y eliminar
            $sql = "DELETE FROM administradores WHERE id_admin = ? AND token_verificacion IS NOT NULL";
            
            $stmt = $this->conexion->prepare($sql);
            if ($stmt->execute([$id_admin])) {
                return ['success' => true, 'mensaje' => 'Administrador rechazado'];
            }
            return ['success' => false, 'mensaje' => 'Error al rechazar'];
        } catch (PDOException $e) {
            return ['success' => false, 'mensaje' => $e->getMessage()];
        }
    }

    // Aprobar administrador manualmente (desde admin general)
    public function aprobarAdmin($id_admin) {
        try {
            $sql = "UPDATE administradores 
                    SET token_verificacion = NULL, token_expiracion = NULL
                    WHERE id_admin = ?";
            
            $stmt = $this->conexion->prepare($sql);
            if ($stmt->execute([$id_admin])) {
                return ['success' => true, 'mensaje' => 'Administrador aprobado'];
            }
            return ['success' => false, 'mensaje' => 'Error al aprobar'];
        } catch (PDOException $e) {
            return ['success' => false, 'mensaje' => $e->getMessage()];
        }
    }

    // Obtener información de administrador
    public function obtenerAdmin($id_admin) {
        try {
            $sql = "SELECT id_admin, usuario, correo, rol, token_verificacion FROM administradores WHERE id_admin = ?";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$id_admin]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // Contar pendientes
    public function contarPendientes() {
        try {
            $sql = "SELECT COUNT(*) as total FROM administradores 
                    WHERE token_verificacion IS NOT NULL 
                    AND (token_expiracion IS NULL OR token_expiracion > NOW())";
            
            $stmt = $this->conexion->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
}
?>
