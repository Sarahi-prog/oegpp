<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Capacitaciones - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/capacitacionesStyles.css?v=<?= time(); ?>">
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
                    <h2><i class="fas fa-graduation-cap"></i> Gestión de Capacitaciones</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra el historial de cursos y registros del personal.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            
            <div id="seccionRegistro">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;"><i class="fas fa-plus-circle"></i> Nuevo Registro</h3>
                    <form id="formCapacitacionAjax" action="index.php?accion=guardar_capacitacion" method="POST">
                        <div class="form-vertical-stack">
                            
                            <div class="field-group">
                                <label>Trabajador</label>
                                <select name="trabajador_id" class="form-select" required>
                                    <option value="">Seleccione un trabajador...</option>
                                    </select>
                            </div>
                            
                            <div class="field-group">
                                <label>Curso</label>
                                <select name="curso_id" class="form-select" required>
                                    <option value="">Seleccione un curso...</option>
                                    </select>
                            </div>

                            <div class="field-group">
                                <label>Libro de Registro</label>
                                <select name="libro_id" class="form-select" required>
                                    <option value="">Seleccione el libro...</option>
                                    </select>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>N° Registro</label>
                                    <input type="number" name="registro" required min="1">
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Horas</label>
                                    <input type="number" name="horas_realizadas" required min="1">
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>F. Inicio</label>
                                    <input type="date" name="fecha_inicio">
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>F. Fin</label>
                                    <input type="date" name="fecha_fin">
                                </div>
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>F. Emisión</label>
                                    <input type="date" name="fecha_emision" required>
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Folio</label>
                                    <input type="text" name="folio" maxlength="20" placeholder="Ej. F-001">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-green" style="width: 100%; justify-content: center; margin-top: 10px;">
                                <i class="fas fa-save"></i> Guardar Capacitación
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-section">
                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorTabla" class="search-input" placeholder="Buscar por trabajador, curso o folio...">
                    </div>
                    <button class="btn btn-outline"><i class="fas fa-file-export"></i> Exportar</button>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="data-table" id="tablaPrincipal">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>TRABAJADOR</th>
                                    <th>CURSO</th>
                                    <th>LIBRO</th>
                                    <th>N° REG</th>
                                    <th>HORAS</th>
                                    <th>INICIO / FIN</th>
                                    <th>FOLIO</th> 
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                // Asumiendo que la variable $capacitaciones trae los datos con JOINs desde tu BD
                                if (!empty($capacitaciones)):
                                    foreach ($capacitaciones as $c): 
                                ?>
                                <tr class="fila-capacitacion">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><strong><?= htmlspecialchars($c['nombre_trabajador']) ?></strong></td>
                                    <td><?= htmlspecialchars($c['nombre_curso']) ?></td>
                                    <td><?= htmlspecialchars($c['nombre_libro']) ?></td>
                                    <td><?= htmlspecialchars($c['registro']) ?></td>
                                    <td><?= htmlspecialchars($c['horas_realizadas']) ?> h</td>
                                    <td style="font-size: 0.9em; color: #475569;">
                                        <?= htmlspecialchars($c['fecha_inicio']) ?> <br>
                                        <?= htmlspecialchars($c['fecha_fin']) ?>
                                    </td>
                                    <td><span style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-family: monospace;"><?= htmlspecialchars($c['folio']) ?></span></td>
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
                                    <td colspan="9" style="text-align: center; padding: 4rem 2rem;">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                            <i class="fas fa-certificate" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin capacitaciones registradas</h4>
                                            <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Utiliza el formulario lateral para añadir el primer registro.</p>
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
    
    <script src="public/capacitacionesScript.js?v=<?= time(); ?>"></script> -->
</body>
</html>