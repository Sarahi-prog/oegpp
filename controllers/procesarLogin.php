<?php
session_start();
require_once __DIR__ . '/../models/AdministradoresModel.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['usuario']) && isset($data['password'])) {
    $usuario = trim($data['usuario']);
    $password = trim($data['password']);

    if (empty($usuario) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Complete todos los campos']);
        exit();
    }

    $model = new AdministradoresModel();
    $admin = $model->verificarCredenciales($usuario, $password);

    if ($admin) {
        // 1. Definir si es Admin Maestro (importante definirlo antes de usarlo)
        $esAdminMaestro = (strtolower($admin['usuario'] ?? '') === 'admin');
        
        // 2. Validar Verificación (PostgreSQL devuelve 't' o true)
        $estaVerificado = ($admin['verificado'] === true || $admin['verificado'] === 't' || $admin['verificado'] == 1);

        if (!$esAdminMaestro && !$estaVerificado) {
            echo json_encode([
                'success' => false, 
                'message' => 'Tu cuenta aún no ha sido autorizada. Estado actual: Pendiente.'
            ]);
            exit();
        }
        
        // 3. CONFIGURACIÓN DE SESIÓN
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_usuario'] = $admin['usuario'];
        $_SESSION['rol'] = $admin['rol'] ?? 'asistente';
        
        $rolLower = strtolower($_SESSION['rol']);
        // Guardamos si es admin general para los menús
        $_SESSION['admin_general'] = ($rolLower === 'administrador general' || $rolLower === 'administrador' || $esAdminMaestro);
        $_SESSION['login_time'] = time();
        
        // 4. RESPUESTA DE ÉXITO
        echo json_encode([
            'success' => true, 
            'message' => 'Bienvenido ' . $admin['usuario']
        ]);
        exit();

    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o formato incorrecto']);
    exit();
}