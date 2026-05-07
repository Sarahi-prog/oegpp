// Configuración del módulo
const configRegistro = {
    entity: 'registro',
    formId: 'formRegistro',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: { singular: 'Registro' }
};

document.addEventListener('DOMContentLoaded', () => {
    iniciarBuscador('buscadorTabla', 'cuerpoTabla');

    const formRegistro = document.getElementById('formRegistro');

    if (formRegistro) {
        formRegistro.addEventListener('submit', function(e) {
            if (!this.checkValidity()) return;

            const idInput = document.querySelector('input[name="id_registro"]');
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
                        text: 'No has modificado ningún campo.',
                        background: '#4a4a4a'
                    });
                    return;
                }
            }

            e.preventDefault();
            NotificacionOEGPP.fire({
                icon: 'success',
                title: esEdicion ? '¡Cambios Guardados!' : '¡Registro Exitoso!',
                text: esEdicion ? 'Registro actualizado correctamente.' : 'Registro agregado correctamente.'
            }).then(() => {
                this.submit();
            });
        });
    }
});

function editarRegistro(data) { 
    modoFormularioUniversal(configRegistro, data);

    const panel = document.getElementById('seccionRegistro');
    if (panel?.classList.contains('panel-oculto')) {
        toggleRegistro();
    }
}

function resetearFormularioRegistro() { 
    modoFormularioUniversal(configRegistro, false); 
}

function confirmarEliminarRegistro(id) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#e24a4a',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        borderRadius: '16px',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `index.php?accion=eliminar_registro&id=${id}`;
        }
    });
}

function exportarRegistros() {
    try {
        if (typeof XLSX === 'undefined') {
            alert("La librería de exportación aún no ha cargado.");
            return;
        }

        const tabla = document.getElementById('tablaPrincipal');
        if (!tabla) {
            console.error("No se encontró la tabla");
            return;
        }

        const clon = tabla.cloneNode(true);
        const filas = clon.querySelectorAll('tr');

        filas.forEach(fila => {
            if (fila.cells.length > 0) {
                fila.deleteCell(-1); // eliminar columna acciones
            }
        });

        const hoja = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Registros");

        XLSX.writeFile(libro, "Reporte_Registros_OEGPP.xlsx");

    } catch (error) {
        console.error("Error al exportar:", error);
    }
}