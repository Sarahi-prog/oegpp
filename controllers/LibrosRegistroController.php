<?php
    require_once 'models/LibrosRegistroModel.php';
    require_once 'models/LibrosRegistro.php';
    require_once 'helpers/loggers.php';

    class LibrosRegistroController{

        public function cargar(){
            try {
                $model = new LibrosRegistroModel();
                $libros = $model->cargar(); // <--- SI ESTO FALLA...
                require './views/libros_registro.php'; // <--- ESTO SE SALTA
            } catch (Exception $e) {
                Logger::error($e); // <--- Y EL ERROR SE ESCONDE AQUÍ
            }
}
        public function modificar(){
            try {
                // Solo exigimos los campos que son NOT NULL en la base de datos
                if(isset($_POST['id_libro']) && isset($_POST['tipo']) && isset($_POST['numero_libro']) && isset($_POST['anio_inicio'])){
                    
                    $libro = new LibrosRegistro();
                    $libro->setIdLibro($_POST['id_libro']);
                    $libro->setTipo($_POST['tipo']);
                    $libro->setNumeroLibro($_POST['numero_libro']);
                    $libro->setAnioInicio($_POST['anio_inicio']);
                    
                    // Campos opcionales: Usamos una validación para aceptar nulos o vacíos
                    $libro->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);
                    $libro->setProvincia($_POST['provincia'] ?? null);
                    $libro->setDistrito($_POST['distrito'] ?? null);
                    $libro->setDescripcion($_POST['descripcion'] ?? null);
                    
                    $model = new LibrosRegistroModel();
                    $model->modificar($libro);

                    // Redirección limpia para evitar reenvío de formularios
                    header('Location: index.php?accion=libros');
                    exit;
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }

        public function guardar(){
            try {
                // Solo exigimos los campos NOT NULL
                if(isset($_POST['tipo']) && isset($_POST['numero_libro']) && isset($_POST['anio_inicio'])){
                    
                    $libro = new LibrosRegistro();
                    $libro->setTipo($_POST['tipo']);
                    $libro->setNumeroLibro($_POST['numero_libro']);
                    $libro->setAnioInicio($_POST['anio_inicio']);
                    
                    // Campos opcionales (incluyendo los del ALTER TABLE)
                    $libro->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);
                    $libro->setProvincia($_POST['provincia'] ?? null);
                    $libro->setDistrito($_POST['distrito'] ?? null);
                    $libro->setDescripcion($_POST['descripcion'] ?? null);

                    $model = new LibrosRegistroModel();
                    $model->guardar($libro);

                    // Redirección limpia hacia el directorio de libros
                    header('Location: index.php?accion=libros');
                    exit;
                } else {
                    // Si falla el POST o se accede directamente, recargamos la vista principal
                    $this->cargar();
                }
            } catch (Exception $e) {
                Logger::error($e);
            }
        }
    }
?>