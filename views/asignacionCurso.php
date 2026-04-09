<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Nuevo Curso - OEGPP</title>
    <link rel="stylesheet" href="public/dashStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="asignacion-page">

    <?php include 'includes/menu.php'; ?>

    <div class="container main-content" style="margin-top: 30px;">
        <div class="section-header">
            <h2><i class="fas fa-user-graduate" style="color: #10b981;"></i> Asignar Nuevo Curso</h2>
            <p>Registra la capacitación de un trabajador en un curso específico.</p>
        </div>

        <form action="index.php?accion=guardar_asignacion" method="POST" class="form-container">
            
            <fieldset>
                <legend><i class="fas fa-address-card"></i> 1. Selección de Participante y Curso</legend>
                
                <input type="hidden" name="es_nuevo_trabajador" id="es_nuevo_trabajador" value="0">

                <div class="form-row">
                    <div class="form-group" id="caja_selector">
                        <label for="trabajador_id">Trabajador (DNI - Nombre):</label>
                        <select name="trabajador_id" id="trabajador_id" required>
                            <option value="">-- Seleccione un trabajador --</option>
                            <?php if (!empty($trabajadores)): ?>
                                <?php foreach ($trabajadores as $t): ?>
                                    <option value="<?= $t['id_trabajador'] ?>">
                                        <?= htmlspecialchars($t['dni'] . ' - ' . $t['nombres'] . ' ' . $t['apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div style="text-align: right; margin-top: 8px;">
                            <button type="button" class="btn-icon btn-edit" onclick="toggleNuevoTrabajador(true)" style="background: #ecfdf5; color: #10b981; padding: 6px 12px; font-weight: 600;">
                                <i class="fas fa-user-plus"></i> Añadir participante nuevo
                            </button>
                        </div>
                    </div>

                    <div id="caja_nuevo_trabajador" style="display: none; width: 100%; border-left: 3px solid #10b981; padding-left: 15px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <h4 style="margin: 0; color: #10b981;">Registrar Nuevo Trabajador</h4>
                            <button type="button" class="btn-icon btn-delete" onclick="toggleNuevoTrabajador(false)" style="padding: 6px 12px; font-weight: 600;">
                                <i class="fas fa-times"></i> Cancelar nuevo
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
                            <div class="form-group">
                                <label>Celular (Opcional):</label>
                                <input type="text" name="nuevo_celular" id="nuevo_celular" placeholder="987654321">
                            </div>
                            <div class="form-group">
                                <label>Área (Opcional):</label>
                                <input type="text" name="nuevo_area" id="nuevo_area" placeholder="Ej: Logística">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="curso_id">Curso o Diplomado a asignar:</label>
                        <select name="curso_id" id="curso_id" required>
                            <option value="">-- Seleccione un curso --</option>
                            <?php if (!empty($cursos)): ?>
                                <?php foreach ($cursos as $c): ?>
                                    <option value="<?= $c['id_curso'] ?>">
                                        <?= htmlspecialchars($c['codigo_curso'] . ' - ' . $c['nombre_curso']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
                            <?php if (!empty($libros)): ?>
                                <?php foreach ($libros as $l): ?>
                                    <option value="<?= $l['id_libro'] ?>">
                                        <?= htmlspecialchars(ucfirst($l['tipo']) . ' - Libro N° ' . $l['numero_libro'] . ' (' . $l['anio_inicio'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
                        <label for="fecha_inicio">Fecha de Inicio (Opcional):</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio">
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin (Opcional):</label>
                        <input type="date" name="fecha_fin" id="fecha_fin">
                    </div>

                    <div class="form-group">
                        <label for="fecha_emision">Fecha de Emisión (Obligatorio):</label>
                        <input type="date" name="fecha_emision" id="fecha_emision" required>
                    </div>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="window.location.href='index.php?accion=trabajadores'">
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