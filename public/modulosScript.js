// --- 1. CONFIGURACIÓN DEL MÓDULO ---
const configModulo = {
    entity: 'modulo',
    formId: 'formModulo',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: { singular: 'Módulo' }
};

document.addEventListener('DOMContentLoaded', () => {
    iniciarBuscador('buscadorModulos', 'tablaModulos');
    iniciarBuscadorCurso();         // ← Buscador de cursos en el formulario

    const formModulo = document.getElementById(configModulo.formId);
    if (formModulo) {
        formModulo.addEventListener('submit', function(e) {
            if (!this.checkValidity()) return;

            const idInput = document.querySelector('input[name="id_modulo"]');
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
                        text: 'No has modificado ningún campo del módulo.',
                        background: '#4a4a4a'
                    });
                    return;
                }
            }

            e.preventDefault();
            NotificacionOEGPP.fire({
                icon: 'success',
                title: esEdicion ? '¡Cambios Guardados!' : '¡Registro Exitoso!',
                text: esEdicion ? 'Módulo actualizado correctamente.' : 'Módulo agregado correctamente.'
            }).then(() => this.submit());
        });
    }
});


// --- 2. BUSCADOR DE CURSOS EN EL FORMULARIO ---

/**
 * Convierte el <select name="curso_id"> en un input buscador con lista desplegable.
 * El select original queda oculto y sigue enviándose con el form normalmente.
 */
function iniciarBuscadorCurso() {
    const select = document.getElementById('curso_id_form');
    if (!select) return;

    // --- Wrapper contenedor ---
const wrapper = document.createElement('div');
wrapper.style.cssText = 'position:relative; width:100%; display:flex; align-items:center; gap:8px;';

// --- Input visible del buscador ---
const input = document.createElement('input');
input.type         = 'text';
input.id           = 'buscadorCursoInput';
input.placeholder  = 'Buscar curso...';
input.autocomplete = 'off';
input.style.cssText = `
    flex: 1;
    box-sizing: border-box;
    padding: 11px 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.92rem;
    font-family: inherit;
    color: #1f2937;
    background: #fff;
    outline: none;
    cursor: text;
    transition: border-color 0.2s;
`;

// --- Botón lupa externo ---
const btnLupa = document.createElement('button');
btnLupa.type = 'button';
btnLupa.innerHTML = '<i class="fas fa-search"></i>';
btnLupa.style.cssText = `
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 10px;
    background: #22c55e;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
`;
btnLupa.addEventListener('mouseenter', () => btnLupa.style.background = '#16a34a');
btnLupa.addEventListener('mouseleave', () => btnLupa.style.background = '#22c55e');
btnLupa.addEventListener('click', () => { input.focus(); renderLista(input.value); });

// --- Lista desplegable (posición relativa al wrapper completo) ---
const lista = document.createElement('ul');
lista.id = 'listaCursosSugerencias';
lista.style.cssText = `
    position:absolute; top:calc(100% + 4px); left:0;
    right: 48px;
    background:#fff; border:1.5px solid #e2e8f0; border-radius:10px;
    max-height:220px; overflow-y:auto; z-index:9999;
    margin:0; padding:4px 0; list-style:none;
    box-shadow:0 8px 24px rgba(0,0,0,0.10); display:none;
`;

// Insertar en el DOM y ocultar el select original
select.parentNode.insertBefore(wrapper, select);
wrapper.appendChild(input);
wrapper.appendChild(btnLupa);
wrapper.appendChild(lista);
select.style.display = 'none';
wrapper.appendChild(select);

    // --- Opciones válidas (sin la opción vacía inicial) ---
    const opciones = Array.from(select.options).filter(o => o.value !== '');

    // --- Renderizar lista filtrada ---
    function renderLista(filtro) {
        lista.innerHTML = '';
        const texto = filtro.toLowerCase().trim();
        const resultados = texto
            ? opciones.filter(o => o.text.toLowerCase().includes(texto))
            : opciones;

        if (resultados.length === 0) {
            const li = document.createElement('li');
            li.textContent = 'Sin resultados';
            li.style.cssText = 'padding:10px 14px; color:#9ca3af; font-size:0.875rem; font-style:italic;';
            lista.appendChild(li);
        } else {
            resultados.forEach(op => {
                const li       = document.createElement('li');
                li.dataset.value = op.value;

                // Resaltar coincidencia en el texto
                if (texto) {
                    const idx = op.text.toLowerCase().indexOf(texto);
                    li.innerHTML =
                        op.text.substring(0, idx) +
                        `<strong style="color:#16a34a;">${op.text.substring(idx, idx + texto.length)}</strong>` +
                        op.text.substring(idx + texto.length);
                } else {
                    li.textContent = op.text;
                }

                li.style.cssText = `
                    padding:10px 16px; cursor:pointer; font-size:0.875rem;
                    color:#1f2937; border-radius:7px; margin:2px 4px;
                    transition:background 0.15s;
                `;
                li.addEventListener('mouseenter', () => { li.style.background = '#f0fdf4'; li.style.color = '#16a34a'; });
                li.addEventListener('mouseleave', () => { li.style.background = 'transparent'; li.style.color = '#1f2937'; });
                li.addEventListener('mousedown', e => {
                    e.preventDefault();             // evita que blur se dispare antes
                    seleccionarCurso(op.value, op.text);
                });
                lista.appendChild(li);
            });
        }
        lista.style.display = 'block';
    }

    // --- Seleccionar curso ---
    function seleccionarCurso(valor, texto) {
        select.value          = valor;
        input.value           = texto;
        input.style.borderColor = '#22c55e';
        lista.style.display   = 'none';
    }

    // --- Limpiar selección ---
    function limpiarSeleccion() {
        select.value          = '';
        input.style.borderColor = '';
    }

    // --- Eventos ---
    input.addEventListener('input', () => {
        limpiarSeleccion();
        renderLista(input.value);
    });

    input.addEventListener('focus', () => { input.style.borderColor = '#22c55e'; renderLista(input.value); });

    input.addEventListener('blur', () => {
        setTimeout(() => {
            lista.style.display = 'none';
            // Si el usuario no terminó de seleccionar, limpiar el texto suelto
            if (!select.value) {
                input.value           = '';
                input.style.borderColor = '';
            }
        }, 160);
    });
}


