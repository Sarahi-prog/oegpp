/**
 * Confirma la eliminación con una alerta nativa.
 * @param {number} id - ID del curso a eliminar.
 */
function eliminarCurso(id) {
    if (confirm("¿Estás seguro de eliminar este programa? Esta acción es irreversible.")) {
        window.location.href = "index.php?accion=eliminar_curso&id=" + id;
    }
}

/**
 * Prepara el formulario lateral para editar un curso existente.
 * @param {Object} curso - Objeto con los datos del curso traídos desde el JSON del botón.
 */
function editarCurso(curso) {
    const seccion = document.getElementById('seccionRegistro');
    // Despliega el panel lateral si está oculto (asumiendo que usas toggleRegistro)
    if (seccion && seccion.classList.contains('panel-oculto')) {
        toggleRegistro(); 
    }

    const form = document.getElementById('formCursoAjax');
    const title = document.getElementById('form-title'); // Asegúrate de que tu título tenga este ID o cámbialo a 'form-title-curso'
    const btnSubmit = document.getElementById('btnSubmitCurso');
    const btnCancelar = document.getElementById('btnCancelarEdicion'); 

    // 1. Cambio de modo: Registro -> Edición
    form.action = "index.php?accion=modificar_curso";
    if (title) title.innerHTML = '<i class="fas fa-edit"></i> Editar Programa';
    
    // 2. Llenado de campos
    document.getElementById('id_curso_form').value = curso.id_curso;
    document.getElementById('codigo_curso_form').value = curso.codigo_curso;
    document.getElementById('nombre_curso_form').value = curso.nombre_curso;
    document.getElementById('tipo_form').value = curso.tipo;
    document.getElementById('horas_totales_form').value = curso.horas_totales;

    // 3. UI: CAMBIO A COLOR AZUL
    btnSubmit.innerHTML = '<i class="fas fa-sync-alt"></i> Actualizar Programa';
    
    // Aplicamos el color azul directamente
    btnSubmit.style.backgroundColor = "#007bff"; // Azul primario
    btnSubmit.style.borderColor = "#0069d9";
    btnSubmit.style.color = "#ffffff"; // Aseguramos que el texto sea blanco

    // Mostrar el botón de cancelar
    if (btnCancelar) btnCancelar.style.display = 'inline-block'; 
    
    // Scroll suave hacia la sección
    if (seccion) seccion.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Restablece el formulario al estado original (Verde) y cancela la edición.
 */
function cancelarEdicion() {
    const form = document.getElementById('formCursoAjax');
    form.reset();
    
    // 1. Resetear el action original
    form.action = "index.php?accion=guardar_curso";
    
    // 2. Limpiar ID oculto y restaurar título
    document.getElementById('id_curso_form').value = "";
    const title = document.getElementById('form-title');
    if (title) title.innerHTML = '<i class="fas fa-plus-circle"></i> Datos del Programa';
    
    const btnSubmit = document.getElementById('btnSubmitCurso');
    btnSubmit.innerHTML = '<i class="fas fa-save"></i> Guardar Programa';
    
    // 3. VOLVER AL VERDE ORIGINAL
    btnSubmit.style.backgroundColor = "#28a745"; 
    btnSubmit.style.borderColor = "#218838";
    btnSubmit.style.color = "#ffffff";
    
    // 4. Ocultar botón cancelar
    const btnCancelar = document.getElementById('btnCancelarEdicion');
    if (btnCancelar) btnCancelar.style.display = 'none';
}