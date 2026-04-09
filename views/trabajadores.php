<?php

// Verificar si el usuario está logueado
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php?accion=login");
    exit();
}

// Definir página actual para el menú
if (!isset($pagina_actual)) {
    $pagina_actual = 'trabajadores';
}

// Incluir configuración de base de datos
$config_path = __DIR__ . '/../config/DB.php';
if (!file_exists($config_path)) {
    die("Error: Archivo de configuración no encontrado en " . $config_path);
}
require_once $config_path;

// Verificar que la clase DB existe
if (!class_exists('DB')) {
    die("Error: Clase DB no encontrada");
}

// Crear conexión
$db = DB::conectar();
if (!$db) {
    die("Error: No se pudo establecer conexión con la base de datos");
}

// Consultar trabajadores
$query = "SELECT id_trabajador, dni, nombres, apellidos, area, celular 
          FROM trabajadores 
          ORDER BY apellidos ASC";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $trabajadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $trabajadores = [];
    error_log("Error al cargar trabajadores: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directorio de Trabajadores - OEGPP</title>
    <link rel="stylesheet" href="public/dashStyles.css?v=<?php echo time(); ?>">   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos adicionales para la tabla */
        .oculto-por-busqueda {
            display: none !important;
        }
    </style>
</head>
<body>

    <?php 
    // CORREGIDO: ruta del menu
    $menu_path = __DIR__ . '/includes/menu.php';
    if (file_exists($menu_path)) {
        include $menu_path; 
    } else {
        echo "<!-- Menu no encontrado en: " . $menu_path . " -->";
    }
    ?>

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
            <button class="btn btn-outline" id="btnExportarPDF"><i class="fas fa-download"></i> Exportar PDF</button>
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
                            <tr class="fila-dato" data-id="<?= $t['id_trabajador'] ?>">
                                <td><?= htmlspecialchars($t['id_trabajador']) ?></td>
                                <td><strong><?= htmlspecialchars($t['dni']) ?></strong></td>
                                <td><?= htmlspecialchars($t['nombres']) ?></td>
                                <td><?= htmlspecialchars($t['apellidos']) ?></td>
                                <td>
                                    <div style="font-size: 0.85rem; color: #64748b;">
                                        <?= !empty($t['area']) ? htmlspecialchars($t['area']) : 'Sin área' ?><br>
                                        <i class="fas fa-phone-alt" style="font-size: 0.75rem;"></i> 
                                        <?= !empty($t['celular']) ? htmlspecialchars($t['celular']) : '-' ?>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <button class="btn-icon btn-edit" onclick="editarTrabajador(<?= $t['id_trabajador'] ?>)" title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" onclick="eliminarTrabajador(<?= $t['id_trabajador'] ?>)" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="fila-vacia">
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
        // Función para buscar en la tabla
        document.addEventListener('DOMContentLoaded', function() {
            const inputBusqueda = document.getElementById('buscadorTabla');
            const filas = document.querySelectorAll('#tablaTrabajadores tbody tr.fila-dato');
            const contadorVisible = document.getElementById('displayCount');

            if(inputBusqueda && filas.length > 0) {
                inputBusqueda.addEventListener('keyup', function(e) {
                    const texto = e.target.value.toLowerCase();
                    let visibles = 0;

                    filas.forEach(fila => {
                        const dni = fila.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                        const nombres = fila.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                        const apellidos = fila.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                        
                        const contenidoFila = `${dni} ${nombres} ${apellidos}`;
                        
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
            }

            // Botón de exportar PDF
            const btnPDF = document.getElementById('btnExportarPDF');
            if(btnPDF) {
                btnPDF.addEventListener('click', function() {
                    exportarPDF();
                });
            }
        });

        // Función para editar trabajador
        function editarTrabajador(id) {
            if(confirm('¿Deseas editar este trabajador?')) {
                window.location.href = `editar_trabajador.php?id=${id}`;
            }
        }

        // Función para eliminar trabajador
        function eliminarTrabajador(id) {
            if(confirm('¿Estás seguro de eliminar este trabajador? Esta acción no se puede deshacer.')) {
                fetch('api_trabajadores.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Trabajador eliminado correctamente');
                        location.reload();
                    } else {
                        alert('Error al eliminar: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al conectar con el servidor');
                });
            }
        }

        // Función para exportar a PDF
        function exportarPDF() {
            const filasVisibles = document.querySelectorAll('#tablaTrabajadores tbody tr.fila-dato:not(.oculto-por-busqueda)');
            
            if(filasVisibles.length === 0) {
                alert('No hay datos para exportar');
                return;
            }
            
            let contenido = '<html><head><meta charset="UTF-8"><title>Directorio de Trabajadores</title>';
            contenido += '<style>';
            contenido += 'table { border-collapse: collapse; width: 100%; }';
            contenido += 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
            contenido += 'th { background-color: #10b981; color: white; }';
            contenido += '</style></head><body>';
            contenido += '<h1>Directorio de Trabajadores</h1>';
            contenido += '<p>Fecha: ' + new Date().toLocaleDateString() + '</p>';
            contenido += '<table>';
            contenido += '<tr><th>ID</th><th>DNI</th><th>Nombres</th><th>Apellidos</th><th>Área</th><th>Celular</th></tr>';
            
            filasVisibles.forEach(fila => {
                const id = fila.querySelector('td:nth-child(1)')?.textContent || '';
                const dni = fila.querySelector('td:nth-child(2)')?.textContent || '';
                const nombres = fila.querySelector('td:nth-child(3)')?.textContent || '';
                const apellidos = fila.querySelector('td:nth-child(4)')?.textContent || '';
                const areaCelular = fila.querySelector('td:nth-child(5)')?.textContent || '';
                const [area, celular] = areaCelular.split('\n');
                
                contenido += `<tr>
                    <td>${id}</td>
                    <td>${dni}</td>
                    <td>${nombres}</td>
                    <td>${apellidos}</td>
                    <td>${area.trim()}</td>
                    <td>${celular?.trim() || '-'}</td>
                </tr>`;
            });
            
            contenido += '</table></body></html>';
            
            const ventana = window.open('', '_blank');
            ventana.document.write(contenido);
            ventana.document.close();
            ventana.print();
        }
    </script>
</body>
</html>