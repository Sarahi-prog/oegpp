/* ================================================================
   LOGICA JAVASCRIPT - LIBROS DE REGISTRO
   ================================================================ */

let datosOriginales = null; 
let vistaActual = 'lista';

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Iniciar Vistas
    const vistaGuardada = localStorage.getItem('preferenciaVistaLibros') || 'lista';
    aplicarVista(vistaGuardada);

    // 2. Buscador en tiempo real
    const buscador = document.getElementById('buscadorLibros');
    if (buscador) {
        buscador.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            
            // Filtrar en la tabla (Vista Lista)
            document.querySelectorAll('#cuerpoTablaLibros tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
            
            // Filtrar en las tarjetas (Vista Grid)
            document.querySelectorAll('.book-folder-card').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    }

    // 3. LÓGICA DE ALERTAS Y ENVÍO DEL FORMULARIO
    const formLibro = document.getElementById('formLibro');
    if (formLibro) {
        formLibro.addEventListener('submit', function(e) {
            e.preventDefault(); // Detenemos el envío automático SÍ O SÍ

            // Verificar si el HTML exige algún campo obligatorio que falte
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            const idInput = document.getElementById('id_libro_form');
            const esEdicion = idInput && idInput.value !== "";

            // --- VALIDACIÓN DE "SIN CAMBIOS" ---
            if (esEdicion && datosOriginales) {
                let huboCambios = false;
                const formData = new FormData(this);
                
                for (let key in datosOriginales) {
                    let valorNuevo = formData.has(key) ? String(formData.get(key)).trim() : "";
                    let valorViejo = datosOriginales[key] !== null ? String(datosOriginales[key]).trim() : "";

                    if (valorNuevo !== valorViejo) {
                        huboCambios = true;
                        break;
                    }
                }

                if (!huboCambios) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin cambios',
                        text: 'No has modificado ningún campo.',
                        confirmButtonColor: '#64748b' 
                    });
                    return; // Cortamos la ejecución aquí si no hay cambios
                }
            }

            // --- ALERTA DE ÉXITO AL GUARDAR/MODIFICAR ---
            Swal.fire({
                icon: 'success',
                title: esEdicion ? '¡Cambios Guardados!' : '¡Registro Exitoso!',
                text: esEdicion ? 'Información actualizada.' : 'Libro agregado correctamente.',
                showConfirmButton: false, 
                timer: 1500 
            }).then(() => {
                // ENVIAMOS EL FORMULARIO
                HTMLFormElement.prototype.submit.call(formLibro);
            });
        });
    }

    // 4. Cerrar modal al hacer clic afuera
    const modalL = document.getElementById('modalLibro');
    if (modalL) {
        modalL.addEventListener('click', (e) => {
            if (e.target === modalL) cerrarModalLibro();
        });
    }
});

// ==========================================
// FUNCIONES DE EDICIÓN Y NUEVO
// ==========================================

function editarLibro(data) { 
    datosOriginales = data; 

    // Llenamos los inputs
    document.getElementById('id_libro_form').value = data.id_libro;
    document.getElementById('tipo_form').value = data.tipo;
    document.getElementById('numero_libro_form').value = data.numero_libro;
    document.getElementById('distrito_form').value = data.distrito;
    document.getElementById('provincia_form').value = data.provincia;
    document.getElementById('anio_inicio_form').value = data.anio_inicio;
    document.getElementById('fecha_fin_form').value = data.fecha_fin || '';
    document.getElementById('descripcion_form').value = data.descripcion || '';

    // Cambiamos la ruta del form a modificar
    document.getElementById('formLibro').action = 'index.php?accion=modificar_libro'; 
    
    // Cambiamos el estilo del botón
    const btnSubmit = document.getElementById('btnSubmitLibro');
    if(btnSubmit) {
        btnSubmit.innerHTML = '<i class="fas fa-sync-alt"></i> <span>ACTUALIZAR LIBRO</span>';
        btnSubmit.style.background = 'var(--tech-blue)';
    }

    // 👇 LA MAGIA: Solo mostramos la "X" si la vista actual es la lista
    const btnCancelar = document.getElementById('btnCancelarEdicion');
    if(btnCancelar) {
        if (vistaActual === 'lista') {
            btnCancelar.style.display = 'flex';
        } else {
            btnCancelar.style.display = 'none';
        }
    }

    abrirPanelOModal();
}

