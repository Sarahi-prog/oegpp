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
                <button onclick="toggleRegistro()" class="btn-hamburguesa" title="Mostrar Formulario">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="directorio-title-container">
                    <h2><i class="fas fa-star"></i> Registro de Calificaciones</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Asignación de notas por módulo a los trabajadores.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            
            <div id="seccionRegistro">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;"><i class="fas fa-pen-nib"></i> Calificar</h3>
                    <form id="formNotasAjax" action="index.php?accion=guardar_nota" method="POST">
                        <div class="form-vertical-stack">
                            
                            <div class="field-group">
                                <label>Trabajador</label>
                                <select name="trabajador_id" class="form-select" required>
                                    <option value="">Seleccione trabajador...</option>
                                    </select>
                            </div>
                            
                            <div class="field-group">
                                <label>Módulo</label>
                                <select name="modulo_id" class="form-select" required>
                                    <option value="">Seleccione módulo...</option>
                                    </select>
                            </div>

                            <div class="field-group">
                                <label>Nota Final (0 - 20)</label>
                                <input type="number" name="nota" step="0.01" min="0" max="20" placeholder="0.00" required 
                                       style="font-size: 1.2rem; font-weight: bold; text-align: center; color: #0f172a;">
                            </div>

                            <div class="field-group">
                                <label>Fecha de Registro</label>
                                <input type="date" name="fecha_registro" value="<?= date('Y-m-d'); ?>">
                            </div>

                            <button type="submit" class="btn btn-primary-green" style="width: 100%; justify-content: center; margin-top: 10px;">
                                <i class="fas fa-check-circle"></i> Registrar Nota
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-section">
                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorTabla" class="search-input" placeholder="Buscar por trabajador o módulo...">
                    </div>
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
                                <?php if (!empty($notas)): foreach ($notas as $n): 
                                    $esAprobado = $n['nota'] >= 10.5; // Lógica simple de aprobación
                                ?>
                                <tr>
                                    <td class="id-column"><?= $n['id_nota'] ?></td>
                                    <td><strong><?= htmlspecialchars($n['nombre_trabajador']) ?></strong></td>
                                    <td><?= htmlspecialchars($n['nombre_modulo']) ?></td>
                                    <td style="text-align: center;">
                                        <span style="font-size: 1.1rem; font-weight: 700; color: <?= $esAprobado ? '#10b981' : '#ef4444' ?>;">
                                            <?= number_format($n['nota'], 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge" style="padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; background: <?= $esAprobado ? '#dcfce7' : '#fee2e2' ?>; color: <?= $esAprobado ? '#166534' : '#991b1b' ?>;">
                                            <?= $esAprobado ? 'APROBADO' : 'DESAPROBADO' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($n['fecha_registro'])) ?></td>
                                    <td style="text-align: center;">
                                        <button class="btn-icon" title="Editar"><i class="fas fa-edit"></i></button>
                                        <button class="btn-icon" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 3rem;">
                                        <i class="fas fa-clipboard-list" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                                        <p>No hay notas registradas aún.</p>
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
</body>
</html>