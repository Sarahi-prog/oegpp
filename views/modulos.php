<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Módulos - OEGPP</title>
    <link rel="stylesheet" href="public/dashStyles.css?v=<?php echo time(); ?>">   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'includes/menu.php'; ?>

    <div class="container main-content" style="margin-top: 30px;">
        
        <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
            <button class="btn btn-primary-green" onclick="toggleRegistro()" id="mainActionButton">
                <i class="fas fa-plus-circle"></i> Nuevo Módulo
            </button>
        </div>

        <section id="seccionRegistro">
            <div class="form-content-inner">
                <i class="fas fa-chevron-up btn-close-slider" onclick="toggleRegistro()"></i>
                <div class="form-header-inner">
                    <h2><i class="fas fa-cubes" style="color: #10b981;"></i> Registrar Módulo</h2>
                </div>
                
                <form id="formModulo" action="index.php?accion=guardar_modulo" method="POST" class="form-inline-grid">
                    
                    <div class="field-group">
                        <label for="curso_id">Curso Académico</label>
                        <select name="curso_id" id="curso_id" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                            <option value="">Seleccione un curso...</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?= $curso['id_curso'] ?>"><?= $curso['nombre_curso'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="nombre_modulo">Nombre del Módulo</label>
                        <input type="text" name="nombre_modulo" id="nombre_modulo" placeholder="Ej: Introducción al Derecho" required>
                    </div>

                    <div class="field-group">
                        <label for="horas">Horas Lectivas</label>
                        <input type="number" name="horas" id="horas" placeholder="Cant. Horas" required>
                    </div>

                    <div class="field-group">
                        <label for="fecha_inicio">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio">
                    </div>

                    <div class="field-group">
                        <label for="fecha_fin">Fecha Fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin">
                    </div>
                    
                    <div class="botones-form">
                        <button type="submit" class="btn-save-ajax">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn-cancel" onclick="toggleRegistro()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <div class="directorio-title-container">
            <h2><i class="fas fa-layer-group" style="color: #10b981;"></i> Listado de Módulos</h2>
            <p style="color: #64748b; margin-top: 5px;">Visualiza los módulos desglosados por curso y sus respectivas fechas.</p>
        </div>

        <div class="search-bar">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscadorTabla" placeholder="Buscar por nombre de módulo o curso..." class="search-input">
            </div>
            <button class="btn btn-outline" onclick="ejecutarBusquedaManual()">
                <i class="fas fa-search"></i> Buscar
            </button>
            <button class="btn btn-outline"><i class="fas fa-file-pdf"></i> Reporte</button>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table" id="tablaModulos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Módulo</th>
                            <th>Curso</th>
                            <th>Horas</th>
                            <th>Periodo (Inicio - Fin)</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($modulos)): ?>
                            <?php foreach ($modulos as $m): ?>
                            <tr class="fila-modulo">
                                <td><?= $m['id_modulo'] ?></td>
                                <td><strong><?= $m['nombre_modulo'] ?></strong></td>
                                <td><span style="color: #64748b; font-size: 0.9rem;"><?= $m['nombre_curso'] ?></span></td>
                                <td><?= $m['horas'] ?> hrs</td>
                                <td>
                                    <small><i class="far fa-calendar-alt"></i> <?= $m['fecha_inicio'] ?? 'N/A' ?></small> <br>
                                    <small><i class="far fa-calendar-check"></i> <?= $m['fecha_fin'] ?? 'N/A' ?></small>
                                </td>
                                <td class="text-right">
                                    <button class="btn-icon btn-edit"><i class="fas fa-pen"></i></button>
                                    <button class="btn-icon btn-delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center;">No hay módulos registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <p>Total: <span id="displayCount" class="highlight-green"><?= count($modulos) ?></span> módulos en el sistema.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputBusqueda = document.getElementById('buscadorTabla');
            const filas = document.querySelectorAll('#tablaModulos tbody tr.fila-modulo');
            const contadorVisible = document.getElementById('displayCount');

            if(!inputBusqueda) return;

            inputBusqueda.addEventListener('keyup', function(e) {
                const texto = e.target.value.toLowerCase();
                let visibles = 0;

                filas.forEach(fila => {
                    const contenidoFila = fila.textContent.toLowerCase();
                    if(contenidoFila.includes(texto)) {
                        fila.classList.remove('oculto-por-busqueda');
                        visibles++;
                    } else {
                        fila.classList.add('oculto-por-busqueda');
                    }
                });

                if(contadorVisible) contadorVisible.textContent = visibles;
            });
        });

        function toggleRegistro() {
            const seccion = document.getElementById('seccionRegistro');
            const btnPrincipal = document.getElementById('mainActionButton');
            seccion.classList.toggle('abierto');

            if (seccion.classList.contains('abierto')) {
                btnPrincipal.innerHTML = '<i class="fas fa-times"></i> Cerrar Registro';
                btnPrincipal.style.backgroundColor = '#64748b';
            } else {
                btnPrincipal.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Módulo';
                btnPrincipal.style.backgroundColor = '';
            }
        }

        function ejecutarBusquedaManual() {
            const input = document.getElementById('buscadorTabla');
            input.dispatchEvent(new Event('keyup'));
        }
    </script>
</body>
</html>