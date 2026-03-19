<?php
require_once './models/TrabajadoresModel.php';
require_once './helpers/verificacion.php';

class TrabajadoresController {

    public function cargar() {
        $model = new TrabajadoresModel();
        $trabajadores = $model->cargar();
        require './view/viewCargarTrabajadores.php';
    }
}