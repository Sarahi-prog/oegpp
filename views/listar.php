<?php
require_once(__DIR__ . "/../controllers/TrabajadoresController.php");

$controller = new TrabajadoresController();
$trabajadores = $controller->Cargar();
?>