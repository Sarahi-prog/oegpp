<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Registros</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="public/clientesStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?php echo time(); ?>">

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
                    <h2><i class="fas fa-clipboard-list"></i> Gestión de Registros</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra la lista de registros de capacitación.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            <div id="seccionRegistro">
                <div class="side-panel">
                    <h3 id="form-title"><i class="fas fa-plus-circle"></i> Datos del Registro</h3>
                    <form id="formRegistro" action="index.php?accion=guardar_registro" method="POST">
                        <div class="form-vertical-stack">
                            <input type="hidden" name="id_registro" id="id_registro_form" value="">

                            <div class="field-group">
                                <label>N° Registro</label>
                                <input type="text" name="registro" id="registro_input" required placeholder="Ej. 001">
                            </div>
                            <div class="field-group">
                                <label>Cliente</label>
                                <select name="trabajador_id" class="form-select" required>
                                    <option value="">Seleccione un cliente...</option>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= $c->setIdCliente() ?>">
                                            <?= (htmlspecialchars($c->getNombres())." ".htmlspecialchars($c->getApellidos())) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label>Curso</label>
                                <select name="curso_id" class="form-select" required>
                                    <option value="">Seleccione un curso...</option>

                                    <?php foreach ($cursos as $c): ?>
                                        <option value="<?= $c->getIdCurso() ?>">
                                            <?= htmlspecialchars($c->getNombreCurso()) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="field-group">
                                <label>Libro de Registro</label>
                                <select name="libro_id" class="form-select" required>
                                    <option value="">Seleccione el libro...</option>
                                    <?php foreach ($libros as $l): ?>
                                        <option value="<?= $l->getIdLibro() ?>">
                                            <?= ("OEGPP-L".htmlspecialchars($l->getNumeroLibro())) ?>
                                        </option>
                                    <?php endforeach; ?> 
                                </select>
                            </div>
                            <div class="field-group">
                                <label>Horas Realizadas</label>
                                <input type="number" name="horas_realizadas" id="horas_input" placeholder="Ej. 40">
                            </div>
                            <div class="field-group">
                                <label>Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio_input">
                            </div>
                            <div class="field-group">
                                <label>Fecha Fin</label>
                                <input type="date" name="fecha_fin" id="fecha_fin_input">
                            </div>
                            <div class="field-group">
                                <label>Fecha Emisión</label>
                                <input type="date" name="fecha_emision" id="fecha_emision_input">
                            </div>
                            <div class="field-group">
                                <label>Folio</label>
                                <input type="text" name="folio" id="folio_input" placeholder="Ej. F-001">
                            </div>
                            <div class="field-group">
                                <label>Link</label>
                                <input type="text" name="linkr" id="linkr_input" placeholder="https://...">
                            </div>
                            <div class="field-group">
                                <label>Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                    <option value="Pendiente">Pendiente</option>
                                </select>
                            </div>

                            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 15px;">
                                <button type="submit" id="btn-submit-form" class="btn-primary-green">
                                    <i class="fas fa-save"></i> 
                                    <span>Guardar Registro</span>
                                </button>

                                <button type="button" id="btn-cancelar" onclick="resetearFormulario()" 
                                        class="btn btn-secondary" style="display: none; background-color: #64748b; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <a href="index.php?accion=clientes" 
                                    class="btn-ir-gestion" 
                                    style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 8px; padding: 12px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 8px; font-size: 0.85rem; font-weight: 500; transition: background 0.3s;">
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
                    <button class="btn-exportar" onclick="exportarCursos()">
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
                                    <th>CÓD. REGISTRO</th>
                                    <th>CLIENTE</th>
                                    <th>DNI</th>
                                    <th>CURSO</th>
                                    <th>TIPO</th>
                                    <th>LIBRO</th>
                                    <th>FOLIO</th>
                                    <th>HORAS</th>
                                    <th>F. INICIO</th>
                                    <th>F. FIN</th>
                                    <th>F. EMISIÓN</th>
                                    <th>ESTADO</th>
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTabla">
                                <?php 
                                    $i = 1; 
                                    if (!empty($registros)):
                                        foreach ($registros as $r): 
                                ?>
                                <tr class="fila-cliente">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><strong><?= htmlspecialchars($r->codigo_registro ?? '') ?></strong></td>
                                    <td><?= htmlspecialchars($r->nombre_cliente ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->dni ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->nombre_curso ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->tipo ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->nombre_libro ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->folio ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->horas_realizadas ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->fecha_inicio ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->fecha_fin ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->fecha_emision ?? '') ?></td>
                                    <td><?= htmlspecialchars($r->estado ?? '') ?></td>
                                    
                                    <td style="text-align: center; white-space: nowrap;">
                                        <button class="btn-icon btn-edit" 
                                                title="Editar" 
                                                onclick='editarRegistro(<?= json_encode($r) ?>)'>
                                            <i class="fas fa-edit" style="color: #4a90e2;"></i>
                                        </button>

                                        <button class="btn-icon btn-delete" 
                                                title="Eliminar" 
                                                onclick="confirmarEliminar(<?= $r->id_registro ?>)">
                                            <i class="fas fa-trash" style="color: #e24a4a;"></i>
                                        </button>

                                        <?php if (!empty($r->linkr)): ?>
                                        <a href="<?= htmlspecialchars($r->linkr) ?>" target="_blank" class="btn-icon" title="Ver Link">
                                            <i class="fas fa-external-link-alt" style="color: #22c55e;"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                        endforeach; 
                                    else: 
                                ?>
                                <tr>
                                    <td colspan="14" style="text-align: center; padding: 4rem 2rem;">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                            <i class="fas fa-folder-open" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin registros encontrados</h4>
                                            <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Utiliza el panel lateral para agregar tu primer registro.</p>
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

</body>
</html>