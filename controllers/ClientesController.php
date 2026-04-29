<?php
require_once './models/Clientes.php';
require_once './models/ClientesModel.php';

class ClientesController {

    private $model;

    public function __construct() {
        $this->model = new ClientesModel();
    }

    public function inicioDashboard() {
        $pagina_actual = 'inicio';
        require './views/dashboard.php';
    }

    public function listarClientes() {
        $clientes = $this->model->cargar(); 
        $pagina_actual = 'clientes';
        require './views/clientes.php'; 
    }

    public function guardarCliente() {
    // 1. Verificamos que al menos los datos básicos existan
        if (isset($_POST['dni'], $_POST['nombres'])) {
            
            $cliente = $this->mapearDatosFormulario();
            $id_cliente = $_POST['id_cliente'] ?? '';

            if (!empty($id_cliente)) {
                // --- MODO EDICIÓN ---
                $cliente->setIdCliente($id_cliente);
                if ($this->model->modificarCliente($cliente)) {
                    header("Location: index.php?accion=clientes&msg=actualizado");
                    exit();
                } else {
                    $this->manejarError("Error al actualizar");
                }
            } else {
                // --- MODO NUEVO ---
                $idGenerado = $this->model->guardarCliente($cliente);
                if ($idGenerado !== null) {
                    header("Location: index.php?accion=clientes&msg=guardado");
                    exit();
                } else {
                    // Si el error es por DNI duplicado
                    if (strpos($this->model->ultimoError, '23505') !== false) {
                        $this->manejarError("El DNI ya se encuentra registrado.");
                    } else {
                        $this->manejarError("Error al guardar nuevo cliente");
                    }
                }
            }
        }
    }

    private function manejarError($mensaje) {
        // Redirigir con mensaje de error para evitar el pantallazo blanco
        header("Location: index.php?accion=clientes&msg=error&info=" . urlencode($mensaje));
        exit();
    }

    /**
     * Procesa la actualización de un cliente existente
     */
    public function modificarCliente() {
        if(isset($_POST['id_cliente']) && !empty($_POST['id_cliente'])) {
            $cliente = $this->mapearDatosFormulario();
            $cliente->setIdCliente($_POST['id_cliente']); // Asignamos el ID oculto del form

            if($this->model->modificarCliente($cliente)) {
                header("Location: index.php?accion=clientes&msg=actualizado");
                exit();
            } else {
                $this->manejarError("Error al actualizar cliente");
            }
        } else {
            $this->manejarError("Error: No se proporcionó un ID válido para modificar.");
        }
    }

    public function eliminarCliente() {
    if (isset($_GET['id'])) {
        if ($this->model->eliminarCliente($_GET['id'])) {
            // Redirigimos con el mensaje de éxito
            header("Location: index.php?accion=clientes&msg=eliminado");
            exit();
        } else {
            header("Location: index.php?accion=clientes&msg=error");
            exit();
        }
    }
}

    /**
     * Función interna para no repetir código de captura de datos $_POST
     */
    private function mapearDatosFormulario() {
        $cliente = new Clientes(); 
        $cliente->setDni($_POST['dni']);
        $cliente->setNombres($_POST['nombres']);
        $cliente->setApellidos($_POST['apellidos']);
        $cliente->setCorreo(!empty($_POST['correo']) ? $_POST['correo'] : null);
        $cliente->setCelular(!empty($_POST['celular']) ? $_POST['celular'] : null);
        $cliente->setArea(!empty($_POST['area']) ? $_POST['area'] : null);
        $cliente->setEstado($_POST['estado'] ?? 'Activo');
        return $cliente;
    }
}
?>


