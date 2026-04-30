<?php
require_once './models/LibrosRegistro.php';
require_once './models/LibrosRegistroModel.php';

class LibrosRegistroController {

    private $model;

    public function __construct() {
        $this->model = new LibrosRegistroModel();
    }

    // 🔹 LISTAR (equivalente a listarClientes)
    public function cargar() {
        $libros = $this->model->cargar();
        $pagina_actual = 'libros';
        require './views/libros_registro.php';
    }

    // 🔹 GUARDAR Y EDITAR (MISMA LÓGICA QUE CLIENTES)
    public function guardar() {
        if (isset($_POST['tipo'], $_POST['numero_libro'], $_POST['anio_inicio'])) {

            $libro = $this->mapearDatosFormulario();
            $id_libro = $_POST['id_libro'] ?? '';

            if (!empty($id_libro)) {
                // --- MODO EDICIÓN ---
                $libro->setIdLibro($id_libro);

                if ($this->model->modificarLibro($libro)) {
                    header("Location: index.php?accion=libros_registro&msg=actualizado");
                    exit();
                } else {
                    $this->manejarError("Error al actualizar libro");
                }

            } else {
                // --- MODO NUEVO ---
                $idGenerado = $this->model->guardarLibro($libro);

                if ($idGenerado !== null) {
                    header("Location: index.php?accion=libros_registro&msg=guardado");
                    exit();
                } else {
                    $this->manejarError("Error al guardar libro");
                }
            }
        }
    }

    // 🔹 MODIFICAR (opcional, igual que clientes)
    public function modificar() {
        if(isset($_POST['id_libro']) && !empty($_POST['id_libro'])) {

            $libro = $this->mapearDatosFormulario();
            $libro->setIdLibro($_POST['id_libro']);

            if($this->model->modificarLibro($libro)) {
                header("Location: index.php?accion=libros_registro&msg=actualizado");
                exit();
            } else {
                $this->manejarError("Error al actualizar libro");
            }

        } else {
            $this->manejarError("ID no válido");
        }
    }

    // 🔹 ELIMINAR
    public function eliminar() {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];

            if($this->model->eliminarLibro($id)) {
                header("Location: index.php?accion=libros_registro&msg=eliminado");
                exit();
            } else {
                $this->manejarError("Error al eliminar libro");
            }
        }
    }

    // 🔹 MANEJO DE ERRORES (igual que clientes)
    private function manejarError($mensaje) {
        header("Location: index.php?accion=libros_registro&msg=error&info=" . urlencode($mensaje));
        exit();
    }

    // 🔹 MAPEAR DATOS (CLAVE, igual que clientes)
    private function mapearDatosFormulario() {
        $libro = new LibrosRegistro();

        $libro->setTipo($_POST['tipo']);
        $libro->setNumeroLibro($_POST['numero_libro']);
        $libro->setAnioInicio($_POST['anio_inicio']);

        $libro->setFechaFin(!empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null);
        $libro->setDistrito(!empty($_POST['distrito']) ? $_POST['distrito'] : null);
        $libro->setProvincia(!empty($_POST['provincia']) ? $_POST['provincia'] : null);
        $libro->setDescripcion(!empty($_POST['descripcion']) ? $_POST['descripcion'] : null);

        return $libro;
    }
}
?>