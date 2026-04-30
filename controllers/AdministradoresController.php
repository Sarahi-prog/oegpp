<?php
require_once __DIR__ . '/../models/AdministradoresModel.php';

class AdministradoresController {

    private $model;

    public function __construct() {
        $this->model = new AdministradoresModel();
    }

    // =========================
    // LISTAR ADMINISTRADORES
    // =========================
    public function cargar() {
        $administradores = $this->model->cargar();
        require './views/viewCargarAdministradores.php';
    }

    // =========================
    // GUARDAR ADMIN (NUEVO)
    // =========================
    public function guardar() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?accion=administradores');
            exit();
        }

        $usuario = $_POST['usuario'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$usuario || !$correo || !$password) {
            header('Location: index.php?accion=administradores&error=1');
            exit();
        }

        $this->model->guardar($usuario, $correo, $password);

        header('Location: index.php?accion=administradores&success=1');
        exit();
    }

    // =========================
    // APROBAR (ADMIN GENERAL)
    // =========================
    public function aprobar() {

        $id = $_GET['id'] ?? null;

        if ($id) {
            $this->model->aprobar($id);
        }

        header('Location: index.php?accion=administradores');
        exit();
    }

    // =========================
    // RECHAZAR (ELIMINAR)
    // =========================
    public function rechazar() {

        $id = $_GET['id'] ?? null;

        if ($id) {
            $this->model->eliminar($id);
        }

        header('Location: index.php?accion=administradores');
        exit();
    }
}
?>
