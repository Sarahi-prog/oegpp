/**
 * Confirma la eliminación con una alerta nativa.
 * @param {number} id - ID del cliente a eliminar.
 */
function confirmarEliminar(id) {
    if (confirm("¿Estás seguro de eliminar este registro? Esta acción es irreversible.")) {
        window.location.href = "index.php?accion=eliminar_cliente&id=" + id;
    }
}

/**
 * Prepara el formulario lateral para editar un cliente existente.
 * @param {Object} cliente - Objeto con los datos del cliente traídos desde el JSON del botón.
 */
function editarCliente(cliente) {
    // 1. Visibilidad del panel (si está oculto por el botón hamburguesa)
    const seccion = document.getElementById('seccionRegistro');
    if (seccion.classList.contains('panel-oculto')) {
        toggleRegistro(); // Asumiendo que esta función existe en UniversalScript.js
    }

    // 2. Referencias a elementos
    const form = document.getElementById('formCliente');
    const title = document.getElementById('form-title');
    const btnSubmit = document.getElementById('btn-submit-form');
    const btnCancelar = document.getElementById('btn-cancelar');

    // 3. Cambio de modo: Registro -> Edición
    form.action = "index.php?accion=modificar_cliente";
    title.innerHTML = '<i class="fas fa-edit"></i> Editar Cliente';
    
    // 4. Llenado de campos (Asegúrate que los nombres coincidan con el objeto PHP)
    document.getElementById('id_cliente_form').value = cliente.id_cliente;
    form.querySelector('input[name="dni"]').value = cliente.dni;
    form.querySelector('input[name="nombres"]').value = cliente.nombres;
    form.querySelector('input[name="apellidos"]').value = cliente.apellidos;
    form.querySelector('input[name="correo"]').value = cliente.correo;
    form.querySelector('input[name="celular"]').value = cliente.celular;
    form.querySelector('input[name="area"]').value = cliente.area;
    form.querySelector('select[name="estado"]').value = cliente.estado;

    // 5. UI: Feedback visual de que estamos editando
    btnSubmit.querySelector('span').innerText = "Actualizar Datos";
    btnSubmit.classList.replace('btn-primary-green', 'btn-primary-blue'); // Si existe estilo azul
    btnCancelar.style.display = 'block'; // Mostrar botón cancelar
    
    // Scroll suave hacia el formulario en móviles
    seccion.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Restablece el formulario a su estado original de "Nuevo Registro".
 */
function resetearFormulario() {
    const form = document.getElementById('formCliente');
    form.reset();
    form.action = "index.php?accion=guardarCliente";
    
    document.getElementById('id_cliente_form').value = "";
    document.getElementById('form-title').innerHTML = '<i class="fas fa-plus-circle"></i> Datos del Registro';
    
    const btnSubmit = document.getElementById('btn-submit-form');
    btnSubmit.querySelector('span').innerText = "Guardar Cliente";
    btnSubmit.classList.add('btn-primary-green');
    
    document.getElementById('btn-cancelar').style.display = 'none';
}