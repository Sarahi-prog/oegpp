// ============================================================
//  notasScript.js  –  OEGPP | Registro de Calificaciones
// ============================================================

/* ── Toggle panel lateral ─────────────────────────────────── */
function toggleRegistro() {
    const panel = document.getElementById('seccionRegistro');
    if (!panel) return;
    panel.classList.toggle('oculto');
}

/* ── Resetear formulario al estado inicial ────────────────── */
function resetearFormulario() {
    document.getElementById('id_nota_form').value       = '';
    document.getElementById('trabajador_id').value      = '';
    document.getElementById('modulo_id').value          = '';
    document.getElementById('nota_input').value         = '';
    document.getElementById('fecha_registro').value     = hoy();

    document.getElementById('dni_search').value = '';
    document.getElementById('cliente_encontrado').style.display = 'none';
    document.getElementById('cliente_error').style.display = 'none';

    const btnSubmit   = document.getElementById('btn-submit-form');
    const btnCancelar = document.getElementById('btn-cancelar');
    if (btnSubmit)   { btnSubmit.querySelector('span').textContent = 'Registrar Nota'; }
    if (btnCancelar) { btnCancelar.style.display = 'none'; }
}

/* ── Cargar datos en el formulario para editar ────────────── */
function editarNota(data) {
    const panel = document.getElementById('seccionRegistro');
    if (panel && panel.classList.contains('oculto')) {
        panel.classList.remove('oculto');
    }

    const cliente = clientesData.find(c => c.id == data.trabajador_id);
    if (cliente) {
        document.getElementById('dni_search').value = cliente.dni;
        document.getElementById('cliente_nombre_display').textContent = cliente.nombre;
        document.getElementById('cliente_encontrado').style.display = 'block';
        document.getElementById('cliente_error').style.display = 'none';
        document.getElementById('trabajador_id').value = cliente.id;
    }

    document.getElementById('id_nota_form').value   = data.id_nota      ?? '';
    document.getElementById('trabajador_id').value  = data.trabajador_id ?? '';
    document.getElementById('modulo_id').value      = data.modulo_id     ?? '';
    document.getElementById('nota_input').value     = data.nota          ?? '';
    document.getElementById('fecha_registro').value = data.fecha_registro
        ? data.fecha_registro.substring(0, 10)
        : hoy();

    const btnSubmit   = document.getElementById('btn-submit-form');
    const btnCancelar = document.getElementById('btn-cancelar');
    if (btnSubmit)   { btnSubmit.querySelector('span').textContent = 'Actualizar Nota'; }
    if (btnCancelar) { btnCancelar.style.display = 'inline-flex'; }

    document.getElementById('seccionRegistro')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ── Confirmar eliminación ────────────────────────────────── */
function confirmarEliminar(id) {
    if (!id) return;
    Swal.fire({
        title: '¿Eliminar nota?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        iconColor: '#ef4444',
        showCancelButton: true,
        confirmButtonColor: '#e24a4a',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `index.php?accion=eliminar_nota&id=${id}`;
        }
    });
}

/* ── Buscador en tiempo real con criterio ────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('buscadorTabla');
    const tabla = document.getElementById('tablaPrincipal');
    const criterioSelect = document.getElementById('criterioBusqueda');

    function aplicarFiltro() {
        const filtro = input.value.toLowerCase().trim();
        const criterio = criterioSelect.value; // cliente, modulo o fecharegistro
        const filas = tabla.querySelectorAll('tbody tr');
        let hayResultados = false;

        filas.forEach(fila => {
            if (fila.classList.contains('fila-sin-resultados')) return;

            let valor = "";
            if (criterio === "cliente") {
                valor = fila.cells[1] ? fila.cells[1].textContent.toLowerCase() : "";
            } else if (criterio === "modulo") {
                valor = fila.cells[2] ? fila.cells[2].textContent.toLowerCase() : "";
            } else if (criterio === "fecharegistro") {
                valor = fila.cells[3] ? fila.cells[3].textContent.toLowerCase() : "";
            }

            if (valor.includes(filtro)) {
                fila.style.display = "";
                hayResultados = true;
            } else {
                fila.style.display = "none";
            }
        });

        let filaVacia = tabla.querySelector('.fila-sin-resultados');
        if (!hayResultados && filtro !== '') {
            if (!filaVacia) {
                filaVacia = document.createElement('tr');
                filaVacia.className = 'fila-sin-resultados';
                const cols = tabla.querySelectorAll('thead th').length;
                filaVacia.innerHTML = `<td colspan="${cols}"><div class="empty-state"><i class="fas fa-search"></i><p>No se encontraron resultados para "${filtro}"</p></div></td>`;
                tabla.querySelector('tbody').appendChild(filaVacia);
            }
            filaVacia.style.display = '';
        } else if (filaVacia) {
            filaVacia.style.display = 'none';
        }
    }

    if (input && tabla && criterioSelect) {
        input.addEventListener('input', aplicarFiltro);
        criterioSelect.addEventListener('change', aplicarFiltro);
    }

    /* ── Manejo del Formulario ── */
    const form = document.getElementById('formNotasAjax');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const idNota = document.getElementById('id_nota_form').value;
            const esEdicion = idNota && idNota !== '';

            if (esEdicion) {
                form.action = 'index.php?accion=modificar_nota';
            } else {
                form.action = 'index.php?accion=guardar_nota';
            }

            NotificacionOEGPP.fire({
                icon: 'success',
                title: esEdicion ? '¡Cambios Guardados!' : '¡Registro Exitoso!',
                text: esEdicion ? 'Nota actualizada.' : 'Nota registrada.'
            }).then(() => {
                form.submit();
            });
        });
    }
});


