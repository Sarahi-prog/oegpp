<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/cursosStyles.css?v=<?= time(); ?>">
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
                    <h2><i class="fas fa-book-open"></i> Directorio de Cursos</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra los programas académicos, diplomados y certificaciones.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            <div id="seccionRegistro" data-modulo="cursos">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;"><i class="fas fa-edit"></i> Datos de curso</h3>
                    <form id="formCursoAjax" action="index.php?accion=guardar_curso" method="POST">
                    <input type="hidden" name="id_curso" id="id_curso_form" value="">    
                        <div class="form-vertical-stack">
                            <div class="field-group">
                                <label>Código de curso</label>
                                <input type="text" name="codigo_curso" id="codigo_curso_form" required placeholder="Ej. OEGPP-DIP-001" style="text-transform: uppercase;">
                            </div>
                            
                            <div class="field-group">
                                <label>Nombre de Curso</label>
                                <input type="text" name="nombre_curso" id="nombre_curso_form" required placeholder="Nombre completo del programa">
                            </div>

                            <div style="display: flex; gap: 10px;">
                                <div class="field-group" style="flex: 1;">
                                    <label>Tipo</label>
                                    <select name="tipo" id="tipo_form" class="form-select" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="certificados">Certificado</option>
                                        <option value="diplomados">Diplomado</option>
                                    </select>
                                </div>
                                <div class="field-group" style="flex: 1;">
                                    <label>Horas Totales</label>
                                    <input type="number" name="horas_totales" id="horas_totales_form" required min="1" placeholder="Ej. 120">
                                </div>
                            </div>

                            <button type="submit" id="btnSubmitCurso" class="btn btn-success">
    <i class="fas fa-save"></i> <span>Guardar Programa</span>
</button>

<button type="button" id="btnCancelarEdicion" class="btn btn-secondary" style="display:none;" onclick="cancelarEdicion()">
    Cancelar
</button>

                        </div>
                    </form>
                </div>
            </div>

            <div class="table-section">
                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorCursos" class="search-input" placeholder="Buscar por código o nombre...">
                    </div>
                    <button class="btn-exportar" onclick="exportarCursos()">
                        <i class="fas fa-file-export"></i>
                        <span>Exportar Datos</span>
                    </button>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="data-table" id="tablaCursos">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>CÓDIGO</th>
                                    <th>NOMBRE DEL PROGRAMA</th>
                                    <th>TIPO</th>
                                    <th>HORAS</th>
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                if (!empty($cursos)):
                                    foreach ($cursos as $curso): 
                                        // CORRECCIÓN: Uso de Getters en lugar de propiedades directas
                                        $tipoRaw = $curso->getTipo();
                                        $badgeClass = ($tipoRaw == 'diplomados') ? 'badge-diplomado' : 'badge-certificado';
                                        $tipoFormateado = ucfirst(substr($tipoRaw, 0, -1)); 
                                        
                                        // Preparamos los datos para el botón editar en formato JSON
                                        $datosJson = json_encode([
                                            'id_curso' => $curso->getIdCurso(),
                                            'codigo_curso' => $curso->getCodigoCurso(),
                                            'nombre_curso' => $curso->getNombreCurso(),
                                            'tipo' => $curso->getTipo(),
                                            'horas_totales' => $curso->getHorasTotales()
                                        ]);
                                ?>
                                <tr class="fila-curso">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><span class="codigo-box"><?= htmlspecialchars($curso->getCodigoCurso()) ?></span></td>
                                    <td><strong><?= htmlspecialchars($curso->getNombreCurso()) ?></strong></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= $tipoFormateado ?></span></td>
                                    <td><i class="far fa-clock" style="color: #94a3b8; margin-right: 5px;"></i> <?= htmlspecialchars($curso->getHorasTotales()) ?> h</td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        <button class="btn-icon btn-edit" title="Editar" 
                                                onclick='editarCurso(<?= $datosJson ?>)'>
                                            <i class="fas fa-edit" style="color: #4a90e2;"></i>
                                        </button>

                                        <button class="btn-icon btn-delete" title="Eliminar" 
                                                onclick="eliminarCurso(<?= $curso->getIdCurso() ?>)">
                                            <i class="fas fa-trash" style="color: #e24a4a;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach; 
                                else: 
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 4rem 2rem;">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                            <i class="fas fa-book-reader" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin programas registrados</h4>
                                            <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Utiliza el formulario lateral para agregar tu primer curso.</p>
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
    <script src="public/cursosScript.js?v=<?= time(); ?>"></script>    
</body>
</html>