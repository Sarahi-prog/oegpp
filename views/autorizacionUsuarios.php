<?php

// autorizacionUsuario.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SEGURIDAD: Si el usuario no tiene sesión o su rol es 'asistente', lo expulsamos
if (!isset($_SESSION['rol']) || $_SESSION['rol'] === 'asistente') {
    // Lo redirigimos al inicio con un mensaje de error
    header("Location: index.php?accion=inicio&error=no_autorizado");
    exit();
}

// Si llega aquí, es porque es Administrador. Aquí cargamos el modelo y los datos.

$pagina_actual = 'autorizacion';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Autorización - OEGPP</title>
    <link rel="stylesheet" href="public/dashStyles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'views/includes/menu.php'; ?>

    <div class="container main-content">
        <div class="section-header">
            <div class="section-title">
                <h2><i class="fas fa-user-check" style="color: #10b981;"></i> Gestión de Autorizaciones</h2>
                <p>Aprueba o rechaza las solicitudes de nuevos usuarios al sistema.</p>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>USUARIO</th>
                        <th>CORREO</th>
                        <th>ROL SOLICITADO</th> <th>FECHA SOLICITUD</th>
                        <th>ESTADO</th>
                        <th class="text-right">ACCIONES</th>
                    </tr>
                </thead>
                <tbody id="listaSolicitudes">
                    <!-- Se carga vía AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', cargarHistorial);

    async function cargarHistorial() {
        try {
            const res = await fetch('index.php?accion=obtenerHistorialSolicitudes');
            const data = await res.json();
            const tbody = document.getElementById('listaSolicitudes');
            tbody.innerHTML = '';

            if (!data.success || data.data.length === 0) {
                // Actualizado a colspan="6" porque añadimos una columna
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:30px;">No hay solicitudes registradas</td></tr>';
                return;
            }

            // --- LÓGICA PARA EL ROL ACTUALIZADA ---
data.data.forEach(s => {
    // 1. Colores y texto del estado
    const colorEstado = s.estado === 'aprobado'  ? 'green'
                      : s.estado === 'rechazado' ? 'red'
                      : 'orange';

    const etiquetaEstado = s.estado === 'aprobado'  ? 'Autorizado'
                         : s.estado === 'rechazado' ? 'Rechazado'
                         : 'Pendiente';

    // 2. Validación de seguridad para el ROL (Evita el UNDEFINED)
    // Si s.rol no existe o es nulo, le asignamos 'asistente' por defecto
    const valorRol = (s.rol && s.rol.trim() !== "") ? s.rol : 'asistente';
    
    // Color según el valor final del rol
    const badgeRolColor = valorRol.toLowerCase() === 'administrador' ? '#2563eb' : '#64748b'; 

    const etiquetaRol = `
        <span style="background-color: ${badgeRolColor}; color: white; padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; display: inline-block; min-width: 90px; text-align: center;">
            ${valorRol}
        </span>
    `;

    // 3. Lógica de botones (se mantiene igual)
    let acciones = '';
    if (s.estado === 'pendiente') {
        acciones = `
            <button class="btn-icon btn-edit" onclick="procesar('${s.id_solicitud}', 'aprobar')" title="Aprobar">
                <i class="fas fa-check"></i>
            </button>
            <button class="btn-icon btn-outline" onclick="procesar('${s.id_solicitud}', 'rechazar')" title="Rechazar" style="color:#f59e0b">
                <i class="fas fa-times"></i>
            </button>
        `;
    } else {
        acciones = `
            <button class="btn-icon btn-delete" onclick="procesar('${s.id_solicitud}', 'eliminar')" title="Eliminar del historial" style="color:#ef4444">
                <i class="fas fa-trash-alt"></i>
            </button>
        `;
    }

    // 4. Renderizado de la fila
    tbody.innerHTML += `
        <tr>
            <td><strong>${s.usuario}</strong></td>
            <td>${s.correo}</td>
            <td style="text-align: center;">${etiquetaRol}</td> 
            <td>${new Date(s.fecha_solicitud).toLocaleString()}</td>
            <td><span class="stat-trend ${colorEstado}">${etiquetaEstado}</span></td>
            <td class="text-right">${acciones}</td>
        </tr>
    `;
});
        } catch (e) {
            console.error('Error al cargar historial:', e);
        }
    }

    async function procesar(id, tipo) {
        let accion = '';
        let body = { id_solicitud: id };

        if (tipo === 'aprobar') {
            if (!confirm('¿Autorizar acceso a este usuario?')) return;
            accion = 'aprobarSolicitud';
        } else if (tipo === 'rechazar') {
            const motivo = prompt('Motivo del rechazo:');
            if (motivo === null) return;
            accion = 'rechazarSolicitud';
            body.motivo = motivo;
        } else {
            if (!confirm('¿Eliminar esta solicitud permanentemente?')) return;
            accion = 'eliminarSolicitud';
        }

        try {
            const res = await fetch(`index.php?accion=${accion}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const result = await res.json();
            alert(result.message);
            cargarHistorial();
        } catch (e) {
            alert('Error al procesar la solicitud');
        }
    }
</script>
</body>
</html>