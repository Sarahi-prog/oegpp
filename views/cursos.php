<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Cursos - OEGPP</title>
    <link rel="stylesheet" href="public/cursosStyles.css?v=<?php echo time(); ?>">  
    <link rel="stylesheet" href="public/menuStyles.css?v=<?php echo time(); ?>">   
 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'includes/menu.php'; ?>

    <div class="container main-content" style="margin-top: 30px;">
        
        <div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
            <button class="btn btn-primary-green" onclick="toggleRegistro()" id="mainActionButton">
                <i class="fas fa-plus-circle"></i> Nuevo Curso
            </button>
        </div>

        <section id="seccionRegistro">
            <div class="form-content-inner">
                <i class="fas fa-chevron-up btn-close-slider" onclick="toggleRegistro()"></i>
                <div class="form-header-inner">
                    <h2><i class="fas fa-book-open" style="color: #10b981;"></i> Registrar Nuevo Curso</h2>
                </div>
                
                <form id="formCurso" action="index.php?accion=guardar_curso" method="POST" class="form-inline-grid">
                    
                    <div class="field-group">
                        <label for="codigo_curso">Código del Curso</label>
                        <input type="text" name="codigo_curso" id="codigo_curso" placeholder="Ej: CERT-001" maxlength="50" required>
                    </div>

                    <div class="field-group">
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; outline: none; height: 41px;">
                            <option value="">Seleccione...</option>
                            <option value="certificados">Certificado</option>
                            <option value="diplomados">Diplomado</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="horas_totales">Horas Totales</label>
                        <input type="number" name="horas_totales" id="horas_totales" placeholder="Ej: 40" min="1" required>
                    </div>
                    
                    <div class="field-group" style="grid-column: 1 / -1;">
                        <label for="nombre_curso">Nombre del Curso</label>
                        <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Ej: Especialización en Gestión Pública" required>
                    </div>
                    
                    <div class="botones-form" style="grid-column: 1 / -1; justify-content: flex-end; margin-top: 10px;">
                        <button type="button" class="btn-cancel" onclick="toggleRegistro()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn-save-ajax" id="btnSubmit">
                            <i class="fas fa-save"></i> Guardar Curso
                        </button>
                    </div>
                </form>
                <div id="ajaxResponse" style="margin-top: 15px; font-weight: 600; display: none;"></div>
            </div>
        </section>

        <div class="directorio-title-container">
            <h2><i class="fas fa-graduation-cap" style="color: #10b981;"></i> Catálogo de Cursos</h2>
            <p style="color: #64748b; margin-top: 5px;">Visualización y búsqueda de certificados y diplomados registrados.</p>
        </div>

        <div class="search-bar">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscadorTabla" placeholder="Buscar por Código o Nombre..." class="search-input">
            </div>
            <button class="btn btn-outline"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table" id="tablaCursos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Nombre del Curso</th>
                            <th>Tipo</th>
                            <th>Horas</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($cursos) && !empty($cursos)): ?>
                            <?php foreach ($cursos as $c): ?>
                            <tr class="fila-dato">
                                <td><?= $c['id_curso'] ?></td>
                                <td><strong><?= htmlspecialchars($c['codigo_curso']) ?></strong></td>
                                <td><?= htmlspecialchars($c['nombre_curso']) ?></td>
                                <td>
                                    <?php if($c['tipo'] == 'certificados'): ?>
                                        <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Certificado</span>
                                    <?php else: ?>
                                        <span style="background: #dcfce7; color: #15803d; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Diplomado</span>
                                    <?php endif; ?>
                                </td>
                                <td><i class="far fa-clock" style="color: #64748b;"></i> <?= $c['horas_totales'] ?> hrs</td>
                                <td class="text-right">
                                    <button class="btn-icon btn-edit" title="Editar"><i class="fas fa-pen"></i></button>
                                    <button class="btn-icon btn-delete" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="fila-dato">
                                <td colspan="6" style="text-align: center; color: #64748b; padding: 20px;">No hay cursos registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <p>Mostrando <span id="displayCount" class="highlight-green"><?= isset($cursos) ? count($cursos) : 0 ?></span> cursos registrados.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica para el buscador de la tabla
            const inputBusqueda = document.getElementById('buscadorTabla');
            const filas = document.querySelectorAll('#tablaCursos tbody tr.fila-dato');
            const contadorVisible = document.getElementById('displayCount');

            if(inputBusqueda && filas.length > 0) {
                inputBusqueda.addEventListener('keyup', function(e) {
                    const texto = e.target.value.toLowerCase();
                    let visibles = 0;

                    filas.forEach(fila => {
                        // Evitamos buscar en la fila de "No hay cursos registrados" si existe
                        if(fila.cells.length > 1) { 
                            const contenidoFila = fila.textContent.toLowerCase();
                            if(contenidoFila.includes(texto)) {
                                fila.classList.remove('oculto-por-busqueda');
                                visibles++;
                            } else {
                                fila.classList.add('oculto-por-busqueda');
                            }
                        }
                    });

                    if(contadorVisible) {
                        contadorVisible.textContent = visibles;
                    }
                });
            }
        });

        // Lógica para abrir/cerrar el formulario slider
        function toggleRegistro() {
            const seccion = document.getElementById('seccionRegistro');
            const btnPrincipal = document.getElementById('mainActionButton');

            // Alterna la clase "abierto"
            seccion.classList.toggle('abierto');

            // Cambiar el texto y color del botón principal según el estado
            if (seccion.classList.contains('abierto')) {
                btnPrincipal.innerHTML = '<i class="fas fa-times"></i> Cancelar Registro';
                btnPrincipal.style.backgroundColor = '#64748b'; 
            } else {
                btnPrincipal.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Curso';
                btnPrincipal.style.backgroundColor = ''; 
            }
        }
    </script>
</body>
</html>