/* ── Exportar tabla a EXCEL (MEJORADO) ────────────────────── */
function exportarNotas() {
    try {
        if (typeof XLSX === 'undefined') {
            Swal.fire('Error', 'Librería Excel no cargada', 'error');
            return;
        }

        const tabla = document.getElementById('tablaPrincipal');
        if (!tabla) return;

        // Clonar para no romper la vista original
        const clon = tabla.cloneNode(true);
        
        // Quitar filas ocultas por el buscador y la columna de acciones
        const filas = clon.querySelectorAll('tr');
        filas.forEach(fila => {
            if (fila.style.display === 'none' || fila.classList.contains('fila-sin-resultados')) {
                fila.remove();
            } else {
                fila.deleteCell(-1); // Elimina la última columna (Acciones)
            }
        });

        const hoja = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Notas");

        XLSX.writeFile(libro, `Reporte_Notas_${fechaArchivo()}.xlsx`);

    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'No se pudo exportar a Excel', 'error');
    }
}

/* ── Utilidades ───────────────────────────────────────────── */
function hoy() {
    return new Date().toISOString().substring(0, 10);
}

function fechaArchivo() {
    return new Date().toISOString().substring(0, 10).replace(/-/g, '');
}

function buscarPorDni() {
    const dni = document.getElementById('dni_search').value.trim();
    const divEncontrado = document.getElementById('cliente_encontrado');
    const divError = document.getElementById('cliente_error');
    const hidden = document.getElementById('trabajador_id');

    divEncontrado.style.display = 'none';
    divError.style.display = 'none';
    hidden.value = '';

    if (!dni) return;

    const cliente = clientesData.find(c => c.dni === dni);

    if (cliente) {
        hidden.value = cliente.id;
        document.getElementById('cliente_nombre_display').textContent = cliente.nombre;
        divEncontrado.style.display = 'block';
    } else {
        divError.style.display = 'block';
    }
}

/* ── Exportar tabla a EXCEL (SheetJS) ────────────────────── */
function exportarNotas() {
    try {
        // 1. Verificamos si la librería XLSX está cargada en el navegador
        if (typeof XLSX === 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Librería no encontrada',
                text: 'Por favor, asegúrate de incluir la librería SheetJS en tu HTML.'
            });
            return;
        }

        const tabla = document.getElementById('tablaPrincipal');
        if (!tabla) return;

        // 2. Clonar la tabla para manipularla sin afectar lo que ve el usuario
        const clon = tabla.cloneNode(true);
        const filas = clon.querySelectorAll('tr');

        // 3. Limpiar el clon: eliminamos la columna de acciones y filas ocultas
        filas.forEach(fila => {
            // Eliminar la última celda de cada fila (los botones de editar/eliminar)
            if (fila.cells.length > 0) {
                fila.deleteCell(-1); 
            }
            // Si la fila estaba oculta por el buscador, la eliminamos del Excel
            if (fila.style.display === 'none' || fila.classList.contains('fila-sin-resultados')) {
                fila.remove();
            }
        });

        // 4. Crear el libro de Excel usando la librería
        const hoja = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Registro de Notas");

        // 5. Generar descarga con la fecha actual
        const fecha = new Date().toISOString().slice(0, 10);
        XLSX.writeFile(libro, `Registro_Notas_OEGPP_${fecha}.xlsx`);

        // Notificación de éxito
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        Toast.fire({
            icon: 'success',
            title: 'Reporte de notas generado'
        });

    } catch (error) {
        console.error("Error al exportar notas:", error);
        Swal.fire('Error', 'No se pudo generar el Excel', 'error');
    }
}