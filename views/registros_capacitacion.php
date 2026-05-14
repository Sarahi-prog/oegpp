    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de Registros</title>

        <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="public/registrosStyles.css?v=<?= time(); ?>">
        <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
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
                        <h2><i class="fas fa-clipboard-list"></i> Gestión de Registros</h2>
                        <p>Administra los registros de capacitación del sistema OEGPP.</p>
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="stat-card green">
                    <div class="stat-num"><?= count($registros ?? []) ?></div>
                    <div class="stat-label"><i class="fas fa-layer-group"></i> Total registros</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num"><?= count(array_filter($registros ?? [], fn($r) => $r->estado === 'Activo')) ?></div>
                    <div class="stat-label"><i class="fas fa-check-circle"></i> Activos</div>
                </div>
                <div class="stat-card amber">
                    <div class="stat-num"><?= count(array_filter($registros ?? [], fn($r) => $r->estado === 'Pendiente')) ?></div>
                    <div class="stat-label"><i class="fas fa-clock"></i> Pendientes</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-num"><?= count(array_filter($registros ?? [], fn($r) => $r->estado === 'Inactivo')) ?></div>
                    <div class="stat-label"><i class="fas fa-minus-circle"></i> Inactivos</div>
                </div>
            </div>

            <div class="dashboard-wrapper">

                <div id="seccionRegistro">
                    <div class="side-panel">
                        <h3 id="form-title"><i class="fas fa-plus-circle"></i> Datos del Registro</h3>

                        <form id="formRegistro" action="index.php?accion=guardar_registro" method="POST">
                            <div class="form-vertical-stack">
                                <input type="hidden" name="id_registro" id="id_registro_form" value="">

                                <div class="form-section-title">Datos Principales</div>
                                
                                <div class="field-group">
                                    <label>Cliente</label>
                                    <select name="cliente_id" id="cliente_id_input" class="form-select" required>
                                        <option value="">Seleccione un cliente...</option>
                                        <?php foreach ($clientes ?? [] as $c): ?>
                                            <option value="<?= $c->id_cliente ?>">
                                                <?= htmlspecialchars($c->nombres) . ' ' . htmlspecialchars($c->apellidos) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="field-group">
                                    <label>Curso</label>
                                    <select name="curso_id" id="curso_id_input" class="form-select" required>
                                        <option value="">Seleccione un curso...</option>
                                        <?php foreach ($cursos ?? [] as $c): ?>
                                            <option value="<?= $c->id_curso ?>">
                                                <?= htmlspecialchars($c->nombre_curso) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-section-title">Desarrollo Académico</div>

                                <div class="field-group">
                                    <label>Horas Realizadas</label>
                                    <input type="number" name="horas_realizadas" id="horas_input" placeholder="Ej. 40">
                                </div>

                                <!-- FECHAS AHORA EN UNA SOLA COLUMNA (UNO DEBAJO DEL OTRO) -->
                                <div class="field-group">
                                    <label>Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio_input">
                                </div>

                                <div class="field-group">
                                    <label>Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin_input">
                                </div>

                                <div class="form-section-title">Datos de Certificación</div>

                                <!-- REGISTRO Y FOLIO AHORA EN UNA SOLA COLUMNA (Por el panel más delgado) -->
                                <div class="field-group">
                                    <label>N° Registro</label>
                                    <input type="text" name="registro" id="registro_input" required placeholder="Ej. 001">
                                </div>

                                <div class="field-group">
                                    <label>Folio</label>
                                    <input type="text" name="folio" id="folio_input" placeholder="Ej. F-001">
                                </div>

                                <div class="field-group">
                                    <label>Libro de Registro</label>
                                    <select name="libro_id" id="libro_id_input" class="form-select" required>
                                        <option value="">Seleccione el libro...</option>
                                        <?php foreach ($libros ?? [] as $l): ?>
                                            <option value="<?= $l->id_libro ?>">
                                                <?= 'OEGPP-L' . htmlspecialchars($l->numero_libro) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="field-group">
                                    <label>Fecha Emisión</label>
                                    <input type="date" name="fecha_emision" id="fecha_emision_input">
                                </div>

                                <div class="form-section-title">Estado y Verificación</div>

                                <div class="field-group">
                                    <label>Estado del Registro</label>
                                    <select name="estado" id="estado_input" class="form-select">
                                        <option value="Activo">Activo</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>

                                <div class="field-group">
                                    <label>Link / Código QR (Opcional)</label>
                                    <input type="text" name="linkr" id="linkr_input" placeholder="https://dominio.com/cert/...">
                                </div>

                                <div class="form-actions" style="margin-top: 15px;">
                                    <button type="submit" id="btn-submit-form" class="btn-primary-green">
                                        <i class="fas fa-save"></i>
                                        <span>Guardar Registro</span>
                                    </button>
                                    <button type="button" id="btn-cancelar" onclick="resetearFormulario()" class="btn-cancelar" style="display:none;" title="Cancelar Edición">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <a href="index.php?accion=clientes" class="btn-ir-gestion">
                                    <i class="fas fa-users-cog"></i> Ir a Gestión de Clientes
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-section">
                    <div class="search-bar">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="buscadorTabla" class="search-input" placeholder="Buscar por código, nombre o curso...">
                        </div>
                        
                        <div class="search-actions">
                            <button class="btn-export" onclick="exportarRegistros()">
                                <i class="fas fa-file-export"></i> Exportar
                            </button>
                            <button class="btn-primary-green" onclick="toggleRegistro()">
                                <i class="fas fa-plus"></i> Nuevo Registro
                            </button>
                        </div>
                    </div>

                    <div class="table-card">
                        <div class="table-container">
                            <table class="data-table" id="tablaPrincipal">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nº Registro </th>
                                        <th>Cliente</th>
                                        <th>Curso & Tipo</th>
                                        <th>Libro</th>
                                        <th>Folio</th>
                                        <th>Horas</th>
                                        <th>Fechas del Registro</th>
                                        <th>Estado</th>
                                        <th style="text-align:center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTabla">
                                    <?php
                                        $i = 1;
                                        if (!empty($registros)):
                                            foreach ($registros as $r):
                                                $nombre = htmlspecialchars($r->nombre_cliente ?? '');
                                                $partes  = explode(' ', trim($nombre));
                                                $inicial1 = strtoupper(substr($partes[0] ?? '', 0, 1));
                                                $inicial2 = strtoupper(substr($partes[1] ?? '', 0, 1));
                                                $iniciales = $inicial1 . $inicial2;
                                                $colores = ['green','blue','amber','purple','coral'];
                                                $color = $colores[$r->id_registro % count($colores)];
                                                
                                                // Lógica para color de la etiqueta del curso
                                                $tipoFormateado = htmlspecialchars($r->tipo ?? '');
                                                $claseTipo = (strtolower($tipoFormateado) === 'diplomados') ? 'badge-diplomado' : 'badge-certificado';
                                    ?>
                                    <tr class="fila-cliente">
                                        <td class="id-column"><?= $i++ ?></td>
                                        <td><span class="reg-code"><?= htmlspecialchars($r->codigo_registro ?? '') ?></span></td>
                                        <td>
                                            <div class="cliente-cell">
                                                <div class="avatar avatar-<?= $color ?>"><?= $iniciales ?></div>
                                                <div>
                                                    <div class="name-text"><?= $nombre ?></div>
                                                    <div class="dni-text"><?= htmlspecialchars($r->dni ?? '') ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="curso-agrupado">
                                                <span class="curso-nombre"><?= htmlspecialchars($r->nombre_curso ?? '') ?></span>
                                                <span class="badge-tipo <?= $claseTipo ?>"><?= $tipoFormateado ?></span>
                                            </div>
                                        </td>
                                        
                                        <td><span class="libro-pill"><?= htmlspecialchars($r->nombre_libro ?? '') ?></span></td>
                                        <td><span class="folio-code"><?= htmlspecialchars($r->folio ?? '') ?></span></td>
                                        <td><span class="horas-pill"><?= htmlspecialchars($r->horas_realizadas ?? '') ?> h</span></td>
                                        
                                        <td>
                                            <div class="fechas-agrupadas">
                                                <div class="fecha-item">
                                                    <span class="fecha-label" title="Fecha de Inicio"><i class="fas fa-play-circle" style="color: var(--tech-blue);"></i> Inicio:</span>
                                                    <span class="fecha-val"><?= htmlspecialchars($r->fecha_inicio ?? '--') ?></span>
                                                </div>
                                                <div class="fecha-item">
                                                    <span class="fecha-label" title="Fecha de Fin"><i class="fas fa-stop-circle" style="color: var(--tech-red);"></i> Final:</span>
                                                    <span class="fecha-val"><?= htmlspecialchars($r->fecha_fin ?? '--') ?></span>
                                                </div>
                                                <div class="fecha-item">
                                                    <span class="fecha-label" title="Fecha de Emisión"><i class="fas fa-award" style="color: var(--tech-amber);"></i> Emisión:</span>
                                                    <span class="fecha-val"><?= htmlspecialchars($r->fecha_emision ?? '--') ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <?php
                                                $estado = strtolower($r->estado ?? '');
                                                $iconoEstado = match($estado) {
                                                    'activo'   => 'fa-check-circle',
                                                    'pendiente'=> 'fa-clock',
                                                    default    => 'fa-minus-circle'
                                                };
                                            ?>
                                            <span class="badge-estado <?= $estado ?>">
                                                <i class="fas <?= $iconoEstado ?>"></i>
                                                <?= htmlspecialchars($r->estado ?? '') ?>
                                            </span>
                                        </td>
                                        <td style="text-align:center; white-space:nowrap;">
                                            <button class="btn-icon btn-edit" title="Editar"
                                                    onclick="editarRegistro(<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-icon btn-delete" title="Eliminar"
                                                    onclick="confirmarEliminarRegistro(<?= $r->id_registro ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php if (!empty($r->linkr)): ?>
                                            <a href="<?= htmlspecialchars($r->linkr) ?>" target="_blank" class="btn-icon btn-link" title="Ver Link">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                            endforeach;
                                        else:
                                    ?>
                                    <tr>
                                        <td colspan="10"> <div class="empty-state">
                                                <i class="fas fa-folder-open"></i>
                                                <h4>Sin registros encontrados</h4>
                                                <p>Utiliza el panel lateral para agregar tu primer registro.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="public/UniversalScript.js?v=<?= time(); ?>"></script>
        <script src="public/registrosScript.js?v=<?= time(); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    </body>
    </html>