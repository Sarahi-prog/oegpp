<?php
require_once 'models/Administradores.php';
require_once 'models/AdministradoresModel.php';
require_once 'helpers/loggers.php';
require_once 'models/Sesiones.php';
require_once 'models/SesionesModel.php';

class Verificacion {

    public static function verificarSesion() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: login.php');
            exit();
        }
    }

    public static function verificarRol($rolRequerido) {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $rolRequerido) {
            header('Location: ./views/dashboard.php');
            exit();
        }
    }
}
?>