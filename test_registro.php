<?php
// test_registro.php - Archivo de prueba para verificar el registro

session_start();
require_once __DIR__ . '/config/DB.php';

echo "<h1>Test de Registro de Solicitudes</h1>";

// Simular datos del formulario
$datos = [
    'usuario' => 'test_user_' . time(),
    'correo' => 'test_' . time() . '@example.com',
    'password' => password_hash('Test123456!', PASSWORD_BCRYPT),
    'rol' => 'asistente'
];

try {
    $db = DB::conectar();
    
    // Verificar que la tabla existe
    $sql_check = "SELECT 1 FROM solicitudes_registro LIMIT 1";
    $result = $db->query($sql_check);
    echo "<p style='color: green;'>✓ Tabla solicitudes_registro existe</p>";
    
    // Intentar insertar
    $sql = "INSERT INTO solicitudes_registro (usuario, correo, password_hash, rol, estado, fecha_solicitud) 
            VALUES (:usuario, :correo, :password, :rol, 'pendiente', CURRENT_TIMESTAMP)";
    
    $ps = $db->prepare($sql);
    $ps->bindValue(":usuario", $datos['usuario']);
    $ps->bindValue(":correo", $datos['correo']);
    $ps->bindValue(":password", $datos['password']);
    $ps->bindValue(":rol", $datos['rol']);
    
    if ($ps->execute()) {
        echo "<p style='color: green;'>✓ Solicitud insertada correctamente</p>";
        echo "<p>Usuario: " . $datos['usuario'] . "</p>";
        echo "<p>Correo: " . $datos['correo'] . "</p>";
        echo "<p>Rol: " . $datos['rol'] . "</p>";
        
        // Verificar que se insertó
        $sql_verify = "SELECT * FROM solicitudes_registro WHERE usuario = :usuario";
        $ps_verify = $db->prepare($sql_verify);
        $ps_verify->bindValue(":usuario", $datos['usuario']);
        $ps_verify->execute();
        $result = $ps_verify->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "<p style='color: green;'>✓ Solicitud verificada en la base de datos</p>";
            echo "<pre>";
            print_r($result);
            echo "</pre>";
        }
    } else {
        echo "<p style='color: red;'>✗ Error al insertar solicitud</p>";
        echo "<pre>";
        print_r($ps->errorInfo());
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>
