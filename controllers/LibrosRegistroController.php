<?php
require_once 'models/LibrosRegistroModel.php';
require_once 'models/LibrosRegistro.php';

class LibrosRegistroController {

    public function cargar() {
        $model = new LibrosRegistroModel();
        $libros = $model->cargar();
        require './views/libros_registro.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $libro = new LibrosRegistro();
            $libro->setTipo($_POST['tipo']);
            $libro->setNumeroLibro($_POST['numero_libro']);
            $libro->setAnioInicio($_POST['anio_inicio']);
            $libro->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);
            $libro->setDistrito($_POST['distrito'] ?? null);
            $libro->setProvincia($_POST['provincia'] ?? null);
            $libro->setDescripcion($_POST['descripcion'] ?? null);

            $model = new LibrosRegistroModel();
            $model->guardarLibro($libro);

            header('Location: index.php?accion=libros_registro');
            exit;
        }
    }

    public function modificar() {
        if (isset($_POST['id_libro'])) {
            $libro = new LibrosRegistro();
            $libro->setIdLibro($_POST['id_libro']);
            $libro->setTipo($_POST['tipo']);
            $libro->setNumeroLibro($_POST['numero_libro']);
            $libro->setAnioInicio($_POST['anio_inicio']);
            $libro->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);
            $libro->setDistrito($_POST['distrito'] ?? null);
            $libro->setProvincia($_POST['provincia'] ?? null);
            $libro->setDescripcion($_POST['descripcion'] ?? null);

            $model = new LibrosRegistroModel();
            $model->modificarLibro($libro);

            header('Location: index.php?accion=libros_registro');
            exit;
        }
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            $model = new LibrosRegistroModel();
            $model->eliminarLibro($_GET['id']);
        }
        header('Location: index.php?accion=libros_registro');
        exit;
    }
}