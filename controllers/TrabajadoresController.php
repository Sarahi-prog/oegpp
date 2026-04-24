<?php
require_once './models/TrabajadoresModel.php';

class TrabajadoresController {

    public function inicioDashboard() {
        $pagina_actual = 'inicio';
        require './views/dashboard.php';
    }

    public function listarTrabajadores() {
        $model = new TrabajadoresModel();
        $trabajadores = $model->cargar(); 
        
        $pagina_actual = 'trabajadores'; 
        require './views/trabajadores.php'; 
    }
    public function guardarTrabajador() {
        try {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $trabajador = $this->mapearDatosFormulario();
                $model = new TrabajadoresModel();
                $model->guardar($trabajador);
            }
            header("Location: index.php?accion=trabajadores");
            exit();
        } catch (Exception $e) {
            Logger::error($e);
        }
    }

    public function eliminar() {
        try {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $model = new TrabajadoresModel();
                $model->eliminar($id);
            }
            header("Location: index.php?accion=trabajadores");
            exit();
        } catch (Exception $e) {
            Logger::error($e);
            echo "Error al eliminar: " . $e->getMessage();
        }
    }


    public function modificarTrabajador() {
        try {
            $trabajador = new Trabajadores();
            $trabajador->setIdTrabajador($_POST['id_trabajador']);
            $trabajador->setDni($_POST['dni']);
            $trabajador->setNombres($_POST['nombres']);
            $trabajador->setApellidos($_POST['apellidos']);
            $trabajador->setCorreo($_POST['correo']);
            $trabajador->setCelular($_POST['celular']);
            $trabajador->setArea($_POST['area']);
            $trabajador->setEstado($_POST['estado']);

            $model = new TrabajadoresModel();
            $model->actualizar($trabajador);

            header("Location: index.php?accion=trabajadores");
            exit();
        } catch (Exception $e) {
            echo "Error al modificar: " . $e->getMessage();
        }
    }

    private function mapearDatosFormulario() {
        $trabajador = new Trabajadores(); 
        $trabajador->setDni($_POST['dni']);
        $trabajador->setNombres($_POST['nombres']);
        $trabajador->setApellidos($_POST['apellidos']);
        $trabajador->setCorreo(!empty($_POST['correo']) ? $_POST['correo'] : null);
        $trabajador->setCelular(!empty($_POST['celular']) ? $_POST['celular'] : null);
        $trabajador->setArea(!empty($_POST['area']) ? $_POST['area'] : null);
        $trabajador->setEstado($_POST['estado'] ?? 'Activo');
        return $trabajador;
    }

    // --- AQUÍ CARGAMOS LOS DATOS REALES PARA EL FORMULARIO ---
    public function mostrarFormularioAsignacion() {
        $model = new TrabajadoresModel();
        $trabajadores = $model->cargar();
        $cursos = $model->cargarCursos();
        $libros = $model->cargarLibros();

        $pagina_actual = 'trabajadores';
        require './views/asignacionCurso.php';
    }

    // --- PROCESAR EL GUARDADO ---
    public function procesarAsignacion() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $model = new TrabajadoresModel();
            
            // 1. Trabajador
            $es_nuevo = $_POST['es_nuevo_trabajador'] ?? '0';
            $trabajador_id_final = null;

            if ($es_nuevo === '1') {
                $trabajador_id_final = $model->guardarNuevoTrabajador(
                    $_POST['nuevo_dni'], $_POST['nuevo_nombres'], $_POST['nuevo_apellidos'], 
                    $_POST['nuevo_correo'], $_POST['nuevo_celular'], $_POST['nuevo_area']
                );
            } else {
                $trabajador_id_final = $_POST['trabajador_id'];
            }

            // 2. Guardar en registros_capacitacion
            $exito = $model->asignarCurso(
                $trabajador_id_final, 
                $_POST['curso_id'], 
                $_POST['libro_id'], 
                $_POST['registro'], 
                $_POST['horas_realizadas'], 
                $_POST['fecha_inicio'], 
                $_POST['fecha_fin'], 
                $_POST['fecha_emision'], 
                $_POST['folio']
            );

            // 3. Volver a la tabla de trabajadores
            if ($exito) {
                header("Location: index.php?accion=trabajadores");
                exit();
            }
        }
    }
}
?>