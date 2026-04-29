// --- 1. CONFIGURACIÓN DEL MÓDULO ---
const configCurso = {
    entity: 'curso',
    formId: 'formCurso',           // ✅ coincide con el HTML
    btnId: 'btn-submit-form',      // ✅ coincide con el HTML
    btnCancelId: 'btn-cancelar',   // ✅ coincide con el HTML
    labels: { singular: 'Curso' }
};

document.addEventListener('DOMContentLoaded', () => {
    iniciarBuscador('buscadorCursos', 'tablaCursos'); // ✅ busca por tbody, ver nota abajo

    const formCurso = document.getElementById(configCurso.formId);
    if (formCurso) {
        formCurso.addEventListener('submit', function(e) {
            if (!this.checkValidity()) return;

            const idInput = document.querySelector('input[name="id_curso"]');
            const esEdicion = idInput && idInput.value !== "";

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
                        text: 'No has modificado ningún campo del curso.',
                        background: '#4a4a4a'
                    });
                    return;
                }
            }

            e.preventDefault();
            NotificacionOEGPP.fire({
                icon: 'success',
                title: esEdicion ? '¡Cambios Guardados!' : '¡Registro Exitoso!',
                text: esEdicion ? 'Curso actualizado correctamente.' : 'Curso agregado correctamente.'
            }).then(() => {
                this.submit();
            });
        });
    }
});

// --- 2. FUNCIONES DE INTERFAZ ---
function editarCurso(data) {
    modoFormularioUniversal(configCurso, data);
    const panel = document.getElementById('seccionRegistro');
    if (panel?.classList.contains('panel-oculto')) toggleRegistro();
}

function cancelarEdicion() {       // ✅ el HTML llama cancelarEdicion(), no resetearFormularioCurso()
    modoFormularioUniversal(configCurso, false);
}

function eliminarCurso(id) {       // ✅ el HTML llama eliminarCurso(), no confirmarEliminarCurso()
    Swal.fire({
        title: '¿Eliminar curso?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#e24a4a',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href = `index.php?accion=eliminar_curso&id=${id}`;
        }
    });
}

// --- 3. SWITCH DE ESTADO ---
function confirmarEstado(checkbox, idCurso) {
    const estadoNuevo = checkbox.checked ? 1 : 0;

    fetch('index.php?accion=actualizar_estado_curso', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_curso=${idCurso}&estado=${estadoNuevo}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.exito) {
            checkbox.checked = !checkbox.checked;
            NotificacionOEGPP.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar el estado.' });
        }
    })
    .catch(() => {
        checkbox.checked = !checkbox.checked;
        NotificacionOEGPP.fire({ icon: 'error', title: 'Error de conexión', text: 'Intenta de nuevo.' });
    });
}

// --- 4. EXPORTAR ---
function exportarCursos() {
    try {
        if (typeof XLSX === 'undefined') {
            alert("La librería de exportación aún no ha cargado. Revisa tu conexión.");
            return;
        }

        const tabla = document.getElementById('tablaCursos');
        if (!tabla) return;

        const clon = tabla.cloneNode(true);
        clon.querySelectorAll('tr').forEach(fila => {
            if (fila.cells.length > 0) {
                fila.deleteCell(-1); // Acciones
                fila.deleteCell(-1); // Estado
            }
        });

        const hoja = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Cursos");
        XLSX.writeFile(libro, "Reporte_Cursos_OEGPP.xlsx");

    } catch (error) {
        console.error("Error al exportar:", error);
    }
}


// Función para actualizar el estado del curso al mover el switch
function confirmarEstado(checkbox, idCurso) {
    // Si está marcado es 1 (activo), si no es 0 (inactivo)
    const estadoNuevo = checkbox.checked ? 1 : 0;

    // Petición AJAX al controlador
    fetch('index.php?accion=actualizar_estado_curso', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_curso=${idCurso}&estado=${estadoNuevo}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.exito) {
            alert("Hubo un error al actualizar el estado en el servidor.");
            // Revertir visualmente el switch si falló
            checkbox.checked = !checkbox.checked;
        }
        // Si tiene éxito, la próxima vez que entres al Dashboard el número estará actualizado
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error de conexión al intentar actualizar el estado.");
        checkbox.checked = !checkbox.checked;
    });
}