function resetearFormulario() { 
    datosOriginales = null;
    
    const form = document.getElementById('formLibro');
    if(form) {
        form.reset();
        form.action = 'index.php?accion=guardar_libro';
    }
    
    const idInput = document.getElementById('id_libro_form');
    if(idInput) idInput.value = '';
    
    // Restauramos el botón a modo Guardar
    const btnSubmit = document.getElementById('btnSubmitLibro');
    if(btnSubmit) {
        btnSubmit.innerHTML = '<i class="fas fa-save"></i> <span>GUARDAR LIBRO</span>';
        btnSubmit.style.background = 'var(--tech-green)';
    }

    // 👇 AQUÍ SE OCULTA LA "X"
    const btnCancelar = document.getElementById('btnCancelarEdicion');
    if(btnCancelar) btnCancelar.style.display = 'none';
}

function nuevoLibro() {
    resetearFormulario();
    abrirPanelOModal();
}

// ==========================================
// FUNCIÓN ELIMINAR 
// ==========================================
function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Eliminar libro?',
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
            window.location.href = `index.php?accion=eliminar_libro&id=${id}`;
        }
    });
}

// ==========================================
// EXPORTAR EXCEL
// ==========================================
function exportarLibros() {
    try {
        if (typeof XLSX === 'undefined') {
            alert("La librería SheetJS no ha cargado.");
            return;
        }
        const tabla = document.getElementById('tablaLibros');
        if (!tabla) return;
        const clon = tabla.cloneNode(true);
        clon.querySelectorAll('tr').forEach(fila => {
            if (fila.cells.length > 0) fila.deleteCell(-1); 
        });
        const hoja = XLSX.utils.table_to_sheet(clon);
        const libro = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(libro, hoja, "Libros");
        XLSX.writeFile(libro, "Reporte_Libros_OEGPP.xlsx");
    } catch (e) { console.error("Error al exportar:", e); }
}

// ==========================================
// CONTROL DE VISTAS Y MODALES
// ==========================================
function aplicarVista(vista) {
    vistaActual = vista;
    localStorage.setItem('preferenciaVistaLibros', vista);

    const btnLista = document.getElementById('btnVistaLista');
    const btnGrid = document.getElementById('btnVistaGrid');
    const vistaListaCont = document.getElementById('vistaListaContenedor');
    const vistaGridCont = document.getElementById('vistaGridContenedor');
    const formMaestro = document.getElementById('formularioMaestro');
    const contenedorLateral = document.getElementById('contenedorFormLateral');
    const contenedorModal = document.getElementById('contenedorFormModal');

    // Revisamos si estamos en medio de una edición
    const idInput = document.getElementById('id_libro_form');
    const esEdicion = idInput && idInput.value !== "";
    const btnCancelar = document.getElementById('btnCancelarEdicion');

    if (vista === 'lista') {
        if(btnLista) btnLista.classList.add('active');
        if(btnGrid) btnGrid.classList.remove('active');
        if(vistaListaCont) vistaListaCont.style.display = 'flex';
        if(vistaGridCont) vistaGridCont.style.display = 'none';
        
        if(contenedorLateral && formMaestro) {
            contenedorLateral.appendChild(formMaestro);
            formMaestro.style.display = 'block';
        }
        // Si estamos editando y volvemos a la lista, regresamos la X
        if(esEdicion && btnCancelar) btnCancelar.style.display = 'flex';
        
    } else {
        if(btnGrid) btnGrid.classList.add('active');
        if(btnLista) btnLista.classList.remove('active');
        if(vistaListaCont) vistaListaCont.style.display = 'none';
        if(vistaGridCont) vistaGridCont.style.display = 'block';
        
        if(contenedorModal && formMaestro) {
            contenedorModal.appendChild(formMaestro);
            formMaestro.style.display = 'block';
        }
        // En el modal flotante SIEMPRE ocultamos esta X (ya tiene la suya arriba)
        if(btnCancelar) btnCancelar.style.display = 'none';
    }
}

function abrirPanelOModal() {
    if (vistaActual === 'lista') {
        document.getElementById('panelLateralForm').classList.remove('panel-oculto');
    } else {
        document.getElementById('modalLibro').classList.add('active');
        document.body.style.overflow = 'hidden'; 
    }
}

function toggleSidebar() {
    if (vistaActual === 'lista') {
        document.getElementById('panelLateralForm').classList.toggle('panel-oculto');
    } else {
        aplicarVista('lista');
    }
}

function cerrarModalLibro() {
    document.getElementById('modalLibro').classList.remove('active');
    document.body.style.overflow = '';
}