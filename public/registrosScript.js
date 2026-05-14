// Agrega esto arriba del todo, junto a configRegistro
let choicesCliente = null;
let choicesCurso = null;
let choicesLibro = null;

// Configuración del módulo
const configRegistro = {
    entity: 'registro',
    formId: 'formRegistro',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: { singular: 'Registro' }
};

document.addEventListener('DOMContentLoaded', () => {
    // --- AGREGA ESTO DENTRO DEL DOMContentLoaded ---
    // Configuración general para que esté en español y se vea limpio
    const choicesConfig = {
        searchEnabled: true,
        searchPlaceholderValue: 'Escribe para buscar...',
        itemSelectText: '', // Oculta el texto "Press to select"
        noResultsText: 'No se encontraron resultados',
        shouldSort: false // Respeta el orden de tu base de datos
    };

    // Aplicar a Cliente, Curso y Libro
    if (document.getElementById('cliente_id_input')) {
        choicesCliente = new Choices('#cliente_id_input', choicesConfig);
    }
    if (document.getElementById('curso_id_input')) {
        choicesCurso = new Choices('#curso_id_input', choicesConfig);
    }
    if (document.getElementById('libro_id_input')) {
        choicesLibro = new Choices('#libro_id_input', choicesConfig);
    }


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

// 🔹 EDITAR
function editarRegistro(data) { 
    let datosMapeados = { ...data };
    datosMapeados.cliente_id = data.cliente_id || data.id_cliente || "";
    datosMapeados.curso_id = data.curso_id || data.id_curso || "";
    datosMapeados.libro_id = data.libro_id || data.id_libro || "";

    datosOriginalesRegistro = datosMapeados; 
    
    // Aquí es donde UniversalScript te cambia la URL sin permiso
    modoFormularioUniversal(configRegistro, datosMapeados);

    // 👉 EL FIX MÁGICO: Obligamos al formulario a volver a "guardar_registro"
    document.getElementById('formRegistro').action = 'index.php?accion=guardar_registro';

    // Actualizar visualmente los Selects con Buscador (Choices.js)
    if (choicesCliente && datosMapeados.cliente_id) choicesCliente.setChoiceByValue(String(datosMapeados.cliente_id));
    if (choicesCurso && datosMapeados.curso_id) choicesCurso.setChoiceByValue(String(datosMapeados.curso_id));
    if (choicesLibro && datosMapeados.libro_id) choicesLibro.setChoiceByValue(String(datosMapeados.libro_id));

    document.getElementById('form-title').innerHTML = '<i class="fas fa-edit"></i> Editar Registro';
    document.getElementById('btn-submit-form').querySelector('span').textContent = 'Actualizar Registro';
    document.getElementById('btn-cancelar').style.display = 'inline-block';

    const panel = document.getElementById('seccionRegistro');
    if (panel && panel.classList.contains('panel-oculto')) toggleRegistro(); 
}

// 🔹 RESET FORM
function resetearFormulario() { 
    datosOriginalesRegistro = null;
    modoFormularioUniversal(configRegistro, false); 

    // 👉 EL FIX MÁGICO: Lo aseguramos también al cancelar
    document.getElementById('formRegistro').action = 'index.php?accion=guardar_registro';

    // Limpiar visualmente los Selects con Buscador
    if (choicesCliente) choicesCliente.setChoiceByValue('');
    if (choicesCurso) choicesCurso.setChoiceByValue('');
    if (choicesLibro) choicesLibro.setChoiceByValue('');

    document.getElementById('form-title').innerHTML = '<i class="fas fa-plus-circle"></i> Datos del Registro';
    document.getElementById('btn-submit-form').querySelector('span').textContent = 'Guardar Registro';
    document.getElementById('btn-cancelar').style.display = 'none';
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