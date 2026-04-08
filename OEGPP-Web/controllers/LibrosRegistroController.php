<?php
    require_once 'models/LibrosRegistroModel.php';
    require_once 'models/LibrosRegistro.php';
    require_once 'helpers/loggers.php';

    class LibrosRegistroController{

        public function cargar(){
            try {
                $model = new LibrosRegistroModel();
                $libros = $model->cargar();
                require './views/viewCargarLibrosRegistro.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function cargarsinR(){
            try {
                $model = new LibrosRegistroModel();
                $libros = $model->cargarsinR();
                require './views/viewCargarLibrosRegistroSinR.php';
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function modificar(){
            try {
                if(isset($_POST['id_libro']) && isset($_POST['tipo']) && isset($_POST['numero_libro']) && isset($_POST['anio_inicio']) && isset($_POST['fecha_fin'])){
                    $libro = new LibrosRegistro();
                    $libro->setid_libro($_POST['id_libro']);
                    $libro->setTipo($_POST['tipo']);
                    $libro->setNumeroLibro($_POST['numero_libro']);
                    $libro->setAnioInicio($_POST['anio_inicio']);
                    $libro->setFechaFin($_POST['fecha_fin']);
                    $model = new LibrosRegistroModel();
                    $model->modificar($libro);
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function guardar(){
            try {
                if(isset($_POST['tipo']) && isset($_POST['numero_libro']) && isset($_POST['anio_inicio']) && isset($_POST['fecha_fin'])){
                    $libro = new LibrosRegistro();
                    $libro->setTipo($_POST['tipo']);
                    $libro->setNumeroLibro($_POST['numero_libro']);
                    $libro->setAnioInicio($_POST['anio_inicio']);
                    $libro->setFechaFin($_POST['fecha_fin']);
                    $model = new LibrosRegistroModel();
                    $model->guardar($libro);
                } else {
                    require './views/viewGuardarLibroRegistro.php';
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>