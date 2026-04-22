<?php
require_once 'models/RegistroCapacitacion.php';
require_once 'models/RegistroCapacitacionModel.php';
require_once 'helpers/loggers.php';

class RegistroCapacitacionController{
    public function cargar(){
    try {
        $model = new RegistroCapacitacionModel();
        $capacitaciones = $model->cargar();
        require './views/registros_capacitacion.php';
    } catch (Exception $e) {
        error_log("Error en RegistroCapacitacion: " . $e->getMessage());
        // Pasamos un mensaje amigable a la vista
        $error_sistema = "Ocurrió un problema al cargar los datos. Por favor, contacte al administrador.";
        require './views/registros_capacitacion.php'; 
    }
}

    public function guardar(){
        try {
            if(isset($_POST['cliente_id']) && isset($_POST['curso_id']) && isset($_POST['libro_id']) && isset($_POST['registro']) && isset($_POST['horas_realizadas']) && isset($_POST['fecha_emision']) && isset($_POST['folio'])){
                $registrocapacitacion = new RegistroCapacitacion();
                $registrocapacitacion->setClienteId($_POST['cliente_id']);
                $registrocapacitacion->setCursoId($_POST['curso_id']);
                $registrocapacitacion->setLibroId($_POST['libro_id']);
                $registrocapacitacion->setRegistro($_POST['registro']);
                $registrocapacitacion->setHorasRealizadas($_POST['horas_realizadas']);
                $registrocapacitacion->setFechaInicio($_POST['fecha_inicio'] ?? null);
                $registrocapacitacion->setFechaFin($_POST['fecha_fin'] ?? null);
                $registrocapacitacion->setFechaEmision($_POST['fecha_emision']);
                $registrocapacitacion->setFolio($_POST['folio']);

                $model = new RegistroCapacitacionModel();
                $model->guardar($registrocapacitacion);

                header('Location: index.php?accion=registros_capacitacion');
                exit;
            }

            $this->cargar();
        } catch (Exception $e) {
            error_log("Error en RegistroCapacitacion: " . $e->getMessage());
        }
    }

    public function modificar(){
        try {
            if(isset($_POST['id_registro']) && isset($_POST['trabajador_id']) && isset($_POST['curso_id']) && isset($_POST['libro_id']) && isset($_POST['registro']) && isset($_POST['horas_realizadas']) && isset($_POST['fecha_emision']) && isset($_POST['folio'])){
                $registrocapacitacion = new RegistroCapacitacion();
                $registrocapacitacion->setIdRegistro($_POST['id_registro']);
                $registrocapacitacion->setClienteId($_POST['cliente_id']);
                $registrocapacitacion->setCursoId($_POST['curso_id']);
                $registrocapacitacion->setLibroId($_POST['libro_id']);
                $registrocapacitacion->setRegistro($_POST['registro']);
                $registrocapacitacion->setHorasRealizadas($_POST['horas_realizadas']);
                $registrocapacitacion->setFechaInicio($_POST['fecha_inicio'] ?? null);
                $registrocapacitacion->setFechaFin($_POST['fecha_fin'] ?? null);
                $registrocapacitacion->setFechaEmision($_POST['fecha_emision']);
                $registrocapacitacion->setFolio($_POST['folio']);

                $model = new RegistroCapacitacionModel();
                $model->modificar($registrocapacitacion);
            }
        } catch (Exception $e) {
            error_log("Error en RegistroCapacitacion: " . $e->getMessage());
        }
    }

        public function buscar() {
            try {
                $dni = $_GET['dni'] ?? '';
                
                if (!empty($dni)) {
                    $model = new RegistroCapacitacionModel();
                    $capacitaciones = $model->buscarPorDni($dni);
                } else {
                    $capacitaciones = [];
                }

                require './views/registros_capacitacion.php';
            } catch (Exception $e) {
                error_log("Error al buscar capacitaciones por DNI: " . $e->getMessage());
                $error_sistema = "Ocurrió un problema al buscar. Por favor, contacte al administrador.";
                require './views/registros_capacitacion.php';
            }
        }

} 
?>