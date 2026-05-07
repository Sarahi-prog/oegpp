<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros de Registro - OEGPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/librosStyles.css?v=<?= time(); ?>">
</head> 
<body>
    <?php include 'includes/menu.php'; ?>

    <div class="container main-content">

        <div class="header-acciones">
            <div class="titulo-con-boton">
                <button onclick="toggleSidebar()" class="btn-hamburguesa" title="Mostrar/Ocultar Panel">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="directorio-title-container">
                    <h2><i class="fas fa-book" style="color: var(--tech-green);"></i> Gestión de Libros</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra los libros de actas y registros físicos.</p>
                </div>
            </div>
        </div>

        <div class="top-action-bar">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscadorLibros" class="search-input" placeholder="Buscar por número, distrito o provincia...">
            </div>
            
            <div class="right-actions">
                <div class="view-toggles">
                    <button class="btn-toggle active" id="btnVistaLista" onclick="aplicarVista('lista')" title="Vista de Lista">
                        <i class="fas fa-list"></i>
                    </button>
                    <button class="btn-toggle" id="btnVistaGrid" onclick="aplicarVista('grid')" title="Vista de Cuadrícula">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
                <button class="btn-exportar" onclick="exportarLibros()">
                    <i class="fas fa-file-export"></i> EXPORTAR DATOS
                </button>
                <button class="btn-primary-green" onclick="nuevoLibro()">
                    <i class="fas fa-plus"></i> NUEVO LIBRO
                </button>
            </div>
        </div>

        <div id="vistaListaContenedor" class="dashboard-wrapper">
            
            <div id="panelLateralForm" class="panel-oculto">
                <div class="side-panel">
                    <h3 class="panel-title"><i class="fas fa-plus-circle"></i> DATOS DEL REGISTRO</h3>
                    <div id="contenedorFormLateral"></div>
                </div>
            </div>

            <div class="table-section">
                <div class="table-card">
                    <div class="table-container">
                        <table class="data-table" id="tablaLibros">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>TIPO</th>
                                    <th>NÚMERO</th>
                                    <th>DISTRITO</th>
                                    <th>PROVINCIA</th>
                                    <th>F. INICIO</th>
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaLibros">
                                <?php if (!empty($libros)): $i = 1; ?>
                                    <?php foreach ($libros as $libro): 
                                        // CORRECCIÓN: Acceso directo a propiedades en lugar de get...()
                                        $datosJson = htmlspecialchars(json_encode([
                                            'id_libro' => $libro->id_libro,
                                            'tipo' => $libro->tipo,
                                            'numero_libro' => $libro->numero_libro,
                                            'anio_inicio' => $libro->anio_inicio,
                                            'fecha_fin' => $libro->fecha_fin,
                                            'distrito' => $libro->distrito,
                                            'provincia' => $libro->provincia,
                                            'descripcion' => $libro->descripcion
                                        ]), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr>
                                        <td style="font-weight: 600;"><?= $i++ ?></td>
                                        <td style="font-weight: 600;"><?= htmlspecialchars($libro->tipo ?? '') ?></td>
                                        <td>Libro Nº <?= str_pad($libro->numero_libro ?? 0, 3, '0', STR_PAD_LEFT) ?></td>
                                        <td><?= htmlspecialchars($libro->distrito ?? '') ?></td>
                                        <td><?= htmlspecialchars($libro->provincia ?? '') ?></td>
                                        <td><?= !empty($libro->anio_inicio) ? date('d/m/Y', strtotime($libro->anio_inicio)) : '' ?></td>
                                        <td style="text-align: center; white-space: nowrap;">
                                            <button class="btn-icon btn-edit" title="Editar" onclick='editarLibro(<?= $datosJson ?>)'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-icon btn-delete" title="Eliminar" onclick="confirmarEliminar(<?= $libro->id_libro ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; padding: 20px;">No hay libros registrados.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="vistaGridContenedor" style="display: none;">
            <div class="books-grid">
                <?php if (!empty($libros)): ?>
                    <?php foreach ($libros as $libro): 
                        // CORRECCIÓN: Acceso directo a propiedades en lugar de get...()
                        $datosJson = htmlspecialchars(json_encode([
                            'id_libro' => $libro->id_libro,
                            'tipo' => $libro->tipo,
                            'numero_libro' => $libro->numero_libro,
                            'anio_inicio' => $libro->anio_inicio,
                            'fecha_fin' => $libro->fecha_fin,
                            'distrito' => $libro->distrito,
                            'provincia' => $libro->provincia,
                            'descripcion' => $libro->descripcion
                        ]), ENT_QUOTES, 'UTF-8');
                        
                        $claseBadge = ($libro->tipo == 'Diplomados') ? 'badge-blue' : 'badge-orange';
                    ?>
                    <div class="book-folder-card">
                        <div class="folder-header">
                            <span class="folder-tipo <?= $claseBadge ?>"><?= htmlspecialchars($libro->tipo ?? '') ?></span>
                            <div class="folder-actions">
                                <button class="btn-icon btn-edit" title="Editar" onclick='editarLibro(<?= $datosJson ?>)'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon btn-delete" title="Eliminar" onclick="confirmarEliminar(<?= $libro->id_libro ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <h3 class="folder-title"><i class="fas fa-book-open"></i> Libro Nº <?= str_pad($libro->numero_libro ?? 0, 3, '0', STR_PAD_LEFT) ?></h3>
                        <p class="folder-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($libro->distrito ?? '') ?>, <?= htmlspecialchars($libro->provincia ?? '') ?></p>
                        
                        <div class="folder-meta-grid">
                            <div class="meta-item">
                                <span>Fecha Inicio</span>
                                <span><?= !empty($libro->anio_inicio) ? date('d/m/Y', strtotime($libro->anio_inicio)) : '' ?></span>
                            </div>
                            <div class="meta-item">
                                <span>Fecha Cierre</span>
                                <span><?= !empty($libro->fecha_fin) ? date('d/m/Y', strtotime($libro->fecha_fin)) : 'En curso...' ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: #64748b;">
                        No hay libros registrados.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <div class="modal-overlay" id="modalLibro">
        <div class="modal-content">
            <button class="close-modal" onclick="cerrarModalLibro()"><i class="fas fa-times"></i></button>
            <h3 class="modal-title"><i class="fas fa-book" style="color: var(--tech-green);"></i> Datos del Registro</h3>
            <div id="contenedorFormModal"></div>
        </div>
    </div>

    <div id="formularioMaestro" style="display: none;">
        <form id="formLibro" action="index.php?accion=guardar_libro" method="POST">
            <input type="hidden" name="id_libro" id="id_libro_form">

            <div class="form-grid">
                <div class="field-group">
                    <label>Tipo de Registro</label>
                    <select name="tipo" id="tipo_form" required>
                        <option value="">Seleccionar...</option>
                        <option value="Diplomados">Diplomados</option>
                        <option value="Certificaciones">Certificaciones</option>
                        <option value="Especializaciones">Especializaciones</option>
                    </select>
                </div>
                <div class="field-group">
                    <label>Número</label>
                    <input type="number" name="numero_libro" id="numero_libro_form" required placeholder="Ej. 1">
                </div>
                <div class="field-group">
                    <label>Distrito</label>
                    <input type="text" name="distrito" id="distrito_form" required placeholder="Ej. Miraflores">
                </div>
                <div class="field-group">
                    <label>Provincia</label>
                    <input type="text" name="provincia" id="provincia_form" required placeholder="Ej. Lima">
                </div>
                <div class="field-group">
                    <label>Fecha Inicio</label>
                    <input type="date" name="anio_inicio" id="anio_inicio_form" required>
                </div>
                <div class="field-group">
                    <label>Fecha Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin_form">
                </div>
                <div class="field-group span-2">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="descripcion_form" placeholder="Detalles..."></textarea>
                </div>
                
                <div class="field-group span-2" style="display: flex; gap: 10px; flex-wrap: nowrap; flex-direction: row;">
                    <button type="submit" id="btnSubmitLibro" class="btn-primary-green" style="flex: 1; min-width: 0;">
                        <i class="fas fa-save"></i> <span>GUARDAR LIBRO</span>
                    </button>
                    <button type="button" id="btnCancelarEdicion" class="btn-cancelar" style="display: none; width: 45px; height: 45px; flex-shrink: 0;" onclick="nuevoLibro()" title="Cancelar edición">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="public/librosScript.js?v=<?= time(); ?>"></script>
</body>
</html>