// --- 3. FUNCIONES DE INTERFAZ ---

/**
 * Activa el modo edición y restaura el buscador con el curso guardado.
 */
function editarModulo(data) {
    modoFormularioUniversal(configModulo, data);

    // Restaurar visualmente el buscador con el curso del registro
    const select = document.getElementById('curso_id_form');
    const input  = document.getElementById('buscadorCursoInput');
    if (select && input && data.curso_id) {
        const op = Array.from(select.options).find(o => String(o.value) === String(data.curso_id));
        if (op) {
            select.value          = data.curso_id;
            input.value           = op.text;
            input.style.borderColor = '#22c55e';
        }
    }

    const panel = document.getElementById('seccionRegistro');
    if (panel?.classList.contains('panel-oculto')) toggleRegistro();
}

/**
 * Resetea el formulario y limpia el buscador visual.
 */
function cancelarEdicion() {
    modoFormularioUniversal(configModulo, false);

    const input = document.getElementById('buscadorCursoInput');
    if (input) {
        input.value           = '';
        input.style.borderColor = '';
    }
    // Resetear también el select oculto
    const select = document.getElementById('curso_id_form');
    if (select) select.value = '';
}

/**
 * Confirmación para eliminar módulo.
 */
function eliminarModulo(id) {
    Swal.fire({
        title: '¿Eliminar módulo?',
        text: 'Se borrarán los datos asociados a este módulo.',
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#e24a4a',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed)
            window.location.href = `index.php?accion=eliminar_modulo&id=${id}`;
    });
}


// --- 4. SWITCH DE ESTADO (AJAX) ---
function confirmarEstadoModulo(checkbox, idModulo) {
    const estadoNuevo = checkbox.checked ? 1 : 0;

    fetch('index.php?accion=actualizar_estado_modulo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_modulo=${idModulo}&estado=${estadoNuevo}`
    })
    .then(r => r.json())
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


// --- 5. EXPORTAR A EXCEL ---
function exportarModulos() {
    try {
        if (typeof XLSX === 'undefined') { alert('Librería XLSX no detectada.'); return; }

        const tabla = document.getElementById('tablaModulos');
        if (!tabla) return;

        const clon = tabla.cloneNode(true);
        clon.querySelectorAll('tr').forEach(fila => {
            if (fila.cells.length > 0) {
                fila.deleteCell(-1);
                fila.deleteCell(-1);
            }
        });

        const hoja  = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, 'Módulos');
        XLSX.writeFile(libro, 'Reporte_Modulos_OEGPP.xlsx');
    } catch (err) {
        console.error('Error al exportar:', err);
    }
}