<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Notas - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/notasStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head> 
<body>
    <?php include 'includes/menu.php'; ?>

    <div class="container main-content">

        <div class="header-acciones">
            <div class="titulo-con-boton">
                <button onclick="toggleRegistro()" class="btn-hamburguesa" title="Mostrar/Ocultar Formulario">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="directorio-title-container">
                    <h2>
                        <svg width="36" height="36" viewBox="0 0 60 80" style="flex-shrink:0;" xmlns="http://www.w3.org/2000/svg">
                            <rect x="20" y="0" width="8" height="30" rx="2" fill="#00c853"/>
                            <rect x="32" y="0" width="8" height="30" rx="2" fill="#00a946"/>
                            <circle cx="30" cy="52" r="28" fill="#e8f5e9" stroke="#00c853" stroke-width="2"/>
                            <circle cx="30" cy="52" r="22" fill="none" stroke="#00c853" stroke-width="0.8" stroke-dasharray="3,2"/>
                            <text x="30" y="45" font-family="Inter,sans-serif" font-size="18" font-weight="700" fill="#00c853" text-anchor="middle">20</text>
                            <polygon points="30,56 31.5,60 36,60 32.5,62.5 34,66.5 30,64 26,66.5 27.5,62.5 24,60 28.5,60" fill="#00c853"/>
                        </svg>
                        Registro de Calificaciones
                    </h2>
                    <p>Asignación de notas por módulo a los trabajadores.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">

            <!-- PANEL LATERAL: FORMULARIO -->
            <div id="seccionRegistro">
                <div class="side-panel">
                    <h3><i class="fas fa-pen-nib"></i> Calificar</h3>
                    <form id="formNotasAjax" action="index.php?accion=guardar_nota" method="POST">
                        <div class="form-vertical-stack">

                            <input type="hidden" name="id_nota" id="id_nota_form" value="">

                            <div class="field-group">
                                <label>Trabajador</label>
                                <select name="trabajador_id" id="trabajador_id" class="form-select" required>
                                    <option value="">Seleccione trabajador...</option>
                                    <?php if (!empty($clientes)): foreach ($clientes as $c): ?>
                                    <option value="<?= $c->id_cliente ?>">
                                        <?= htmlspecialchars($c->nombres . ' ' . $c->apellidos) ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>

                            <div class="field-group">
                                <label>Módulo</label>
                                <select name="modulo_id" id="modulo_id" class="form-select" required>
                                    <option value="">Seleccione módulo...</option>
                                    <?php if (!empty($modulos)): foreach ($modulos as $m): ?>
                                    <option value="<?= $m->getIdModulo() ?>">
                                        <?= htmlspecialchars($m->getNombreModulo()) ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>

                            <div class="field-group">
                                <label>Nota Final (0 - 20)</label>
                                <input type="number" name="nota" id="nota_input"
                                       step="0.01" min="0" max="20"
                                       placeholder="0.00" required>
                            </div>

                            <div class="field-group">
                                <label>Fecha de Registro</label>
                                <input type="date" name="fecha_registro" id="fecha_registro"
                                       value="<?= date('Y-m-d') ?>">
                            </div>

                            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 5px;">
                                <button type="submit" id="btn-submit-form" class="btn btn-primary-green" style="flex: 1; justify-content: center;">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Registrar Nota</span>
                                </button>
                                <button type="button" id="btn-cancelar" onclick="resetearFormulario()"
                                    style="display: none; background: #64748b; color: white; border: none; padding: 10px 14px; border-radius: 10px; cursor: pointer;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- SECCIÓN DERECHA: BUSCADOR + TABLA -->
            <div class="table-section">

                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorTabla" class="search-input"
                               placeholder="Buscar por trabajador o módulo...">
                    </div>
                    <button class="btn-exportar" onclick="exportarNotas()">
                        <i class="fas fa-file-export"></i>
                        <span>Exportar Datos</span>
                    </button>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="data-table" id="tablaPrincipal">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>TRABAJADOR</th>
                                    <th>MÓDULO</th>
                                    <th style="text-align: center;">NOTA</th>
                                    <th>ESTADO</th>
                                    <th>FECHA REG.</th>
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                if (!empty($notas)):
                                    foreach ($notas as $n):
                                        $esAprobado = $n['nota'] >= 10.5;
                                        $notaClase  = $esAprobado ? 'nota-aprobado'  : 'nota-desaprobado';
                                        $badgeClase = $esAprobado ? 'badge-aprobado' : 'badge-desaprobado';
                                        $badgeTexto = $esAprobado ? 'APROBADO'       : 'DESAPROBADO';
                                ?>
                                <tr>
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><strong><?= htmlspecialchars($n['nombre_cliente']) ?></strong></td>
                                    <td><?= htmlspecialchars($n['nombre_modulo']) ?></td>
                                    <td style="text-align: center;">
                                        <span class="nota-valor <?= $notaClase ?>">
                                            <?= number_format($n['nota'], 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $badgeClase ?>">
                                            <?= $badgeTexto ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($n['fecha_registro'])) ?></td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        <button class="btn-icon btn-edit"
                                                title="Editar"
                                                onclick='editarNota(<?= json_encode([
                                                    "id_nota"        => $n["id_nota"],
                                                    "trabajador_id"  => $n["cliente_id"],
                                                    "modulo_id"      => $n["modulo_id"],
                                                    "nota"           => $n["nota"],
                                                    "fecha_registro" => $n["fecha_registro"],
                                                ]) ?>)'>
                                            <i class="fas fa-edit" style="color: #4a90e2;"></i>
                                        </button>
                                        <button class="btn-icon btn-delete"
                                                title="Eliminar"
                                                onclick="confirmarEliminar(<?= $n['id_nota'] ?>)">
                                            <i class="fas fa-trash-alt" style="color: #e24a4a;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-clipboard-list"></i>
                                            <p>No hay notas registradas aún.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- /table-section -->
        </div><!-- /dashboard-wrapper -->
    </div><!-- /container -->

    <script src="public/UniversalScript.js?v=<?= time(); ?>"></script>
    <script src="public/notasScript.js?v=<?= time(); ?>"></script>
</body>
</html>