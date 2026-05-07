<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Módulos - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/modulosStyles.css?v=<?= time(); ?>">
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
                    <h2><i class="fas fa-layer-group"></i> Gestión de Módulos</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra la estructura y fechas de cada programa.</p>
                </div>
            </div>
            
            <button onclick="abrirParaNuevo()" class="btn-primary-green">
                <i class="fas fa-plus"></i> Nuevo Módulo
            </button>
        </div>

        <div class="dashboard-wrapper">
            
            <div id="seccionRegistro" data-modulo="modulos">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;"><i class="fas fa-puzzle-piece"></i> Datos del Módulo</h3>
                    <form id="formModuloAjax" action="index.php?accion=guardar_modulo" method="POST">
                        <div class="form-vertical-stack">
                            
                            <div class="field-group">
                                <label>Programa / Curso Base</label>
                                <select name="curso_id" class="form-select" required>
                                    <option value="">Seleccione el curso...</option>
                                    <?php 
                                    // Asumiendo que tu controlador envía la variable $cursos_disponibles
                                    if(!empty($cursos_disponibles)): 
                                        foreach($cursos_disponibles as $curso): 
                                    ?>
                                        <option value="<?= $curso['id_curso'] ?>"><?= htmlspecialchars($curso['codigo_curso'] . ' - ' . $curso['nombre_curso']) ?></option>
                                    <?php 
                                        endforeach; 
                                    endif; 
                                    ?>
                                </select>
                            </div>
                            
                            <div class="field-group">
                                <label>Nombre del Módulo</label>
                                <input type="text" name="nombre_modulo" required placeholder="Ej. Módulo I: Fundamentos...">
                            </div>

                            <div class="field-group">
                                <label>Horas Académicas</label>
                                <input type="number" name="horas" required min="1" placeholder="Ej. 24">
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>Fecha de Inicio</label>
                                    <input type="date" name="fecha_inicio">
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Fecha de Fin</label>
                                    <input type="date" name="fecha_fin">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-green" style="width: 100%; justify-content: center; margin-top: 10px;">
                                <i class="fas fa-save"></i> Guardar Módulo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-section">
                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorModulos" class="search-input" placeholder="Buscar por módulo o curso...">
                    </div>
                    <button class="btn btn-outline"><i class="fas fa-file-export"></i> Exportar</button>
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
                                    <th>CRONOGRAMA (INICIO - FIN)</th>
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                // Asumiendo que $modulos trae los datos con un JOIN a la tabla cursos
                                if (!empty($modulos)):
                                    foreach ($modulos as $mod): 
                                ?>
                                <tr class="fila-modulo">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td>
                                        <span class="curso-referencia">
                                            <?= htmlspecialchars($mod['nombre_curso'] ?? 'Curso Desconocido') ?>
                                        </span>
                                    </td>
                                    <td><strong><?= htmlspecialchars($mod['nombre_modulo']) ?></strong></td>
                                    <td><i class="far fa-clock" style="color: #94a3b8; margin-right: 5px;"></i> <?= htmlspecialchars($mod['horas']) ?> h</td>
                                    <td style="font-size: 0.9em; color: #475569;">
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            <span><i class="fas fa-calendar-alt" style="color: #44bd1f; width: 15px;"></i> <?= htmlspecialchars($mod['fecha_inicio'] ?? 'No definida') ?></span>
                                            <span><i class="fas fa-flag-checkered" style="color: #e24a4a; width: 15px;"></i> <?= htmlspecialchars($mod['fecha_fin'] ?? 'No definida') ?></span>
                                        </div>
                                    </td>
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
                                    <td colspan="6" style="text-align: center; padding: 4rem 2rem;">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                            <i class="fas fa-layer-group" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin módulos registrados</h4>
                                            <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Agrega la estructura de tus cursos desde el panel izquierdo.</p>
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