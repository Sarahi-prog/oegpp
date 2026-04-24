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
/**
 * Prepara el formulario lateral para editar un cliente existente.
 */
function editarCliente(cliente) {
    const seccion = document.getElementById('seccionRegistro');
    if (seccion.classList.contains('panel-oculto')) {
        toggleRegistro(); 
    }

    const form = document.getElementById('formCliente');
    const title = document.getElementById('form-title');
    const btnSubmit = document.getElementById('btn-submit-form');
    const btnCancelar = document.getElementById('btn-cancelar'); 

    // 1. Cambio de modo: Registro -> Edición
    form.action = "index.php?accion=modificar_cliente";
    title.innerHTML = '<i class="fas fa-edit"></i> Editar Cliente';
    
    // 2. Llenado de campos
    document.getElementById('id_cliente_form').value = cliente.id_cliente;
    form.querySelector('input[name="dni"]').value = cliente.dni;
    form.querySelector('input[name="nombres"]').value = cliente.nombres;
    form.querySelector('input[name="apellidos"]').value = cliente.apellidos;
    form.querySelector('input[name="correo"]').value = cliente.correo;
    form.querySelector('input[name="celular"]').value = cliente.celular;
    form.querySelector('input[name="area"]').value = cliente.area;
    form.querySelector('select[name="estado"]').value = cliente.estado;

    // 3. UI: CAMBIO A COLOR AZUL
    btnSubmit.querySelector('span').innerText = "Actualizar Datos";
    
    // Aplicamos el color azul directamente
    btnSubmit.style.backgroundColor = "#007bff"; // Azul primario
    btnSubmit.style.borderColor = "#0069d9";
    btnSubmit.style.color = "#ffffff"; // Aseguramos que el texto sea blanco

    if (btnCancelar) btnCancelar.style.display = 'block'; 
    
    seccion.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Restablece el formulario al estado original (Verde)
 */
function resetearFormulario() {
    const form = document.getElementById('formCliente');
    form.reset();
    form.action = "index.php?accion=guardarCliente";
    
    document.getElementById('id_cliente_form').value = "";
    document.getElementById('form-title').innerHTML = '<i class="fas fa-plus-circle"></i> Datos del Registro';
    
    const btnSubmit = document.getElementById('btn-submit-form');
    btnSubmit.querySelector('span').innerText = "Guardar Cliente";
    
    // VOLVER AL VERDE ORIGINAL
    btnSubmit.style.backgroundColor = "#28a745"; 
    btnSubmit.style.borderColor = "#218838";
    
    const btnCancelar = document.getElementById('btn-cancelar');
    if (btnCancelar) btnCancelar.style.display = 'none';
}