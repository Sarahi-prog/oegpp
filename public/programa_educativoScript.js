// --- 1. CONFIGURACIÓN DEL MÓDULO ---
const configCurso = {
    entity: 'programa',
    formId: 'formCurso',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: {
        singular: 'Programa Educativo'
    }
};

document.addEventListener('DOMContentLoaded', () => {

    iniciarBuscador(
        'buscadorCursos',
        'tablaCursos'
    );

    const formCurso =
        document.getElementById(
            configCurso.formId
        );

    if (formCurso) {

        formCurso.addEventListener(
            'submit',
            function (e) {

                if (!this.checkValidity()) return;

                // ✅ CORREGIDO
                const idInput =
                    document.querySelector(
                        'input[name="id_programa"]'
                    );

                const esEdicion =
                    idInput &&
                    idInput.value !== "";

                // VALIDAR SI HUBO CAMBIOS
                if (
                    esEdicion &&
                    datosOriginales
                ) {

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
                                'No has modificado ningún campo.',
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
                        ? 'Programa actualizado correctamente.'
                        : 'Programa agregado correctamente.'
                }).then(() => {

                    this.submit();

                });
            }
        );
    }
});

// --- 2. FUNCIONES DE INTERFAZ ---
function editarCurso(data) {

    modoFormularioUniversal(
        configCurso,
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

function cancelarEdicion() {

    modoFormularioUniversal(
        configCurso,
        false
    );
}

// --- 3. ELIMINAR ---
function eliminarCurso(id) {

    Swal.fire({
        title:
            '¿Eliminar programa educativo?',
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
                `index.php?accion=eliminar_programa&id=${id}`;
        }
    });
}

// --- 4. CAMBIAR ESTADO ---
function confirmarEstado(
    checkbox,
    idCurso
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
                `id=${idCurso}&estado=${estadoNuevo}`
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
function exportarCursos() {

    try {

        if (typeof XLSX === 'undefined') {

            alert(
                'La librería de exportación aún no ha cargado.'
            );

            return;
        }

        const tabla =
            document.getElementById(
                'tablaCursos'
            );

        if (!tabla) return;

        const clon =
            tabla.cloneNode(true);

        clon.querySelectorAll('tr')
            .forEach(fila => {

                if (
                    fila.cells.length > 0
                ) {

                    fila.deleteCell(-1); // acciones
                    fila.deleteCell(-1); // estado
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