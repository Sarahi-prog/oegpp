<?php
require_once 'models/NotasModuloModel.php';
require_once 'models/NotasModulo.php';
require_once 'helpers/loggers.php';

class NotasModuloController {
    public function listarNotas(){
        try {
            $model = new NotasModuloModel();
            $notas = $model->cargar();
            require './views/notas.php';
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function guardarNota(){
        try {
            if(isset($_POST['trabajador_id']) && isset($_POST['modulo_id']) && isset($_POST['nota'])){
                $notasmodulo = new NotasModulo();
                $notasmodulo->setCliente($_POST['trabajador_id']);
                $notasmodulo->setModuloId($_POST['modulo_id']);
                $notasmodulo->setNota($_POST['nota']);
                $notasmodulo->setFechaRegistro($_POST['fecha_registro'] ?? null);

                $model = new NotasModuloModel();
                $model->guardar($notasmodulo);

                header('Location: index.php?accion=notas');
                exit;
            }

            $this->listarNotas();
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function modificar(){
        try {
            if(isset($_POST['id_nota']) && isset($_POST['trabajador_id']) && isset($_POST['modulo_id']) && isset($_POST['nota'])){
                $notasmodulo = new NotasModulo();
                $notasmodulo->setIdNota($_POST['id_nota']);
                $notasmodulo->setModuloId($_POST['modulo_id']);
                $notasmodulo->setCliente($_POST['trabajador_id']);
                $notasmodulo->setNota($_POST['nota']);

                $model = new NotasModuloModel();
                $model->modificar($notasmodulo);
            }
        } catch (Exception $e) {
            Logger::error($e);
        }
    }
}

?>