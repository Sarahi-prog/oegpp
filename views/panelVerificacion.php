<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Verificación de Administradores</title>
    <link rel="stylesheet" href="public/dashStyles.css">
    <style>
        .panel-verificacion {
            padding: 20px;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        
        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            color: #007bff;
            border-bottom-color: #007bff;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .tabla-administradores {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .tabla-administradores th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .tabla-administradores td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        
        .tabla-administradores tr:hover {
            background-color: #f5f5f5;
        }
        
        .tabla-administradores tr:last-child td {
            border-bottom: none;
        }
        
        .estado-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .estado-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .estado-verificado {
            background-color: #d4edda;
            color: #155724;
        }
        
        .acciones {
            display: flex;
            gap: 5px;
        }
        
        .btn-accion {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }
        
        .btn-aprobar {
            background-color: #28a745;
            color: white;
        }
        
        .btn-aprobar:hover {
            background-color: #218838;
        }
        
        .btn-rechazar {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-rechazar:hover {
            background-color: #c82333;
        }
        
        .sin-registros {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .badge-contador {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            margin-left: 5px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        
        .modal-body {
            margin-bottom: 20px;
        }
        
        .modal-body textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            resize: vertical;
            min-height: 80px;
        }
        
        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .btn-cancelar {
            padding: 8px 16px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-cancelar:hover {
            background-color: #5a6268;
        }
        
        .btn-confirmar {
            padding: 8px 16px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-confirmar:hover {
            background-color: #c82333;
        }
        
        .alerta {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alerta.exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alerta.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="panel-verificacion">
        <h1>📋 Verificación de Administradores</h1>

        <div id="alerta" class="alerta" style="display: none;"></div>

        <div class="tabs">
            <button class="tab-btn active" onclick="cambiarTab('pendientes')">
                Pendientes <span class="badge-contador"><?= $total_pendientes; ?></span>
            </button>
            <button class="tab-btn" onclick="cambiarTab('verificados')">
                Verificados
            </button>
        </div>

        <!-- TAB: PENDIENTES -->
        <div id="pendientes" class="tab-content active">
            <?php if (empty($pendientes)): ?>
                <div class="sin-registros">
                    ✓ No hay administradores pendientes de verificación
                </div>
            <?php else: ?>
                <table class="tabla-administradores">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendientes as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['id_admin']); ?></td>
                                <td><?= htmlspecialchars($admin['usuario']); ?></td>
                                <td><?= htmlspecialchars($admin['correo']); ?></td>
                                <td><?= htmlspecialchars($admin['rol']); ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($admin['fecha_creacion'] ?? 'now')); ?></td>
                                <td>
                                    <div class="acciones">
                                        <button class="btn-accion btn-aprobar" onclick="aprobarAdmin(<?= $admin['id_admin']; ?>)">
                                            ✓ Aprobar
                                        </button>
                                        <button class="btn-accion btn-rechazar" onclick="mostrarModalRechazar(<?= $admin['id_admin']; ?>)">
                                            ✗ Rechazar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- TAB: VERIFICADOS -->
        <div id="verificados" class="tab-content">
            <?php if (empty($verificados)): ?>
                <div class="sin-registros">
                    ℹ No hay administradores verificados todavía
                </div>
            <?php else: ?>
                <table class="tabla-administradores">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($verificados as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin['id_admin']); ?></td>
                                <td><?= htmlspecialchars($admin['usuario']); ?></td>
                                <td><?= htmlspecialchars($admin['correo']); ?></td>
                                <td><?= htmlspecialchars($admin['rol']); ?></td>
                                <td><span class="estado-badge estado-verificado">✓ Verificado</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- MODAL RECHAZAR -->
    <div id="modalRechazar" class="modal">
        <div class="modal-content">
            <div class="modal-header">Rechazar Administrador</div>
            <div class="modal-body">
                <textarea id="motivoRechazo" placeholder="Motivo del rechazo (opcional)"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button class="btn-confirmar" onclick="confirmarRechazo()">Confirmar</button>
            </div>
        </div>
    </div>

    <script>
        let idAdminParaRechazar = null;

        function cambiarTab(tabName) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Mostrar tab seleccionado
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        function mostrarAlerta(mensaje, tipo) {
            const alerta = document.getElementById('alerta');
            alerta.textContent = mensaje;
            alerta.className = 'alerta ' + tipo;
            alerta.style.display = 'block';

            setTimeout(() => {
                alerta.style.display = 'none';
            }, 5000);
        }

        async function aprobarAdmin(idAdmin) {
            if (!confirm('¿Estás seguro de que quieres aprobar este administrador?')) return;

            const formData = new FormData();
            formData.append('id_admin', idAdmin);

            try {
                const response = await fetch('index.php?accion=aprobarAdmin', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('✓ ' + data.message, 'exito');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    mostrarAlerta('✗ ' + data.message, 'error');
                }
            } catch (error) {
                mostrarAlerta('✗ Error en la solicitud', 'error');
            }
        }

        function mostrarModalRechazar(idAdmin) {
            idAdminParaRechazar = idAdmin;
            document.getElementById('motivoRechazo').value = '';
            document.getElementById('modalRechazar').classList.add('active');
        }

        function cerrarModal() {
            document.getElementById('modalRechazar').classList.remove('active');
            idAdminParaRechazar = null;
        }

        async function confirmarRechazo() {
            if (!idAdminParaRechazar) return;

            const motivo = document.getElementById('motivoRechazo').value;
            const formData = new FormData();
            formData.append('id_admin', idAdminParaRechazar);
            formData.append('motivo', motivo);

            try {
                const response = await fetch('index.php?accion=rechazarAdmin', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    mostrarAlerta('✓ ' + data.message, 'exito');
                    cerrarModal();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    mostrarAlerta('✗ ' + data.message, 'error');
                }
            } catch (error) {
                mostrarAlerta('✗ Error en la solicitud', 'error');
            }
        }

        // Cerrar modal al hacer click fuera
        document.getElementById('modalRechazar').addEventListener('click', (e) => {
            if (e.target.id === 'modalRechazar') cerrarModal();
        });
    </script>
</body>
</html>
