// --- 1. CONFIGURACIÓN DEL MÓDULO ---
const configProgramaEducativo = {
    entity: 'programa_educativo',
    formId: 'formProgramaEducativo',
    btnId: 'btn-submit-programa-educativo',
    btnCancelId: 'btn-cancelar-programa-educativo',
    labels: {
        singular: 'Programa Educativo'
    }
};

document.addEventListener('DOMContentLoaded', () => {
    iniciarBuscador(
        'buscadorProgramaEducativo',
        'tablaProgramaEducativo'
    );
    const formProgramaEducativo =
        document.getElementById(
            configProgramaEducativo.formId
        );
    if (formProgramaEducativo) {
        formProgramaEducativo.addEventListener(
            'submit',
            function (e) {
                if (!this.checkValidity()) return;
                const idInput = document.querySelector(
                    'input[name="id_programa_educativo"]'
                );
                const esEdicion =
                    idInput &&
                    idInput.value !== "";
                // VALIDAR SI HUBO CAMBIOS
                if (esEdicion && datosOriginales) {
                    let huboCambios = false;
                    const formData =
                        new FormData(this);
                    for (let key in datosOriginales) {
                        if (
                            formData.has(key) &&
                            String(formData.get(key)) !==
                            String(datosOriginales[key])
                        ) {
                            huboCambios = true;
                            break;

                        }
                    }
                    if (!huboCambios) {
                        e.preventDefault();
                        NotificacionOEGPP.fire({
                            icon: 'info',
                            title: 'Sin cambios',
                            text:
                                'No has modificado ningún campo del Programa Educativo.',
                            background: '#4a4a4a'
                        });
                        return;
                    }
                }
                e.preventDefault();
                NotificacionOEGPP.fire({
                    icon: 'success',
                    title: esEdicion
                        ? '¡Cambios Guardados!'
                        : '¡Registro Exitoso!',
                    text: esEdicion
                        ? 'Programa Educativo actualizado correctamente.'
                        : 'Programa Educativo agregado correctamente.'
                }).then(() => {
                    this.submit();
                });
            }
        );
    }
});
// --- 2. FUNCIONES DE INTERFAZ ---
function editarProgramaEducativo(data) {
    modoFormularioUniversal(
        configProgramaEducativo,
        data
    );
    const panel =
        document.getElementById(
            'seccionRegistro'
        );
    if (
        panel?.classList.contains(
            'panel-oculto'
        )
    ) {
        toggleRegistro();
    }
}
function cancelarEdicionProgramaEducativo() {
    modoFormularioUniversal(
        configProgramaEducativo,
        false
    );
}
// --- 3. ELIMINAR REGISTRO ---
function eliminarProgramaEducativo(
    idProgramaEducativo
) {
    Swal.fire({
        title:
            '¿Eliminar Programa Educativo?',
        text:
            'Esta acción no se puede deshacer.',
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#e24a4a',
        cancelButtonColor: '#64748b',
        confirmButtonText:
            'Sí, eliminar',
        cancelButtonText:
            'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href =
                `index.php?accion=eliminar_programa&id=${idProgramaEducativo}`;
        }
    });
}
// --- 4. CAMBIAR ESTADO ---
function confirmarEstadoProgramaEducativo(
    checkbox,
    idProgramaEducativo
) {
    const estadoNuevo =
        checkbox.checked ? 1 : 0;
    fetch(
        'index.php?accion=actualizar_estado_programa',
        {
            method: 'POST',
            headers: {
                'Content-Type':
                    'application/x-www-form-urlencoded',
            },
            body:
                `id=${idProgramaEducativo}&estado=${estadoNuevo}`
        }
    )
    .then(response => response.json())
    .then(data => {
        if (!data.exito) {
            checkbox.checked =
                !checkbox.checked;
            NotificacionOEGPP.fire({
                icon: 'error',
                title: 'Error',
                text:
                    'No se pudo actualizar el estado.'
            });
        }
    })
    .catch(error => {
        console.error(
            'Error:',
            error
        );
        checkbox.checked =
            !checkbox.checked;
        NotificacionOEGPP.fire({
            icon: 'error',
            title:
                'Error de conexión',
            text:
                'Intenta nuevamente.'
        });
    });
}
// --- 5. EXPORTAR EXCEL ---
function exportarProgramasEducativos() {
    try {
        if (typeof XLSX === 'undefined') {
            alert(
                'La librería de exportación aún no ha cargado.'
            );
            return;
        }
        const tabla =
            document.getElementById(
                'tablaProgramaEducativo'
            );
        if (!tabla) return;
        const clon =
            tabla.cloneNode(true);
        clon.querySelectorAll('tr')
            .forEach(fila => {
                if (
                    fila.cells.length > 0
                ) {
                    fila.deleteCell(-1);
                    fila.deleteCell(-1);
                }
            });
        const hoja =
            XLSX.utils.table_to_sheet(
                clon
            );
        const libro =
            XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(
            libro,
            hoja,
            'Programas Educativos'
        );
        XLSX.writeFile(
            libro,
            'Reporte_Programas_Educativos.xlsx'
        );
    } catch (error) {
        console.error(
            'Error al exportar:',
            error
        );
    }
}