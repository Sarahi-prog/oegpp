// Función principal para mostrar/ocultar con las tres rayitas
function toggleRegistro() {
    const panel = document.getElementById('seccionRegistro');
    panel.classList.toggle('panel-oculto');
}

// Función para el botón verde "Nuevo Registro"
function abrirParaNuevo() {
    const panel = document.getElementById('seccionRegistro');
    const form = document.getElementById('formTrabajadorAjax');
    
    // Si el panel está oculto, lo muestra
    if (panel.classList.contains('panel-oculto')) {
        panel.classList.remove('panel-oculto');
    }
    
    // Limpia el formulario para que esté listo para un registro nuevo
    form.reset();
    
    // Hace que el cursor parpadee en el campo DNI para escribir de inmediato
    setTimeout(() => {
        document.querySelector('input[name="dni"]').focus();
    }, 400); // Espera 400ms a que termine la animación de abrir el panel
}