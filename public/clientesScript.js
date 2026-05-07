// Configuración del módulo
const configCliente = {
    entity: 'cliente',
    formId: 'formCliente',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: { singular: 'Cliente' }
};

document.addEventListener('DOMContentLoaded', () => {
    iniciarBuscadorConCriterio('buscadorTabla', 'cuerpoTabla', 'criterioBusqueda');

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

// 🔎 Buscador con criterio dinámico
function iniciarBuscadorConCriterio(inputId, tableBodyId, selectId) {
    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);
    const tbody = document.getElementById(tableBodyId);

    if (!input || !select || !tbody) return;

    function filtrar() {
        const filtro = input.value.toLowerCase();
        const criterio = select.value; // dni, nombres, area
        const filas = tbody.querySelectorAll('tr');

        filas.forEach(fila => {
            let celda;
            if (criterio === 'dni') {
                celda = fila.querySelector('td:nth-child(2)');
            } else if (criterio === 'nombres') {
                celda = fila.querySelector('td:nth-child(3)');
            } else if (criterio === 'area') {
                celda = fila.querySelector('td:nth-child(7)');
            }

            if (celda && celda.textContent.toLowerCase().includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    // Escucha tanto el input como el cambio de criterio
    input.addEventListener('keyup', filtrar);
    select.addEventListener('change', filtrar);
}

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
