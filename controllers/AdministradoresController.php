<?php
    require_once __DIR__ . '/../models/AdministradoresModel.php';
    require_once __DIR__ . '/../models/Administradores.php';

    class AdministradoresController{
        
        /**
         * Verifica si el usuario logueado es administrador general
         */
        private function esAdministradorGeneral() {
            return isset($_SESSION['admin_general']) && $_SESSION['admin_general'] === true;
        }

        public function cargar(){
            $model = new AdministradoresModel();
            $administradores = $model->cargar();
            require './views/viewCargarAdministradores.php';
        }

        public function cargarsinR(){
            $model = new AdministradoresModel();
            $administradores = $model->cargar();
            require './views/viewCargarAdministradoresSinR.php';
        }

        public function modificar(){
            // Solo el administrador general puede modificar
            if (!$this->esAdministradorGeneral()) {
                echo json_encode(['success' => false, 'message' => 'Solo el administrador general puede modificar otros administradores']);
                exit();
            }
            
            if(isset($_POST['id_administrador']) && isset($_POST['usuario']) && isset($_POST['password']) && isset($_POST['correo']) && isset($_POST['verificado']) && isset($_POST['rol'])){
                $administradores = new Administradores();
                $administradores->setid_admin($_POST['id_administrador']);
                $administradores->setUsuario($_POST['usuario']);
                $administradores->setPassword($_POST['password']);
                $administradores->setCorreo($_POST['correo']);
                $administradores->setVerificado($_POST['verificado']);
                $administradores->setRol($_POST['rol']);
                $model = new AdministradoresModel();
                $model->modificar($administradores);
                echo json_encode(['success' => true, 'message' => 'Administrador modificado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
            }
        }

        public function guardar(){
            // Solo el administrador general puede guardar nuevos administradores
            if (!$this->esAdministradorGeneral()) {
                echo json_encode(['success' => false, 'message' => 'Solo el administrador general puede agregar nuevos administradores']);
                exit();
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['usuario']) || !isset($data['correo']) || !isset($data['password'])) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos']);
                exit();
            }
            
            $admin = new Administradores();
            $admin->setUsuario(trim($data['usuario']));
            $admin->setCorreo(trim($data['correo']));
            $admin->setPassword($data['password']);
            $admin->setRol(trim($data['rol'] ?? 'administrador'));
            $admin->setVerificado(1);
            
            try {
                $model = new AdministradoresModel();
                $model->guardar($admin);
                echo json_encode(['success' => true, 'message' => 'Administrador agregado exitosamente']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error al agregar: ' . $e->getMessage()]);
            }
            exit();
        }
        
        /**
         * Obtener información del administrador actual logueado
         */
        public function obtenerInformacionActual() {
            if (!isset($_SESSION['admin_id'])) {
                echo json_encode(['success' => false, 'message' => 'No hay sesión activa']);
                exit();
            }
            
            $datos = [
                'id' => $_SESSION['admin_id'],
                'usuario' => $_SESSION['admin_usuario'],
                'correo' => $_SESSION['admin_correo'] ?? '',
                'rol' => $_SESSION['admin_rol'] ?? 'usuario',
                'es_general' => $_SESSION['admin_general'] ?? false
            ];
            
            echo json_encode(['success' => true, 'data' => $datos]);
            exit();
        }
        
        /**
         * Eliminar otro administrador (solo admin general)
         */
        public function eliminar($id_admin = null) {
            if (!$this->esAdministradorGeneral()) {
                echo json_encode(['success' => false, 'message' => 'Solo el administrador general puede eliminar administradores']);
                exit();
            }
            
            $id = $id_admin ?? ($_POST['id_admin'] ?? null);
            
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID de administrador no especificado']);
                exit();
            }
            
            if ($id == $_SESSION['admin_id']) {
                echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propia cuenta']);
                exit();
            }
            
            try {
                $model = new AdministradoresModel();
                $model->eliminar($id);
                echo json_encode(['success' => true, 'message' => 'Administrador eliminado']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        }
    }
?>