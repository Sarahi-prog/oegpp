<?php
require_once(__DIR__ . "/../config/DB.php");

try {
    $cn = DB::conectar();

    // 🔥 Recibir datos (evita errores si no llegan)
    $dni = $_POST['dni'] ?? '';
    $nombres = $_POST['nombres'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $correo = $_POST['correo'] ?? '';

    // 🔴 Validación básica
    if (empty($dni) || empty($nombres) || empty($apellidos) || empty($correo)) {
        die("Todos los campos son obligatorios");
    }

    // 🔥 Query segura
    $sql = "INSERT INTO trabajadores 
            (dni, nombres, apellidos, correo) 
            VALUES (:dni, :nombres, :apellidos, :correo)";

    $stmt = $cn->prepare($sql);

    $stmt->execute([
        ':dni' => $dni,
        ':nombres' => $nombres,
        ':apellidos' => $apellidos,
        ':correo' => $correo
    ]);

    echo "Guardado correctamente";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>