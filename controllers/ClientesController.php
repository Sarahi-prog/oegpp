<?php
require_once './models/Clientes.php';
require_once './models/ClientesModel.php';

class ClientesController {

    public function inicioDashboard() {
        $pagina_actual = 'inicio';
        require './views/dashboard.php';
    }

    public function listarClientes() {
        $model = new ClientesModel();
        $clientes = $model->cargar(); 
        
        $pagina_actual = 'trabajadores';
        $trabajadores = $clientes;
        require './views/clientes.php'; 
    }


    public function guardarCliente() {
        if(isset($_POST['dni']) && isset($_POST['nombres']) && isset($_POST['apellidos'])) {
            
            // 1. Requerimos la clase Cliente que creaste hace un rato
            require_once './models/Clientes.php'; 
            require_once './models/ClientesModel.php';
            
            // 2. Creamos el OBJETO y lo llenamos con los datos del formulario
            $cliente = new Clientes(); 
            $cliente->setDni($_POST['dni']);
            $cliente->setNombres($_POST['nombres']);
            $cliente->setApellidos($_POST['apellidos']);
            
            $correo = isset($_POST['correo']) && !empty($_POST['correo']) ? $_POST['correo'] : null;
            $cliente->setCorreo($correo);

            $celular = isset($_POST['celular']) && !empty($_POST['celular']) ? $_POST['celular'] : null;
            $cliente->setCelular($celular);

            $area = isset($_POST['area']) && !empty($_POST['area']) ? $_POST['area'] : null;
            $cliente->setArea($area);

            $estado = isset($_POST['estado']) && !empty($_POST['estado']) ? $_POST['estado'] : 'Activo';
            $cliente->setEstado($estado);

            // 3. Instanciamos el modelo
            $model = new ClientesModel();
            
            // 4. ¡LA MAGIA AQUÍ! Le pasamos UN SOLO argumento: el objeto completo
            $idGenerado = $model->guardarNuevoCliente($cliente);

            // 5. Redirigimos
            if($idGenerado) {
                header("Location: index.php?accion=trabajadores");
                exit();
            } else {
                echo "Error al guardar en la base de datos.";
            }

        } else {
            echo "Error: Faltan datos obligatorios (DNI, Nombres o Apellidos).";
        }
    }

    
}