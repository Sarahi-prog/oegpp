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
                    <h2><i class="fas fa-star"></i> Registro de Calificaciones</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Asignación de notas por módulo a los trabajadores.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            
            <!-- Panel lateral para registrar notas -->
            <div id="seccionRegistro">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;"><i class="fas fa-pen-nib"></i> Calificar</h3>
                    <form id="formNotasAjax" action="index.php?accion=guardar_nota" method="POST">
                        <div class="form-vertical-stack">
                            
                            <div class="field-group">
                                <label>Curso</label>
                                <select name="curso_id" class="form-select" required>
                                    <option value="">Seleccione curso...</option>
                                    <?php if (!empty($cursos)): foreach ($cursos as $curso): ?>
                                        <?php if ($curso->getTipo() === 'diplomados'): ?>
                                            <option value="<?= $curso->getIdCurso() ?>">
                                                <?= htmlspecialchars($curso->getNombreCurso()) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>

                            <div class="field-group">
                                <label>Trabajador</label>
                                <select name="trabajador_id" class="form-select" required>
                                    <option value="">Seleccione trabajador...</option>
                                    <?php if (!empty($trabajadores)): foreach ($trabajadores as $t): ?>
                                        <option value="<?= $t->getIdTrabajador() ?>">   
                                            <?= htmlspecialchars($t->getNombres() . ' ' . $t->getApellidos()) ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            
                            <div class="field-group">
                                <label>Módulo</label>
                                <select name="modulo_id" class="form-select" required>
                                    <option value="">Seleccione módulo...</option>
                                    <?php if (!empty($modulos)): foreach ($modulos as $m): ?>
                                        <?php if ($m->getCursoId() == $cursoSeleccionado): ?>
                                            <option value="<?= $m->getIdModulo() ?>">
                                                <?= htmlspecialchars($m->getNombreModulo()) ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; endif; ?>
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

            <!-- Sección de tabla -->
            <div class="table-section">
                <!-- Selector de curso + botón buscar -->
                <div class="search-bar">
                    <form method="GET" action="index.php" class="search-wrapper" style="display:flex; gap:10px; align-items:center;">
                        <!-- Acción fija: notas -->
                        <input type="hidden" name="accion" value="notas">

                        <select name="curso_id" class="form-select" style="flex:1;" required>
                            <option value="">Seleccione curso...</option>
                            <?php if (!empty($cursos)): foreach ($cursos as $curso): ?>
                                <?php if ($curso->getTipo() === 'diplomados'): ?>
                                    <option value="<?= $curso->getIdCurso() ?>"
                                        <?= (!empty($cursoSeleccionado) && $cursoSeleccionado == $curso->getIdCurso()) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($curso->getNombreCurso()) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; endif; ?>
                        </select>
                        <button type="submit" class="btn btn-primary-green">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </form>

                    <!-- Buscador de texto por trabajador -->
                    <div class="search-wrapper" style="margin-top:10px;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorTabla" class="search-input" placeholder="Buscar por trabajador...">
                    </div>
                </div>
                
                <div class="table-card">
                    <div class="table-container">
                        <?php if (!empty($cursoSeleccionado)): ?>
                        <table class="data-table" id="tablaPrincipal">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>TRABAJADOR</th>
                                    <?php if (!empty($modulos)): foreach ($modulos as $m): ?>
                                        <?php if ($m->getCursoId() == $cursoSeleccionado): ?>
                                            <th><?= htmlspecialchars($m->getNombreModulo()) ?></th>
                                        <?php endif; ?>
                                    <?php endforeach; endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($trabajadores)): 
                                    $contador = 1;
                                    foreach ($trabajadores as $c): ?>
                                    <tr>
                                        <td><?= $contador++ ?></td>
                                        <td><strong><?= htmlspecialchars($c->getNombres() . ' ' . $c->getApellidos()) ?></strong></td>
                                        <?php foreach ($modulos as $m): 
                                            if ($m->getCursoId() != $cursoSeleccionado) continue;
                                            $notaEncontrada = null;
                                            foreach ($notas as $n) {
                                                if ($n->getTrabajadorId() == $c->getIdTrabajador() && 
                                                    $n->getModuloId() == $m->getIdModulo()) {
                                                    $notaEncontrada = $n->getNota();
                                                    break;
                                                }
                                            }
                                        ?>
                                        <td style="text-align: center;">
                                            <?= $notaEncontrada !== null ? number_format($notaEncontrada, 2) : '-' ?>
                                        </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="<?= 2 + count($modulos) ?>" style="text-align: center; padding: 3rem;">
                                            <i class="fas fa-clipboard-list" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                                            <p>No hay notas registradas aún.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                            <p style="text-align:center; padding:2rem;">Seleccione un curso para visualizar las notas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
    <script src="public/UniversalScript.js?v=<?= time(); ?>"></script>
  
</body>
</html>
