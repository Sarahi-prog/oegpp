<?php
require_once("../controllers/TrabajadoresController.php");

$controller = new TrabajadoresController();
// $trabajadores = $controller->Cargar(); // Descomenta cuando tu controlador retorne la lista

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lógica para guardar el trabajador...
    // $mensaje = "✅ Registrado correctamente";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Nuevo Curso - OEGPP</title>
    <link rel="stylesheet" href="../public/asignacion.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos específicos para los botones pequeños dentro del formulario */
        .btn-sm-action {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-add-new {
            background-color: #f0fdf4;
            color: #10b981;
            border: 1px solid #10b981;
        }
        .btn-add-new:hover {
            background-color: #10b981;
            color: white;
        }
        .btn-cancel-new {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #ef4444;
        }
        .btn-cancel-new:hover {
            background-color: #ef4444;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container main-content">
        <div class="section-header" style="margin-top: 30px;">
            <h2><i class="fas fa-user-graduate" style="color: #10b981;"></i> Asignar Nuevo Curso</h2>
            <p>Registra la capacitación de un trabajador en un curso específico.</p>
        </div>

        <form action="../controllers/procesarAsignacion.php" method="POST" class="form-container">
            
            <fieldset>
                <legend><i class="fas fa-address-card"></i> 1. Selección de Participante y Curso</legend>
                
                <input type="hidden" name="es_nuevo_trabajador" id="es_nuevo_trabajador" value="0">

                <div class="form-row">
                    <div class="form-group" id="caja_selector">
                        <label for="trabajador_id">Trabajador (DNI - Nombre):</label>
                        <select name="trabajador_id" id="trabajador_id" required>
                            <option value="">-- Seleccione un trabajador --</option>
                            <option value="1">72345678 - Juan Perez</option> 
                        </select>
                        <div style="text-align: right; margin-top: 8px;">
                            <button type="button" class="btn-sm-action btn-add-new" onclick="toggleNuevoTrabajador(true)">
                                <i class="fas fa-user-plus"></i> Añadir participante
                            </button>
                        </div>
                    </div>

                    <div id="caja_nuevo_trabajador" style="display: none; width: 100%; border-left: 3px solid #10b981; padding-left: 15px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0; color: #10b981;">Registrar Nuevo Trabajador</h4>
                            <button type="button" class="btn-sm-action btn-cancel-new" onclick="toggleNuevoTrabajador(false)">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>DNI:</label>
                                <input type="text" name="nuevo_dni" id="nuevo_dni" maxlength="15" placeholder="Ej: 71234567">
                            </div>
                            <div class="form-group">
                                <label>Nombres:</label>
                                <input type="text" name="nuevo_nombres" id="nuevo_nombres" placeholder="Ej: Ana María">
                            </div>
                            <div class="form-group">
                                <label>Apellidos:</label>
                                <input type="text" name="nuevo_apellidos" id="nuevo_apellidos" placeholder="Ej: Torres">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Correo (Opcional):</label>
                                <input type="email" name="nuevo_correo" id="nuevo_correo" placeholder="ana@email.com">
                            </div>
                            
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="curso_id">Curso a asignar:</label>
                        <select name="curso_id" id="curso_id" required>
                            <option value="">-- Seleccione un curso --</option>
                            <option value="1">C001 - Gestión Pública Básica</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><i class="fas fa-book"></i> 2. Datos del Registro y Certificación</legend>

                <div class="form-row">
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="libro_id">Libro de Registro:</label>
                        <select name="libro_id" id="libro_id" required>
                            <option value="">-- Seleccione un libro --</option>
                            <option value="1">Libro #1 (Certificados 2024)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="registro">Número de Registro (Orden):</label>
                        <input type="number" name="registro" id="registro" required placeholder="Ej: 105">
                    </div>

                    <div class="form-group">
                        <label for="folio">Folio:</label>
                        <input type="text" name="folio" id="folio" placeholder="Ej: 0045-A">
                    </div>

                    <div class="form-group">
                        <label for="horas_realizadas">Horas Realizadas:</label>
                        <input type="number" name="horas_realizadas" id="horas_realizadas" required placeholder="Ej: 120">
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend><i class="fas fa-calendar-alt"></i> 3. Fechas</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio">
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin">
                    </div>

                    <div class="form-group">
                        <label for="fecha_emision">Fecha de Emisión (Requerido):</label>
                        <input type="date" name="fecha_emision" id="fecha_emision" required>
                    </div>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="window.location.href='index.php'">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary-green">
                    <i class="fas fa-save"></i> Guardar Asignación
                </button>
            </div>
        </form>
    </div>

    <script>
    function toggleNuevoTrabajador(mostrar) {
        const cajaSelector = document.getElementById('caja_selector');
        const cajaNuevo = document.getElementById('caja_nuevo_trabajador');
        const inputOculto = document.getElementById('es_nuevo_trabajador');
        
        const selectTrabajador = document.getElementById('trabajador_id');
        const inputDNI = document.getElementById('nuevo_dni');
        const inputNombres = document.getElementById('nuevo_nombres');
        const inputApellidos = document.getElementById('nuevo_apellidos');

        if (mostrar) {
            cajaSelector.style.display = 'none';
            cajaNuevo.style.display = 'block';
            inputOculto.value = '1'; 
            
            selectTrabajador.removeAttribute('required');
            inputDNI.setAttribute('required', 'required');
            inputNombres.setAttribute('required', 'required');
            inputApellidos.setAttribute('required', 'required');
        } else {
            cajaSelector.style.display = 'block';
            cajaNuevo.style.display = 'none';
            inputOculto.value = '0'; 
            
            selectTrabajador.setAttribute('required', 'required');
            inputDNI.removeAttribute('required');
            inputNombres.removeAttribute('required');
            inputApellidos.removeAttribute('required');
            
            inputDNI.value = '';
            inputNombres.value = '';
            inputApellidos.value = '';
        }
    }
    </script>

</body>
</html>