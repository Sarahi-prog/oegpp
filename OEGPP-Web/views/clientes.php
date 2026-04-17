<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Personal - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/clientesStyles.css?v=<?= time(); ?>">
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
                    <h2><i class="fas fa-users-cog"></i> Gestión de Trabajadores</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra la lista de trabajadores.</p>
                </div>
            </div>
     
        </div>

        <div class="dashboard-wrapper">
            
            <div id="seccionRegistro">
                <div class="side-panel">
                    <h3 style="margin-top: 0; margin-bottom: 20px;"><i class="fas fa-plus-circle"></i> Datos del Registro</h3>
                    <form id="formTrabajadorAjax" action="index.php?accion=guardar_cliente" method="POST">
                        <div class="form-vertical-stack">
                            <div class="field-group">
                                <label>DNI</label>
                                <input type="text" name="dni" required placeholder="00000000" maxlength="8">
                            </div>
                            <div class="field-group">
                                <label>Nombres</label>
                                <input type="text" name="nombres" required>
                            </div>
                            <div class="field-group">
                                <label>Apellidos</label>
                                <input type="text" name="apellidos" required>
                            </div>
                            <div class="field-group">
                                <label>Correo</label>
                                <input type="email" name="correo" placeholder="usuario@correo.com">
                            </div>
                            <div class="field-group">
                                <label>Celular</label>
                                <input type="text" name="celular" placeholder="000 000 000">
                            </div>
                            <div class="field-group">
                                <label>Área</label>
                                <input type="text" name="area" placeholder="Ej. Ingeniería">
                            </div>
                            <div class="field-group">
                                <label>Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary-green" style="width: 100%; justify-content: center; margin-top: 10px;">
                                <i class="fas fa-save"></i> Guardar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-section">
                <div class="search-bar">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="buscadorTabla" class="search-input" placeholder="Buscar por DNI, Nombre o Área...">
                    </div>
                    <button class="btn btn-outline"><i class="fas fa-file-export"></i> Exportar</button>
                </div>

                <div class="table-card">
                    <div class="table-container">
                        <table class="data-table" id="tablaPrincipal">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>DNI</th>
                                    <th>NOMBRES</th>
                                    <th>APELLIDOS</th>
                                    <th>CORREO</th>
                                    <th>CELULAR</th>
                                    <th>AREA</th>
                                    <th>ESTADO</th> 
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1; 
                                if (!empty($trabajadores)):
                                    foreach ($trabajadores as $t): 
                                        $id_trabajador = $t->getIdTrabajador();
                                        $dni = $t->getDni() ?? '';
                                        $nombres = $t->getNombres() ?? '';
                                        $apellidos = $t->getApellidos() ?? '';
                                        $correo = $t->getCorreo() ?? '';
                                        $celular = $t->getCelular() ?? '';
                                        $area = $t->getArea() ?? '';
                                        $estado = $t->getEstado() ?? '';
                                ?>
                                <tr class="fila-cliente">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><strong><?= htmlspecialchars($dni) ?></strong></td>
                                    <td><?= htmlspecialchars($nombres) ?></td>
                                    <td><?= htmlspecialchars($apellidos) ?></td>
                                    <td><?= htmlspecialchars($correo) ?></td>
                                    <td><?= htmlspecialchars($celular) ?></td>
                                    <td><?= htmlspecialchars($area) ?></td>
                                    <td><?= htmlspecialchars($estado) ?></td>
                                    <td><?= htmlspecialchars($estado)?></td>
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
                                            <i class="fas fa-folder-open" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                            <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin registros encontrados</h4>
                                            <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Utiliza el botón superior para agregar a tu primer trabajador.</p>
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
    
    <script src="public/UniversalScript.js?v=<?= time(); ?>"></script>
</body>
</html>