// --- 1. CONFIGURACIÓN DEL MÓDULO ---
const configModulo = {
    entity: 'modulo',              // Entidad para las rutas index.php?accion=...
    formId: 'formModulo',          // ID del <form> en tu HTML
    btnId: 'btn-submit-form',      // ID del botón principal
    btnCancelId: 'btn-cancelar',   // ID del botón X o Cancelar
    labels: { singular: 'Módulo' }
};

document.addEventListener('DOMContentLoaded', () => {
    // Inicializar el buscador universal
    iniciarBuscador('buscadorModulos', 'tablaModulos');

    const formModulo = document.getElementById(configModulo.formId);
    if (formModulo) {
        formModulo.addEventListener('submit', function(e) {
            if (!this.checkValidity()) return;

            // Detectar si es edición buscando el input hidden del ID
            const idInput = document.querySelector('input[name="id_modulo"]');
            const esEdicion = idInput && idInput.value !== "";

            // Lógica de validación de cambios (Evita envíos innecesarios)
            if (esEdicion && datosOriginales) {
                let huboCambios = false;
                const formData = new FormData(this);

                for (let key in datosOriginales) {
                    if (formData.has(key) && String(formData.get(key)) !== String(datosOriginales[key])) {
                        huboCambios = true;
                        break;
                    }
                }

                if (!huboCambios) {
                    e.preventDefault();
                    NotificacionOEGPP.fire({
                        icon: 'info',
                        title: 'Sin cambios',
                        text: 'No has modificado ningún campo del módulo.',
                        background: '#4a4a4a'
                    });
                    return;
                }
            }

            // Notificación estética antes de enviar
            e.preventDefault();
            NotificacionOEGPP.fire({
                icon: 'success',
                title: esEdicion ? '¡Cambios Guardados!' : '¡Registro Exitoso!',
                text: esEdicion ? 'Módulo actualizado correctamente.' : 'Módulo agregado correctamente.'
            }).then(() => {
                this.submit();
            });
        });
    }
});

// --- 2. FUNCIONES DE INTERFAZ ---

/**
 * Activa el modo edición en el formulario universal
 * @param {Object} data - Datos del módulo obtenidos del JSON de la tabla
 */
function editarModulo(data) {
    modoFormularioUniversal(configModulo, data);
    const panel = document.getElementById('seccionRegistro');
    if (panel?.classList.contains('panel-oculto')) toggleRegistro();
}

/**
 * Resetea el formulario al estado "Guardar Nuevo"
 */
function cancelarEdicion() {
    modoFormularioUniversal(configModulo, false);
}

/**
 * Confirmación estética para eliminar
 */
function eliminarModulo(id) {
    Swal.fire({
        title: '¿Eliminar módulo?',
        text: "Se borrarán los datos asociados a este módulo.",
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#e24a4a',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href = `index.php?accion=eliminar_modulo&id=${id}`;
        }
    });
}

// --- 3. SWITCH DE ESTADO (AJAX) ---
function confirmarEstadoModulo(checkbox, idModulo) {
    const estadoNuevo = checkbox.checked ? 1 : 0;

    fetch('index.php?accion=actualizar_estado_modulo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_modulo=${idModulo}&estado=${estadoNuevo}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.exito) {
            checkbox.checked = !checkbox.checked; // Revertir si falla
            NotificacionOEGPP.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar el estado.' });
        }
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
        NotificacionOEGPP.fire({ icon: 'error', title: 'Error de conexión', text: 'Intenta de nuevo.' });
    });
}

// --- 4. EXPORTAR A EXCEL ---
function exportarModulos() {
    try {
        if (typeof XLSX === 'undefined') {
            alert("Librería XLSX no detectada.");
            return;
        }

        const tabla = document.getElementById('tablaModulos');
        if (!tabla) return;

        // Clonar para limpiar columnas de acciones
        const clon = tabla.cloneNode(true);
        clon.querySelectorAll('tr').forEach(fila => {
            if (fila.cells.length > 0) {
                fila.deleteCell(-1); // Borrar columna Acciones
                fila.deleteCell(-1); // Borrar columna Estado (opcional)
            }
        });

        const hoja = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Módulos");
        XLSX.writeFile(libro, "Reporte_Modulos_OEGPP.xlsx");

    } catch (error) {
        console.error("Error al exportar:", error);
    }
}