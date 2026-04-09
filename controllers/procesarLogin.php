<?php
session_start();

// controllers/procesarLogin.php
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
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_usuario'] = $admin['usuario'];
        $_SESSION['admin_correo'] = isset($admin['correo']) ? $admin['correo'] : '';
        $_SESSION['admin_rol'] = isset($admin['rol']) ? $admin['rol'] : 'usuario';
        $_SESSION['admin_general'] = (isset($admin['rol']) && strtolower($admin['rol']) === 'administrador general');
        $_SESSION['login_time'] = time();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Bienvenido ' . $admin['usuario']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>