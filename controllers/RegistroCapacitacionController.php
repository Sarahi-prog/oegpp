<?php
require_once 'models/RegistroCapacitacion.php';
require_once 'models/RegistroCapacitacionModel.php';
// Asegúrate de tener modelos para estos o usar el mismo modelo general
require_once __DIR__ . '/../models/ClientesModel.php';
require_once __DIR__ . '/../models/CursosModel.php';
require_once __DIR__ . '/../models/LibrosRegistroModel.php';

class RegistroCapacitacionController {
    
    public function cargar() {
        try {
            $model = new RegistroCapacitacionModel();
            
            // 1. Cargamos los registros de la tabla
            $registros = $model->cargar();
            
            // 2. IMPORTANTE: Cargar datos para los select del formulario
            // Si no tienes estos modelos, puedes crear métodos en RegistroCapacitacionModel
            $clientes = $model->obtenerTodosClientes(); 
            $cursos = $model->obtenerTodosCursos();
            $libros = $model->obtenerTodosLibros();

            require './views/registros_capacitacion.php';
        } catch (Exception $e) {
            error_log("Error en RegistroCapacitacion: " . $e->getMessage());
            $error_sistema = "Error al cargar los datos.";
            require './views/registros_capacitacion.php'; 
        }
    }

    public function guardar() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $registro = new RegistroCapacitacion();
                $registro->setClienteId($_POST['cliente_id']);
                $registro->setCursoId($_POST['curso_id']);
                $registro->setLibroId($_POST['libro_id']);
                $registro->setRegistro($_POST['registro']);
                $registro->setHorasRealizadas($_POST['horas_realizadas']);
                $registro->setFechaInicio($_POST['fecha_inicio'] ?: null);
                $registro->setFechaFin($_POST['fecha_fin'] ?: null);
                $registro->setFechaEmision($_POST['fecha_emision']);
                $registro->setFolio($_POST['folio']);
                $registro->setEstado($_POST['estado'] ?? 'Activo');

                $model = new RegistroCapacitacionModel();
                
                // Si viene un ID, editamos; si no, creamos
                if (!empty($_POST['id_registro'])) {
                    $registro->setIdRegistro($_POST['id_registro']);
                    $model->modificar_registro($registro);
                } else {
                    $model->guardar($registro);
                }

                header('Location: index.php?accion=registros_capacitacion');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error al guardar: " . $e->getMessage());
            $this->cargar();
        }
    }
}