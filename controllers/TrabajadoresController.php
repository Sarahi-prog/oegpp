<?php
require_once '../models/TrabajadoresModel.php';

class TrabajadoresController {

    public function Cargar() {
        $model = new TrabajadoresModel();
        $trabajadores = $model->cargar();
        require '../views/viewCargarTrabajadores.php';
    }

    public function pruebas(){
        require './views/trabajadores.php';
    }
}