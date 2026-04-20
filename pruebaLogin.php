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
// En PostgreSQL, las búsquedas son sensibles a mayúsculas.
// Usamos LOWER() para buscar independientemente de las mayúsculas/minúsculas.
$sql = "SELECT * FROM administradores WHERE LOWER(usuario) = LOWER(:usuario) OR LOWER(correo) = LOWER(:usuario)";
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
    
    echo "Contraseña ingresada (texto plano): " . $password_ingresada . "<br>";
    // Para seguridad, no mostrar el hash completo en un entorno real.
    // Aquí lo mostramos para depuración.
    echo "Contraseña en BD: " . $password_bd . "<br>";
    
    // Intentar verificar con password_verify (para contraseñas hasheadas)
    if (password_verify($password_ingresada, $password_bd)) {
        echo "✅✅✅ CONTRASEÑA CORRECTA - LOGIN EXITOSO ✅✅✅";
    } 
    // Fallback: Comparación directa (para contraseñas en texto plano)
    else if ($password_ingresada === $password_bd) {
        echo "✅✅✅ CONTRASEÑA CORRECTA (texto plano) - LOGIN EXITOSO ✅✅✅";
    }
    else {
        echo "❌ CONTRASEÑA INCORRECTA. Hash no coincide o no es texto plano.";
    }
} else {
    echo "❌ Usuario 'admin' no encontrado en la base de datos";
}
?>