<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/librosStyles.css?v=<?= time(); ?>">
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
                    <h2><i class="fas fa-book"></i> Libros de Registro</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra los libros oficiales de certificados y diplomados.</p>
                </div>
            </div>
            
            <button onclick="abrirParaNuevo()" class="btn-primary-green">
                <i class="fas fa-plus"></i> Nuevo Libro
            </button>
        </div>

        <div class="dashboard-wrapper">
            
            <div id="seccionRegistro" data-modulo="libros">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;"><i class="fas fa-edit"></i> Datos del Libro</h3>
                    <form id="formLibroAjax" action="index.php?accion=guardar_libro" method="POST">
                        <div class="form-vertical-stack">
                            
                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>Tipo de Libro</label>
                                    <select name="tipo" class="form-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="certificados">Certificados</option>
                                        <option value="diplomados">Diplomados</option>
                                    </select>
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Número</label>
                                    <input type="number" name="numero_libro" required min="1" placeholder="Ej. 1">
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>Año de Inicio</label>
                                    <input type="number" name="anio_inicio" required min="2000" max="2100" placeholder="Ej. 2024">
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Fecha de Cierre</label>
                                    <input type="date" name="fecha_fin" title="Dejar en blanco si está activo">
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>Provincia</label>
                                    <input type="text" name="provincia" placeholder="Ej. Arequipa">
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Distrito</label>
                                    <input type="text" name="distrito" placeholder="Ej. Cercado">
                                </div>
                            </div>

                            <div class="field-group">
                                <label>Descripción / Observaciones</label>
                                <textarea name="descripcion" rows="3" placeholder="Detalles adicionales del libro..." style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: 'Inter', sans-serif; box-sizing: border-box; resize: vertical;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary-green" style="width: 100%; justify-content: center; margin-top: 10px;">
                                <i class="fas fa-save"></i> Guardar Libro
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-section">
                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorLibros" class="search-input" placeholder="Buscar por número, tipo o año...">
                    </div>
                    <button class="btn btn-outline"><i class="fas fa-file-export"></i> Exportar</button>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="data-table" id="tablaLibros">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>LIBRO</th>
                                    <th>TIPO</th>
                                    <th>AÑO INICIO</th>
                                    <th>UBICACIÓN</th>
                                    <th>ESTADO</th>
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                if (!empty($libros)):
                                    foreach ($libros as $libro): 
                                        $badgeClass = ($libro['tipo'] == 'diplomados') ? 'badge-diplomado' : 'badge-certificado';
                                        
                                        // Lógica para el estado del libro (Activo vs Cerrado)
                                        $estaCerrado = !empty($libro['fecha_fin']);
                                        $estadoClass = $estaCerrado ? 'estado-cerrado' : 'estado-activo';
                                        $estadoTexto = $estaCerrado ? 'Cerrado' : 'Activo';
                                        
                                        // Formato del nombre del libro (Ej. Libro 01)
                                        $nombreLibro = "Libro " . str_pad($libro['numero_libro'], 2, "0", STR_PAD_LEFT);
                                ?>
                                <tr class="fila-libro">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><strong><?= htmlspecialchars($nombreLibro) ?></strong></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($libro['tipo']) ?></span></td>
                                    <td><i class="far fa-calendar" style="color: #94a3b8; margin-right: 5px;"></i> <?= htmlspecialchars($libro['anio_inicio']) ?></td>
                                    <td style="font-size: 0.9em; color: #475569;">
                                        <?= htmlspecialchars($libro['provincia']) ?><br>
                                        <span style="opacity: 0.7;"><?= htmlspecialchars($libro['distrito']) ?></span>
                                    </td>
                                    <td><span class="estado-indicador <?= $estadoClass ?>"><i class="fas <?= $estaCerrado ? 'fa-lock' : 'fa-check-circle' ?>"></i> <?= $estadoTexto ?></span></td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        <button class="btn-icon btn-edit" title="Editar"><i class="fas fa-edit" style="color: #4a90e2;"></i></button>
                                        <button class="btn-icon btn-delete" title="Eliminar"><i class="fas fa-trash" style="color: #e24a4a;"></i></button>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach; 
                                else: 
                                ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 4rem 2rem;">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                            <i class="fas fa-book-medical" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin libros registrados</h4>
                                            <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Crea el primer libro de actas en el panel izquierdo.</p>
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
    
    <script src="public/universalScript.js?v=<?= time(); ?>"></script>
</body>
</html>