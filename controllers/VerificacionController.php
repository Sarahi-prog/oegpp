<?php
require_once './models/CredencialesModel.php';

class VerificacionController {

    private function esAdminGeneral() {
        return isset($_SESSION['admin_general']) && $_SESSION['admin_general'] === true;
    }

    // Panel de verificación (vista pública - antes de login)
    public function mostrarFormularioVerificacion() {
        require './views/verificarToken.php';
    }

    // Procesar verificación por token
    public function procesarVerificacionToken() {
        header('Content-Type: application/json');
        
        $id_admin = $_POST['id_admin'] ?? null;
        $token = $_POST['token'] ?? null;

        if (!$id_admin || !$token) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit();
        }

        $model = new CredencialesModel();
        $resultado = $model->verificarConToken($id_admin, $token);

        echo json_encode($resultado);
        exit();
    }

    // Panel de administradores para el admin general
    public function mostrarPanelVerificacion() {
        if (!$this->esAdminGeneral()) {
            die("❌ No tienes permisos para acceder a este panel");
        }

        $model = new CredencialesModel();
        $pendientes = $model->obtenerAdminPendientes();
        $verificados = $model->obtenerAdminVerificados();
        $total_pendientes = count($pendientes);

        $pagina_actual = 'verificacion';
        require './views/panelVerificacion.php';
    }

    // Aprobar administrador (método AJAX)
    public function aprobar() {
        header('Content-Type: application/json');
        
        if (!$this->esAdminGeneral()) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit();
        }

        $id_admin = $_POST['id_admin'] ?? null;

        if (!$id_admin) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit();
        }

        $model = new CredencialesModel();
        $resultado = $model->aprobarAdmin($id_admin);

        echo json_encode($resultado);
        exit();
    }

    // Rechazar administrador (método AJAX)
    public function rechazar() {
        header('Content-Type: application/json');
        
        if (!$this->esAdminGeneral()) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit();
        }

        $id_admin = $_POST['id_admin'] ?? null;
        $motivo = $_POST['motivo'] ?? 'No especificado';

        if (!$id_admin) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit();
        }

        $model = new CredencialesModel();
        $resultado = $model->rechazarAdmin($id_admin, $motivo);

        echo json_encode($resultado);
        exit();
    }

    // Registrar nuevo administrador (formulario público)
    public function mostrarRegistro() {
        require './views/registroAdmin.php';
    }

    // Procesar registro
    public function procesarRegistro() {
        header('Content-Type: application/json');
        
        $usuario = trim($_POST['usuario'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_conf = $_POST['password_confirmacion'] ?? '';

        // Validaciones
        if (empty($usuario) || empty($correo) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Completa todos los campos']);
            exit();
        }

        if ($password !== $password_conf) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden']);
            exit();
        }

        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            exit();
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Correo inválido']);
            exit();
        }

        $model = new CredencialesModel();
        $resultado = $model->registrarAdmin($usuario, $correo, $password);

        echo json_encode($resultado);
        exit();
    }
}
?>
