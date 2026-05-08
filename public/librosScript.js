// ============================================================
// SCRIPT PARA GESTIÓN DE REGISTROS (registrosScript.js)
// ============================================================

// Configuración del módulo
const configRegistro = {
    entity: 'registro',
    formId: 'formRegistro',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: { singular: 'Registro' }
};

let datosOriginalesRegistro = null; // Para trackear si hubo cambios en la edición

document.addEventListener('DOMContentLoaded', () => {
    // Iniciar buscador global de la tabla
    iniciarBuscadorRegistros('buscadorTabla', 'tablaPrincipal');

    const formRegistro = document.getElementById(configRegistro.formId);
    if (formRegistro) {
        formRegistro.addEventListener('submit', function(e) {
            if (!this.checkValidity()) return;

            const idInput = document.getElementById('id_registro_form');
            const esEdicion = idInput && idInput.value !== "";

            // Validación: Verificar si hubo cambios reales en la edición
            if (esEdicion && datosOriginalesRegistro) {
                let huboCambios = false;
                const formData = new FormData(this);
                
                for (let key in datosOriginalesRegistro) {
                    // Evitar comparar campos que no están en el form o que son nulos
                    if (formData.has(key)) {
                        let valorOriginal = datosOriginalesRegistro[key] === null ? "" : String(datosOriginalesRegistro[key]);
                        let valorNuevo = String(formData.get(key));
                        
                        if (valorNuevo !== valorOriginal) {
                            huboCambios = true;
                            break;
                        }
                    }
                }

                if (!huboCambios) {
                    e.preventDefault();
                    // Usando SweetAlert2 directamente (o tu NotificacionOEGPP si la tienes global)
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin cambios',
                        text: 'No has modificado ningún campo.',
                        background: '#f8fafc',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }
            }

            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: esEdicion ? '¡Cambios Guardados!' : '¡Registro Exitoso!',
                text: esEdicion ? 'El registro fue actualizado correctamente.' : 'El registro fue agregado correctamente.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                this.submit();
            });
        });
    }
});

// 🔎 Buscador General (Por código, nombre o curso)
function iniciarBuscadorRegistros(inputId, tableId) {
    const input = document.getElementById(inputId);
    const tabla = document.getElementById(tableId);
    const tbody = tabla ? tabla.querySelector('tbody') : null;

    if (!input || !tbody) return;

    input.addEventListener('keyup', function() {
        const filtro = this.value.toLowerCase();
        const filas = tbody.querySelectorAll('tr.fila-cliente'); // Busca solo en las filas de datos

        filas.forEach(fila => {
            // Como tu tabla muestra la info combinada, buscaremos en el texto completo de la fila
            const textoFila = fila.textContent.toLowerCase();
            
            if (textoFila.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });
}

// 🔹 FUNCIÓN UNIVERSAL PARA LLENAR FORMULARIO
function modoFormularioUniversal(config, data) {
    const form = document.getElementById(config.formId);
    if (!form) return;

    if (data) {
        for (let key in data) {
            const campo = form.querySelector(`[name="${key}"]`);
            if (campo) {
                if (campo.tagName === "SELECT") {
                    campo.value = data[key] ?? "";
                } else if (campo.type === "date") {
                    if (data[key]) {
                        campo.value = data[key].split(" ")[0];
                    } else {
                        campo.value = "";
                    }
                } else {
                    campo.value = data[key] ?? "";
                }
            }
        }
        // Asignar el ID manualmente por si el name difiere
        const idCampo = form.querySelector('#id_registro_form');
        if (idCampo && data.id_registro) {
            idCampo.value = data.id_registro;
        }

    } else {
        form.reset();
        const idCampo = form.querySelector('#id_registro_form');
        if (idCampo) idCampo.value = ""; // Limpiar el hidden
    }
}

// 🔹 EDITAR
function editarRegistro(data) { 
    datosOriginalesRegistro = data; // Guardamos el estado original
    modoFormularioUniversal(configRegistro, data);

    // Cambiar UI a modo edición
    document.getElementById('form-title').innerHTML = '<i class="fas fa-edit"></i> Editar Registro';
    document.getElementById('btn-submit-form').querySelector('span').textContent = 'Actualizar Registro';
    document.getElementById('btn-cancelar').style.display = 'inline-block';

    // Abrir el panel si está oculto (Esta función la tienes en tu UniversalScript o debe existir)
    const panel = document.getElementById('seccionRegistro');
    if (panel && panel.classList.contains('panel-oculto')) {
        toggleRegistro(); 
    }
}

// 🔹 RESET FORM
function resetearFormulario() { 
    datosOriginalesRegistro = null;
    modoFormularioUniversal(configRegistro, false); 

    // Restaurar UI a modo nuevo registro
    document.getElementById('form-title').innerHTML = '<i class="fas fa-plus-circle"></i> Datos del Registro';
    document.getElementById('btn-submit-form').querySelector('span').textContent = 'Guardar Registro';
    document.getElementById('btn-cancelar').style.display = 'none';
}

// 🔹 ELIMINAR
function confirmarEliminarRegistro(id) {
    Swal.fire({
        title: '¿Eliminar registro?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
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

// 🔹 EXPORTAR
function exportarRegistros() {
    try {
        if (typeof XLSX === 'undefined') {
            alert("La librería de exportación aún no ha cargado.");
            return;
        }

        const tabla = document.getElementById('tablaPrincipal'); // Usamos el ID de tu tabla HTML
        if (!tabla) {
            console.error("No se encontró la tabla");
            return;
        }

        // Clonar para no alterar la vista original
        const clon = tabla.cloneNode(true);
        const filas = clon.querySelectorAll('tr');

        // Eliminar la última columna (Acciones)
        filas.forEach(fila => {
            if (fila.cells.length > 0) {
                fila.deleteCell(-1); 
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

// 🔹 TOGGLE PANEL (En caso de que no la tengas en UniversalScript.js)
function toggleRegistro() {
    const panel = document.getElementById('seccionRegistro');
    if (panel) {
        panel.classList.toggle('panel-oculto');
    }
}