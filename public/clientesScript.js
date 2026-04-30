// Configuración del módulo
const configCliente = {
    entity: 'cliente',
    formId: 'formCliente',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: { singular: 'Cliente' }
};

document.addEventListener('DOMContentLoaded', () => {
    iniciarBuscador('buscadorTabla', 'cuerpoTabla');

    const formCliente = document.getElementById('formCliente');
    if (formCliente) {
        formCliente.addEventListener('submit', function(e) {
            if (!this.checkValidity()) return;

            const idInput = document.querySelector('input[name="id_cliente"]');
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
                text: esEdicion ? 'Información actualizada.' : 'Cliente agregado.'
            }).then(() => {
                this.submit();
            });
        });
    }
});

function editarCliente(data) { 
    modoFormularioUniversal(configCliente, data);
    const panel = document.getElementById('seccionRegistro');
    if (panel?.classList.contains('panel-oculto')) toggleRegistro();
}

function resetearFormulario() { 
    modoFormularioUniversal(configCliente, false); 
}

function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Eliminar cliente?',
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
            window.location.href = `index.php?accion=eliminar_cliente&id=${id}`;
        }
    });
}

function exportarCursos() {
    try {
        if (typeof XLSX === 'undefined') {
            alert("La librería de exportación aún no ha cargado. Revisa tu conexión.");
            return;
        }

        const tabla = document.getElementById('tablaPrincipal');
        if (!tabla) {
            console.error("No se encontró la tabla con ID 'tablaPrincipal'");
            return;
        }

        const clon = tabla.cloneNode(true);
        const filas = clon.querySelectorAll('tr');

        filas.forEach(fila => {
            if (fila.cells.length > 0) {
                fila.deleteCell(-1); 
            }
        });

        const hoja = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Clientes");

        XLSX.writeFile(libro, "Reporte_Clientes_OEGPP.xlsx");
        
    } catch (error) {
        console.error("Error al exportar:", error);
    }
}