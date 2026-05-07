/**
 * Configura el formulario para modo EDICIÓN (Azul)
 * @param {string} entity - 'cliente' o 'curso'
 * @param {Object} data - Los datos del objeto a editar
 */
function modoEditarUniversal(entity, data) {
    const isCurso = (entity === 'curso');
    
    // IDs Dinámicos según la entidad
    const formId = isCurso ? 'formCursoAjax' : 'formCliente';
    const btnSubmitId = isCurso ? 'btnSubmitCurso' : 'btn-submit-form';
    const btnCancelId = isCurso ? 'btnCancelarEdicion' : 'btn-cancelar';
    const titleText = isCurso ? 'Editar Programa' : 'Editar Cliente';
    const buttonText = isCurso ? "Actualizar Programa" : "Actualizar Datos";

    const form = document.getElementById(formId);
    const title = document.getElementById('form-title'); // Compartido o específico
    const btnSubmit = document.getElementById(btnSubmitId);
    const btnCancelar = document.getElementById(btnCancelId);
    const seccion = document.getElementById('seccionRegistro');

    // 1. Abrir panel si está oculto
    if (seccion && seccion.classList.contains('panel-oculto')) {
        toggleRegistro(); 
    }

    // 2. Cambio de Action y Título
    form.action = isCurso ? "index.php?accion=modificar_curso" : "index.php?accion=modificar_cliente";
    if (title) title.innerHTML = `<i class="fas fa-edit"></i> ${titleText}`;

    // 3. Llenado de campos automático
    if (isCurso) {
        document.getElementById('id_curso_form').value = data.id_curso;
        document.getElementById('codigo_curso_form').value = data.codigo_curso;
        document.getElementById('nombre_curso_form').value = data.nombre_curso;
        document.getElementById('tipo_form').value = data.tipo;
        document.getElementById('horas_totales_form').value = data.horas_totales;
    } else {
        document.getElementById('id_cliente_form').value = data.id_cliente;
        form.querySelector('input[name="dni"]').value = data.dni;
        form.querySelector('input[name="nombres"]').value = data.nombres;
        form.querySelector('input[name="apellidos"]').value = data.apellidos;
        form.querySelector('input[name="correo"]').value = data.correo;
        form.querySelector('input[name="celular"]').value = data.celular;
        form.querySelector('input[name="area"]').value = data.area;
        form.querySelector('select[name="estado"]').value = data.estado;
    }

    // 4. Estilo de Botones (AZUL)
    const span = btnSubmit.querySelector('span');
    if (span) span.innerText = buttonText;
    
    btnSubmit.style.backgroundColor = "#007bff";
    btnSubmit.style.borderColor = "#0069d9";
    btnSubmit.style.color = "#ffffff";

    if (btnCancelar) btnCancelar.style.display = 'block';
    
    seccion.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Restablece cualquier formulario al modo GUARDAR (Verde)
 * @param {string} entity - 'cliente' o 'curso'
 */
function modoGuardarUniversal(entity) {
    const isCurso = (entity === 'curso');
    const formId = isCurso ? 'formCursoAjax' : 'formCliente';
    const btnSubmitId = isCurso ? 'btnSubmitCurso' : 'btn-submit-form';
    const btnCancelId = isCurso ? 'btnCancelarEdicion' : 'btn-cancelar';
    
    const form = document.getElementById(formId);
    form.reset();

    // 1. Reset Action e ID
    form.action = isCurso ? "index.php?accion=guardar_curso" : "index.php?accion=guardarCliente";
    const idField = isCurso ? 'id_curso_form' : 'id_cliente_form';
    document.getElementById(idField).value = "";

    // 2. Título original
    const title = document.getElementById('form-title');
    const titleText = isCurso ? 'Datos del Programa' : 'Datos del Registro';
    if (title) title.innerHTML = `<i class="fas fa-plus-circle"></i> ${titleText}`;

    // 3. Estilo de Botones (VERDE)
    const btnSubmit = document.getElementById(btnSubmitId);
    const span = btnSubmit.querySelector('span');
    if (span) span.innerText = isCurso ? "Guardar Programa" : "Guardar Cliente";

    btnSubmit.style.backgroundColor = "#28a745";
    btnSubmit.style.borderColor = "#218838";

    // 4. Ocultar Cancelar
    const btnCancelar = document.getElementById(btnCancelId);
    if (btnCancelar) btnCancelar.style.display = 'none';
}



// Función para actualizar el estado del curso al mover el switch
function confirmarEstado(checkbox, idCurso) {
    // Si está marcado es 1 (activo), si no es 0 (inactivo)
    const estadoNuevo = checkbox.checked ? 1 : 0;

    // Petición AJAX al controlador
    fetch('index.php?accion=actualizar_estado_curso', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_curso=${idCurso}&estado=${estadoNuevo}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.exito) {
            alert("Hubo un error al actualizar el estado en el servidor.");
            // Revertir visualmente el switch si falló
            checkbox.checked = !checkbox.checked;
        }
        // Si tiene éxito, la próxima vez que entres al Dashboard el número estará actualizado
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error de conexión al intentar actualizar el estado.");
        checkbox.checked = !checkbox.checked;
    });
}