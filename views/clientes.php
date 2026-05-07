<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de clientes</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="public/clientesStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?php echo time(); ?>">

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
                    <h2><i class="fas fa-users-cog"></i> Gestión de Clientes</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Administra la lista de clientes.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            <div id="seccionRegistro">
                <div class="side-panel">
                    <h3 id="form-title"><i class="fas fa-plus-circle"></i> Datos del Registro</h3>
                    <form id="formCliente" action="index.php?accion=guardar_cliente" method="POST">
                        <div class="form-vertical-stack">
                            <input type="hidden" name="id_cliente" id="id_cliente_form" value="">
                            
                            <div class="field-group">
                                <label>DNI</label>
                                <input type="text" name="dni" id="dni_input" required placeholder="00000000" maxlength="8">
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

                            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 15px;">
                                <button type="submit" id="btn-submit-form" class="btn-primary-green">
                                    <i class="fas fa-save"></i> 
                                    <span>Guardar Cliente</span>
                                </button>

                                <button type="button" id="btn-cancelar" onclick="resetearFormulario()" 
                                        class="btn btn-secondary" style="display: none; background-color: #64748b; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer;">
                                    <i class="fas fa-times"></i>
                                </button>
                 
                            </div>

                            <a href="index.php?accion=buscar_dni" 
                                    class="btn-ir-gestion" 
                                    style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 8px; padding: 12px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 8px; font-size: 0.85rem; font-weight: 500; transition: background 0.3s;">
                                        <i class="fas fa-graduation-cap"></i> Ir a Gestión Capacitaciones
                            </a>
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
                    <button class="btn-exportar" onclick="exportarCursos()">
                        <i class="fas fa-file-export"></i>
                        <span>Exportar Datos</span>
                    </button>
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
                                    <th class="email-column">CORREO</th>
                                    <th>CELULAR</th>
                                    <th>AREA</th>
                                    <th>ESTADO</th> 
                                    <th style="text-align: center;">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTabla">
                                <?php 
                                    $i = 1; 
                                    if (!empty($clientes)):
                                        foreach ($clientes as $c): 
                                ?>
                                <tr class="fila-cliente">
                                    <td class="id-column"><?= $i++ ?></td>
                                    <td><strong><?= htmlspecialchars($c->dni) ?></strong></td>
                                    <td><?= htmlspecialchars($c->nombres) ?></td>
                                    <td><?= htmlspecialchars($c->apellidos) ?></td>
                                    <td class="email-column"><?= htmlspecialchars($c->correo) ?></td>
                                    <td><?= htmlspecialchars($c->celular) ?></td>
                                    <td><?= htmlspecialchars($c->area) ?></td>
                                    <td><?= htmlspecialchars($c->estado) ?></td>
                                    
                                    <td style="text-align: center; white-space: nowrap;">
                                        <button class="btn-icon btn-edit" 
                                                title="Editar" 
                                                onclick='editarCliente(<?= json_encode($c) ?>)'>
                                            <i class="fas fa-edit" style="color: #4a90e2;"></i>
                                        </button>

                                        <button class="btn-icon btn-delete" 
                                                title="Eliminar" 
                                                onclick="confirmarEliminar(<?= $c->id_cliente ?>)">
                                            <i class="fas fa-trash" style="color: #e24a4a;"></i>
                                        </button>
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
                                            <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Utiliza el panel lateral para agregar a tu primer cliente.</p>
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
    <script src="public/UniversalScript.js?v=<?= time(); ?>"></script>
    <script src="public/clientesScript.js?v=<?= time(); ?>"></script>

</body>
</html>