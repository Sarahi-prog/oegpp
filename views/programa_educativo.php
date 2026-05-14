<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cursos - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/programa_educativoStyles.css?v=<?= time(); ?>">
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
                    <h2><i class="fas fa-book-open"></i> Directorio de Programa Educativo</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra los programas académicos, diplomados y certificaciones.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            <div id="seccionRegistro" data-modulo="programa">

                <div class="side-panel">

                    <h3 id="form-title"
                        style="margin-top: 0; margin-bottom: 20px;">

                        <i class="fas fa-plus-circle"></i>

                        Datos del Programa Educativo

                    </h3>

                    <form id="formProgramaEducativo"
                        action="index.php?accion=guardar_programa"
                        method="POST">

                        <!-- ID -->
                        <input type="hidden"
                            name="id"
                            id="id_programa_educativo_form"
                            value="">

                        <div class="form-vertical-stack">

                            <!-- CÓDIGO -->
                            <div class="field-group">

                                <label>
                                    Código de Programa Educativo
                                </label>

                                <input type="text"
                                    name="codigo"
                                    id="codigo_programa_educativo_form"
                                    required
                                    placeholder="Ej. OEGPP-DIP-001"
                                    style="text-transform: uppercase;">

                            </div>

                            <!-- NOMBRE -->
                            <div class="field-group">

                                <label>
                                    Nombre de Programa Educativo
                                </label>

                                <input type="text"
                                    name="nombre"
                                    id="nombre_programa_educativo_form"
                                    required
                                    placeholder="Nombre del programa educativo">

                            </div>

                            <div style="display: flex; gap: 10px;">

                                <!-- TIPO -->
                                <div class="field-group" style="flex: 1;">

                                    <label>
                                        Tipo de Programa Educativo
                                    </label>

                                    <select name="tipo"
                                            id="tipo_programa_educativo_form"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            Seleccionar...
                                        </option>

                                        <option value="certificados">
                                            Certificado
                                        </option>

                                        <option value="diplomados">
                                            Diplomado
                                        </option>

                                    </select>

                                </div>

                                <!-- HORAS -->
                                <div class="field-group" style="flex: 1;">

                                    <label>
                                        Horas Totales
                                    </label>

                                    <input type="number"
                                        name="horas_totales"
                                        id="horas_totales_programa_educativo_form"
                                        required
                                        min="1"
                                        placeholder="Ej. 120">

                                </div>

                            </div>

                            <!-- ESTADO -->
                            <input type="hidden"
                                name="estado"
                                value="1">

                            <!-- BOTONES -->
                            <div class="form-actions"
                                style="display: flex;
                                        gap: 10px;
                                        margin-top: 15px;">

                                <button type="submit"
                                        id="btn-submit-programa-educativo"
                                        class="btn btn-primary-green"
                                        style="flex: 1;">

                                    <i class="fas fa-save"></i>

                                    <span>
                                        Guardar Programa Educativo
                                    </span>

                                </button>

                                <button type="button"
                                        id="btn-cancelar-programa-educativo"
                                        onclick="cancelarEdicionProgramaEducativo()"
                                        class="btn btn-secondary"
                                        style="display: none;
                                            background-color: #64748b;
                                            color: white;
                                            border: none;
                                            padding: 10px;
                                            border-radius: 12px;
                                            cursor: pointer;">

                                    <i class="fas fa-times"></i>

                                </button>

                            </div>

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
                        <table class="data-table" id="tablaProgramaEducativo">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>CÓDIGO</th>
                                    <th>NOMBRE DE PROGRAMA</th>
                                    <th>TIPO</th>
                                    <th>HORAS</th>
                                    <th>ESTADO</th>
                                    <th style="text-align: center;" class="acciones">ACCIONES
                                        
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTabla">
                                <?php 
                                $i = 1; 
                                if (!empty($programas)):
                                    foreach ($programas as $programa): 
                                        $tipoRaw = $programa->getTipo() ?? '';
                                        $badgeClass = ($tipoRaw === 'diplomados') ? 'badge-diplomado' : 'badge-certificado';
                                        $tipoFormateado = !empty($tipoRaw) ? ucfirst(substr($tipoRaw, 0, -1)) : 'Sin tipo';
                                        $estadoActivo = ($programa->getEstado() == 1); 

                                        $datosJson = json_encode([
                                            'id' => $programa->getId(),
                                            'codigo' => $programa->getCodigo(),
                                            'nombre' => $programa->getNombre(),
                                            'tipo' => $programa->getTipo(),
                                            'horas_totales' => $programa->getHorasTotales()
                                        ]);
                                ?>
                                <tr class="fila-curso">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><span class="codigo-box"><?= htmlspecialchars($programa->getCodigo()) ?></span></td>
                                    <td><strong><?= htmlspecialchars($programa->getNombre()) ?></strong></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= $tipoFormateado ?></span></td>
                                    <td><i class="far fa-clock" style="color: #94a3b8; margin-right: 5px;"></i> <?= htmlspecialchars($programa->getHorasTotales()) ?> h</td>
                                    
                                    <td style="text-align: center;">
                                        <label class="switch">
                                            <input type="checkbox" <?= $estadoActivo ? 'checked' : '' ?> 
                                                   onchange="confirmarEstado(this, <?= $programa->getId() ?>)">
                                            <span class="slider"></span>
                                        </label>
                                    </td>

                                    <td style="text-align: center; white-space: nowrap;">
                                        <a href="index.php?accion=modulos&id=<?= $programa->getId() ?>" 
                                        class="btn-icon" 
                                        title="Gestionar Módulos" 
                                        style="text-decoration: none; margin-right: 8px;">
                                            <i class="fas fa-layer-group" style="color: #10b981;"></i>
                                        </a>

                                        <button class="btn-icon btn-edit" title="Editar" onclick='editarProgramaEducativo(<?= $datosJson ?>)'>
                                            <i class="fas fa-edit" style="color: #4a90e2;"></i>
                                        </button>

                                        <button class="btn-icon btn-delete" title="Eliminar" onclick="eliminarProgramaEducativo(<?= $programa->getId() ?>)">
                                            <i class="fas fa-trash" style="color: #e24a4a;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach; 
                                else: 
                                ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 4rem 2rem;">
                                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                            <i class="fas fa-book-reader" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin programas registrados</h4>
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
    <script src="public/universalScript.js?v=<?= time(); ?>"></script>
    <script src="public/programa_educativoScript.js?v=<?= time(); ?>"></script>
</body>
</html>