<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Trabajadores - OEGPP</title>
    <link rel="stylesheet" href="public/dashStyles.css?v=<?php echo time(); ?>">   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'includes/menu.php'; ?>

    <div class="container main-content" style="margin-top: 40px;">
        
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div class="section-title" style="margin: 0;">
                <h2><i class="fas fa-users" style="color: #10b981; margin-right: 10px;"></i> Directorio de Trabajadores</h2>
                <p style="margin-top: 5px;">Listado completo de trabajadores registrados en el sistema.</p>
            </div>
            <button class="btn btn-primary-green" onclick="window.location.href='index.php?accion=nueva_asignacion'">
                <i class="fas fa-plus"></i> Nueva Asignación
            </button>
        </div>

        <div class="search-bar" style="margin-top: 20px;">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="buscadorTabla" placeholder="Buscar por DNI, Nombre o Apellido..." class="search-input">
            </div>
            <button class="btn btn-outline"><i class="fas fa-download"></i> Exportar PDF</button>
        </div>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table" id="tablaTrabajadores">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Área / Celular</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($trabajadores) && is_array($trabajadores)): ?>
                            <?php foreach ($trabajadores as $t): ?>
                            <tr class="fila-dato">
                                <td><?= htmlspecialchars($t['id_trabajador']) ?></td>
                                <td><strong><?= htmlspecialchars($t['dni']) ?></strong></td>
                                <td><?= htmlspecialchars($t['nombres']) ?></td>
                                <td><?= htmlspecialchars($t['apellidos']) ?></td>
                                <td>
                                    <div style="font-size: 0.85rem; color: #64748b;">
                                        <?= !empty($t['area']) ? htmlspecialchars($t['area']) : 'Sin área' ?><br>
                                        <i class="fas fa-phone-alt" style="font-size: 0.75rem;"></i> <?= !empty($t['celular']) ? htmlspecialchars($t['celular']) : '-' ?>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button class="btn-icon btn-edit" title="Editar"><i class="fas fa-pen"></i></button>
                                    <button class="btn-icon btn-delete" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 50px 20px;">
                                    <i class="fas fa-folder-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 15px; display:block;"></i>
                                    <h3 style="color: #475569; margin-bottom: 5px;">No hay trabajadores registrados</h3>
                                    <p style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 20px;">Aún no has agregado a ningún trabajador a la base de datos.</p>
                                    <button class="btn btn-primary-green" onclick="window.location.href='index.php?accion=nueva_asignacion'">
                                        Registrar el primero
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <p class="footer-text">
                    Mostrando <span class="highlight-green" id="displayCount"><?= is_array($trabajadores) ? count($trabajadores) : 0 ?></span> registros
                </p>
            </div>
        </div>
    </div> 

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputBusqueda = document.getElementById('buscadorTabla');
            const filas = document.querySelectorAll('#tablaTrabajadores tbody tr.fila-dato');
            const contadorVisible = document.getElementById('displayCount');

            // Si no hay input de búsqueda (porque la tabla está vacía), no hacemos nada
            if(!inputBusqueda) return;

            inputBusqueda.addEventListener('keyup', function(e) {
                const texto = e.target.value.toLowerCase();
                let visibles = 0;

                filas.forEach(fila => {
                    const contenidoFila = fila.textContent.toLowerCase();
                    if(contenidoFila.includes(texto)) {
                        fila.classList.remove('oculto-por-busqueda');
                        visibles++;
                    } else {
                        fila.classList.add('oculto-por-busqueda');
                    }
                });

                if(contadorVisible) {
                    contadorVisible.textContent = visibles;
                }
            });
        });
    </script>
</body>
</html>