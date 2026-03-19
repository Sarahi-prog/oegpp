<?php
require_once './controllers/TrabajadoresController.php';

    $accion=isset($_GET['accion'])?$_GET['accion']:'cargartrabajadores';
    switch($accion){
        case 'cargartrabajadores':
            $controller=new TrabajadoresController();
            $controller->cargar();        
        break;
    }
?>