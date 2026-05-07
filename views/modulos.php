<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Módulos - OEGPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/modulosStyles.css?v=<?= time(); ?>">
</head> 
<body>
    <?php include 'includes/menu.php'; ?>

    <div class="container main-content">

        <!-- ── CABECERA (idéntica a cursos) ── -->
        <div class="header-acciones">
            <div class="titulo-con-boton">
                <button onclick="toggleRegistro()" class="btn-hamburguesa" title="Mostrar/Ocultar Formulario">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="directorio-title-container">
                    <h2><i class="fas fa-layer-group"></i> Gestión de Módulos</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra la estructura académica y cronogramas por programa.</p>
                </div>
            </div>

            <!-- Breadcrumb: volver a cursos -->
            <a href="index.php?accion=cursos" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver a Cursos
            </a>
        </div>

        <div class="dashboard-wrapper">

            <!-- ── PANEL LATERAL (formulario) ── -->
            <div id="seccionRegistro" data-modulo="modulos">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;">
                        <i class="fas fa-puzzle-piece"></i> Datos del Módulo
                    </h3>

                    <form id="formModulo" action="index.php?accion=guardar_modulo" method="POST">
                        <input type="hidden" name="id_modulo" id="id_modulo_form" value="">

                        <div class="form-vertical-stack">

                            <!-- Selector de curso -->
                            <div class="field-group">
                                <label>Programa / Curso Base</label>
                                <select name="curso_id" id="curso_id_form" class="form-select" required>
                                    <option value="">Seleccione el curso...</option>
                                    <?php if (!empty($cursos_disponibles)): ?>
                                        <?php foreach ($cursos_disponibles as $curso): ?>
                                            <option value="<?= $curso->getIdCurso() ?>"
                                                <?= (isset($curso_preseleccionado) && $curso_preseleccionado == $curso->getIdCurso()) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($curso->getCodigoCurso() . ' - ' . $curso->getNombreCurso()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Nombre del módulo -->
                            <div class="field-group">
                                <label>Nombre del Módulo</label>
                                <input type="text" name="nombre_modulo" id="nombre_modulo_form"
                                       required placeholder="Ej. Módulo I: Fundamentos...">
                            </div>

                            <!-- Horas -->
                            <div class="field-group">
                                <label>Horas Académicas</label>
                                <input type="number" name="horas" id="horas_form"
                                       required min="1" placeholder="Ej. 24">
                            </div>

                            <!-- Fechas -->
                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio_form">
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin_form">
                                </div>
                            </div>
                            <!-- Botones acción -->
                            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 5px;">
                                <button type="submit" id="btn-submit-form" class="btn btn-primary-green" style="flex: 1;">
                                    <i class="fas fa-save"></i> <span id="btn-submit-texto">Guardar Módulo</span>
                                </button>
                                <button type="button" id="btn-cancelar"
                                        onclick="cancelarEdicion()"
                                        class="btn btn-secondary"
                                        style="display: none; background-color: #64748b; color: white; border: none; padding: 10px 14px; border-radius: 12px; cursor: pointer;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <!-- /panel -->

            <!-- ── SECCIÓN TABLA ── -->
            <div class="table-section">

                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorModulos" class="search-input"
                               placeholder="Buscar por módulo o curso...">
                    </div>
                    <button class="btn-exportar" onclick="exportarModulos()">
                        <i class="fas fa-file-export"></i>
                        <span>Exportar Datos</span>
                    </button>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="data-table" id="tablaModulos">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CURSO APLICADO</th>
                                    <th>NOMBRE DEL MÓDULO</th>
                                    <th>HORAS</th>
                                    <th>CRONOGRAMA</th>
                                    <th style="text-align: center;">ESTADO</th>
                                    <th style="text-align: center;" class="acciones">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaModulos">

                                <?php if (!empty($modulos)): $i = 1; ?>
                                    <?php foreach ($modulos as $mod):
                                        $estadoActivo = ($mod->getEstado() == 1);

                                        // Buscar nombre del curso asociado
                                        $nombreCurso = 'Sin curso';
                                        if (!empty($cursos_disponibles)) {
                                            foreach ($cursos_disponibles as $c) {
                                                if ($c->getIdCurso() == $mod->getCursoId()) {
                                                    $nombreCurso = $c->getCodigoCurso() . ' - ' . $c->getNombreCurso();
                                                    break;
                                                }
                                            }
                                        }

                                        $datosJson = htmlspecialchars(json_encode([
                                            'id_modulo'    => $mod->getIdModulo(),
                                            'curso_id'     => $mod->getCursoId(),
                                            'nombre_modulo'=> $mod->getNombreModulo(),
                                            'horas'        => $mod->getHoras(),
                                            'fecha_inicio' => $mod->getFechaInicio(),
                                            'fecha_fin'    => $mod->getFechaFin()
                                        ]), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr class="fila-modulo">
                                        <td class="id-column"><?= $i++ ?></td>

                                        <!-- Nombre del curso (no solo el ID) -->
                                        <td>
                                            <span class="curso-referencia">
                                                <?= htmlspecialchars($nombreCurso) ?>
                                            </span>
                                        </td>

                                        <td style="font-weight: 600; color: var(--text-main);">
                                            <?= htmlspecialchars($mod->getNombreModulo()) ?>
                                        </td>

                                        <td>
                                            <i class="far fa-clock" style="color: #94a3b8; margin-right: 5px;"></i>
                                            <?= htmlspecialchars($mod->getHoras()) ?> h
                                        </td>

                                        <td style="font-size: 0.85rem; color: var(--text-muted);">
                                            <?php
                                                $fi = $mod->getFechaInicio();
                                                $ff = $mod->getFechaFin();
                                                echo ($fi ? date('d/m/Y', strtotime($fi)) : '---')
                                                   . ' → '
                                                   . ($ff ? date('d/m/Y', strtotime($ff)) : '---');
                                            ?>
                                        </td>

                                        <td style="text-align: center;">
                                            <label class="switch">
                                                <input type="checkbox" <?= $estadoActivo ? 'checked' : '' ?>
                                                       onchange="confirmarEstadoModulo(this, <?= $mod->getIdModulo() ?>)">
                                                <span class="slider"></span>
                                            </label>
                                        </td>

                                        <td style="text-align: center; white-space: nowrap;">
                                            <button class="btn-icon btn-edit" title="Editar"
                                                    onclick='editarModulo(<?= $datosJson ?>)'>
                                                <i class="fas fa-edit" style="color: #4a90e2;"></i>
                                            </button>
                                            <button class="btn-icon btn-delete" title="Eliminar"
                                                    onclick="eliminarModulo(<?= $mod->getIdModulo() ?>)">
                                                <i class="fas fa-trash" style="color: #e24a4a;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 4rem 2rem;">
                                            <div style="display: flex; flex-direction: column; align-items: center; opacity: 0.7;">
                                                <i class="fas fa-layer-group" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                                <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin módulos registrados</h4>
                                                <p style="color: #64748b; margin-top: 8px; font-size: 0.9rem;">Usa el formulario para agregar el primer módulo.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- /table-section -->

        </div>
        <!-- /dashboard-wrapper -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="public/universalScript.js?v=<?= time(); ?>"></script>
    <script src="public/modulosScript.js?v=<?= time(); ?>"></script>
</body>
</html>