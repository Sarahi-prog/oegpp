<?php
// Verificar si el usuario está logueado y es admin general
if(!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_general']) || $_SESSION['admin_general'] !== true) {
    header("Location: index.php?accion=login");
    exit();
}

require_once __DIR__ . '/../models/SolicitudesRegistroModel.php';
$model = new SolicitudesRegistroModel();
$estadisticas = $model->obtenerEstadisticas();
$solicitudes = $model->obtenerPendientes();

// Definir página actual para el menú
$pagina_actual = 'autorizacion';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorizar Usuarios - OEGPP</title>
    <link rel="stylesheet" href="public/dashStyles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .tabs-container {
            display: flex;
            gap: 10px;
            margin: 20px 0;
            border-bottom: 2px solid #ecf0f1;
        }

        .tab-btn {
            padding: 12px 20px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: #7f8c8d;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #27ae60;
            border-bottom-color: #27ae60;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }

        .stat-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card.pendientes {
            background: linear-gradient(135deg, #fff5e6 0%, #ffe6cc 100%);
            border-left: 4px solid #f39c12;
        }

        .stat-card.aprobados {
            background: linear-gradient(135deg, #e6f7f2 0%, #cceee6 100%);
            border-left: 4px solid #27ae60;
        }

        .stat-card.rechazados {
            background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
            border-left: 4px solid #e74c3c;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            margin: 10px 0;
            color: #2c3e50;
        }

        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }

        .solicitud-card {
            background: white;
            border: 1px solid #ecf0f1;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .solicitud-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .solicitud-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .solicitud-info {
            flex: 1;
        }

        .solicitud-usuario {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .solicitud-correo {
            color: #3498db;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .solicitud-detalles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 15px 0;
            padding: 15px 0;
            border-top: 1px solid #ecf0f1;
            border-bottom: 1px solid #ecf0f1;
        }

        .detalle-item {
            font-size: 13px;
        }

        .detalle-label {
            color: #7f8c8d;
            font-weight: 600;
            display: block;
            margin-bottom: 3px;
        }

        .detalle-valor {
            color: #2c3e50;
            font-weight: 500;
        }

        .solicitud-fecha {
            font-size: 12px;
            color: #95a5a6;
            margin-bottom: 10px;
        }

        .acciones-solicitud {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-accion {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-aprobar {
            background: #27ae60;
            color: white;
        }

        .btn-aprobar:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        .btn-rechazar {
            background: #e74c3c;
            color: white;
        }

        .btn-rechazar:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .btn-accion:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .mensaje {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
            animation: slideIn 0.3s ease-out;
        }

        .mensaje.success {
            display: block;
            background: #d5f4e6;
            color: #27ae60;
            border-left: 4px solid #27ae60;
        }

        .mensaje.error {
            display: block;
            background: #fadbd8;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
        }

        .mensaje.info {
            display: block;
            background: #d6eaf8;
            color: #3498db;
            border-left: 4px solid #3498db;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease-out;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #7f8c8d;
        }

        .modal-body {
            margin: 20px 0;
        }

        .modal-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ecf0f1;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-modal {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-modal.confirm {
            background: #e74c3c;
            color: white;
        }

        .btn-modal.confirm:hover {
            background: #c0392b;
        }

        .btn-modal.cancel {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-modal.cancel:hover {
            background: #bdc3c7;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #7f8c8d;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .estado-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .estado-pendiente {
            background: #fff5e6;
            color: #f39c12;
        }

        .estado-aprobado {
            background: #e6f7f2;
            color: #27ae60;
        }

        .estado-rechazado {
            background: #ffe6e6;
            color: #e74c3c;
        }

        .historial-filtro {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filtro-btn {
            padding: 8px 16px;
            border: 2px solid #ecf0f1;
            background: white;
            color: #7f8c8d;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filtro-btn.active {
            background: #27ae60;
            color: white;
            border-color: #27ae60;
        }

        .filtro-btn:hover {
            border-color: #27ae60;
        }
    </style>
</head>
<body>
    <?php include 'includes/menu.php'; ?>

    <div class="container main-content">
        <div class="section-header">
            <div class="section-title">
                <h2><i class="fas fa-user-check"></i> Autorización de Usuarios</h2>
                <p>Gestiona solicitudes de acceso al sistema</p>
            </div>
        </div>

        <div id="mensaje-general" class="mensaje"></div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card pendientes" onclick="cambiarTab('pendientes')">
                <div class="stat-number"><?php echo $estadisticas['pendientes'] ?? 0; ?></div>
                <div class="stat-label">📋 Pendientes</div>
            </div>
            <div class="stat-card aprobados" onclick="cambiarTab('aprobados')">
                <div class="stat-number"><?php echo $estadisticas['aprobados'] ?? 0; ?></div>
                <div class="stat-label">✅ Aprobados</div>
            </div>
            <div class="stat-card rechazados" onclick="cambiarTab('rechazados')">
                <div class="stat-number"><?php echo $estadisticas['rechazados'] ?? 0; ?></div>
                <div class="stat-label">❌ Rechazados</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #e8daef 0%, #d7bde2 100%); border-left: 4px solid #8e44ad;">
                <div class="stat-number"><?php echo $estadisticas['total'] ?? 0; ?></div>
                <div class="stat-label">📊 Total</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
            <button class="tab-btn active" onclick="cambiarTab('pendientes')">
                <i class="fas fa-hourglass-half"></i> Pendientes
            </button>
            <button class="tab-btn" onclick="cambiarTab('historial')">
                <i class="fas fa-history"></i> Historial
            </button>
        </div>

        <!-- Tab: Pendientes -->
        <div id="tab-pendientes" class="tab-content active">
            <div id="solicitudes-pendientes">
                <?php if (count($solicitudes) > 0): ?>
                    <?php foreach ($solicitudes as $solicitud): ?>
                        <div class="solicitud-card" data-id="<?php echo $solicitud['id_solicitud']; ?>">
                            <div class="solicitud-header">
                                <div class="solicitud-info">
                                    <div class="solicitud-usuario">
                                        <i class="fas fa-user-circle"></i>
                                        <?php echo htmlspecialchars($solicitud['usuario']); ?>
                                    </div>
                                    <div class="solicitud-correo">
                                        <i class="fas fa-envelope"></i>
                                        <?php echo htmlspecialchars($solicitud['correo']); ?>
                                    </div>
                                    <div class="solicitud-fecha">
                                        <i class="fas fa-calendar"></i>
                                        Solicitado: <?php echo date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="solicitud-detalles">
                                <div class="detalle-item">
                                    <span class="detalle-label">Nombres</span>
                                    <span class="detalle-valor"><?php echo htmlspecialchars($solicitud['nombres'] ?? 'No especificado'); ?></span>
                                </div>
                                <div class="detalle-item">
                                    <span class="detalle-label">Apellidos</span>
                                    <span class="detalle-valor"><?php echo htmlspecialchars($solicitud['apellidos'] ?? 'No especificado'); ?></span>
                                </div>
                                <div class="detalle-item">
                                    <span class="detalle-label">DNI</span>
                                    <span class="detalle-valor"><?php echo htmlspecialchars($solicitud['dni'] ?? 'No especificado'); ?></span>
                                </div>
                                <div class="detalle-item">
                                    <span class="detalle-label">Celular</span>
                                    <span class="detalle-valor"><?php echo htmlspecialchars($solicitud['celular'] ?? 'No especificado'); ?></span>
                                </div>
                                <div class="detalle-item">
                                    <span class="detalle-label">Área</span>
                                    <span class="detalle-valor"><?php echo htmlspecialchars($solicitud['area'] ?? '-'); ?></span>
                                </div>
                            </div>

                            <div class="acciones-solicitud">
                                <button class="btn-accion btn-aprobar" onclick="aprobarSolicitud(<?php echo $solicitud['id_solicitud']; ?>)">
                                    <i class="fas fa-check"></i> Aprobar
                                </button>
                                <button class="btn-accion btn-rechazar" onclick="abrirModalRechazo(<?php echo $solicitud['id_solicitud']; ?>, '<?php echo htmlspecialchars($solicitud['usuario']); ?>')">
                                    <i class="fas fa-times"></i> Rechazar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <div class="empty-state-title">No hay solicitudes pendientes</div>
                        <p>Todos los usuarios han sido revisados</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab: Historial -->
        <div id="tab-historial" class="tab-content">
            <div class="historial-filtro">
                <button class="filtro-btn active" onclick="filtrarHistorial('todos')">
                    <i class="fas fa-list"></i> Todos
                </button>
                <button class="filtro-btn" onclick="filtrarHistorial('aprobado')">
                    <i class="fas fa-check"></i> Aprobados
                </button>
                <button class="filtro-btn" onclick="filtrarHistorial('rechazado')">
                    <i class="fas fa-times"></i> Rechazados
                </button>
            </div>
            <div id="historial-container"></div>
        </div>
    </div>

    <!-- Modal de Rechazo -->
    <div id="modalRechazo" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span><i class="fas fa-exclamation-circle"></i> Rechazar Solicitud</span>
                <button class="modal-close" onclick="cerrarModalRechazo()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="color: #7f8c8d; margin-bottom: 15px;">
                    ¿Estás seguro de rechazar la solicitud de <strong><span id="usuario-rechazo"></span></strong>?
                </p>
                <label style="color: #2c3e50; font-weight: 600; display: block; margin-bottom: 8px;">
                    Motivo del rechazo (opcional):
                </label>
                <textarea id="motivoRechazo" class="modal-input" placeholder="Explica brevemente por qué rechazas esta solicitud..." style="resize: vertical; min-height: 100px;"></textarea>
            </div>
            <div class="modal-buttons">
                <button class="btn-modal cancel" onclick="cerrarModalRechazo()">Cancelar</button>
                <button class="btn-modal confirm" onclick="confirmarRechazo()">Rechazar</button>
            </div>
        </div>
    </div>

    <script>
        let solicitudRechazoId = null;

        // Cambiar entre pestañas
        function cambiarTab(tab) {
            // Ocultar todas las pestañas
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('active');
            });

            // Mostrar pestaña seleccionada
            document.getElementById('tab-' + tab).classList.add('active');
            event.target.closest('.tab-btn').classList.add('active');

            if (tab === 'historial') {
                cargarHistorial('todos');
            }
        }

        // Cargar historial
        async function cargarHistorial(estado) {
            try {
                const response = await fetch('index.php?accion=obtenerHistorialSolicitudes&estado=' + estado);
                const result = await response.json();

                if (result.success) {
                    const container = document.getElementById('historial-container');
                    
                    if (result.data && result.data.length > 0) {
                        container.innerHTML = result.data.map(solicitud => `
                            <div class="solicitud-card">
                                <div class="solicitud-header">
                                    <div class="solicitud-info">
                                        <div class="solicitud-usuario">
                                            <i class="fas fa-user-circle"></i>
                                            ${solicitud.usuario}
                                        </div>
                                        <div class="solicitud-correo">
                                            <i class="fas fa-envelope"></i>
                                            ${solicitud.correo}
                                        </div>
                                        <span class="estado-badge estado-${solicitud.estado}">
                                            ${solicitud.estado.toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                                <div class="solicitud-detalles">
                                    <div class="detalle-item">
                                        <span class="detalle-label">Solicitado</span>
                                        <span class="detalle-valor">${new Date(solicitud.fecha_solicitud).toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                                    </div>
                                    <div class="detalle-item">
                                        <span class="detalle-label">Autorizado por</span>
                                        <span class="detalle-valor">${solicitud.autorizado_por_usuario || '-'}</span>
                                    </div>
                                    ${solicitud.motivo_rechazo ? `
                                        <div class="detalle-item" style="grid-column: 1 / -1;">
                                            <span class="detalle-label">Motivo rechazo</span>
                                            <span class="detalle-valor">${solicitud.motivo_rechazo}</span>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = `
                            <div class="empty-state">
                                <div class="empty-state-icon">📭</div>
                                <div class="empty-state-title">Sin registros</div>
                                <p>No hay solicitudes en este estado</p>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarMensaje('Error cargando historial', 'error');
            }
        }

        // Filtrar historial
        function filtrarHistorial(estado) {
            document.querySelectorAll('.filtro-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            cargarHistorial(estado === 'todos' ? '' : estado);
        }

        // Aprobar solicitud
        async function aprobarSolicitud(id) {
            if (!confirm('¿Autorizar este usuario para acceder al sistema?')) return;

            try {
                const response = await fetch('index.php?accion=aprobarSolicitud', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_solicitud: id })
                });

                const result = await response.json();
                
                if (result.success) {
                    mostrarMensaje('✅ Usuario autorizado correctamente', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarMensaje(result.message || 'Error al autorizar', 'error');
                }
            } catch (error) {
                mostrarMensaje('Error de conexión', 'error');
            }
        }

        // Modal rechazo
        function abrirModalRechazo(id, usuario) {
            solicitudRechazoId = id;
            document.getElementById('usuario-rechazo').textContent = usuario;
            document.getElementById('motivoRechazo').value = '';
            document.getElementById('modalRechazo').classList.add('show');
        }

        function cerrarModalRechazo() {
            document.getElementById('modalRechazo').classList.remove('show');
            solicitudRechazoId = null;
        }

        async function confirmarRechazo() {
            const motivo = document.getElementById('motivoRechazo').value.trim();

            try {
                const response = await fetch('index.php?accion=rechazarSolicitud', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        id_solicitud: solicitudRechazoId,
                        motivo: motivo || null
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    cerrarModalRechazo();
                    mostrarMensaje('❌ Solicitud rechazada', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarMensaje(result.message || 'Error al rechazar', 'error');
                }
            } catch (error) {
                mostrarMensaje('Error de conexión', 'error');
            }
        }

        // Cerrar modal al presionar Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModalRechazo();
            }
        });

        // Mostrar mensaje
        function mostrarMensaje(texto, tipo) {
            const el = document.getElementById('mensaje-general');
            el.className = 'mensaje ' + tipo;
            el.innerHTML = '<i class="fas fa-' + (tipo === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + texto;
            window.scrollTo(0, 0);
        }
    </script>
</body>
</html>
