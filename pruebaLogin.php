<?php
// prueba_login.php
require_once './config/DB.php';

echo "<h2>Prueba de Login</h2>";

// Conectar a la base de datos
try {
    $db = DB::conectar();
    echo "✅ Conexión exitosa<br><br>";
} catch (Exception $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// Buscar al usuario admin
$usuario = 'admin';
$sql = "SELECT * FROM administradores WHERE usuario = :usuario";
$ps = $db->prepare($sql);
$ps->bindParam(":usuario", $usuario);
$ps->execute();
$admin = $ps->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    echo "✅ Usuario encontrado:<br>";
    echo "<pre>";
    print_r($admin);
    echo "</pre><br>";
    
    // Probar contraseña
    $password_ingresada = '123456';
    $password_bd = $admin['password'];
    
    echo "Contraseña ingresada: " . $password_ingresada . "<br>";
    echo "Contraseña en BD: " . $password_bd . "<br>";
    
    if ($password_ingresada === $password_bd) {
        echo "✅✅✅ CONTRASEÑA CORRECTA - LOGIN EXITOSO ✅✅✅";
    } else {
        echo "❌ CONTRASEÑA INCORRECTA";
    }
} else {
    echo "❌ Usuario 'admin' no encontrado en la base de datos";
}
?>