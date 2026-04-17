// 1. Cuando la página carga, revisamos la memoria del navegador
document.addEventListener('DOMContentLoaded', () => {
    const panel = document.getElementById('seccionRegistro');
    
    // CORREGIDO: Clave sin espacios extra
    const estadoGuardado = localStorage.getItem('estadoPanelTrabajadores');

    // CORREGIDO: Usamos consistentemente 'panel-oculto'
    if (estadoGuardado === 'cerrado') {
        panel.classList.add('panel-oculto'); 
    }
});

// 2. Función PRINCIPAL para el botón hamburguesa
function toggleRegistro() {
    const panel = document.getElementById('seccionRegistro');
    
    // Alternar la clase visualmente
    panel.classList.toggle('panel-oculto');

    // Guardar el nuevo estado en la memoria del navegador
    if (panel.classList.contains('panel-oculto')) {
        localStorage.setItem('estadoPanelTrabajadores', 'cerrado');
    } else {
        localStorage.setItem('estadoPanelTrabajadores', 'abierto');
    }
}

// 3. Función para el botón verde "Nuevo Registro"
function abrirParaNuevo() {
    const panel = document.getElementById('seccionRegistro');
    const form = document.getElementById('formTrabajadorAjax');
    
    // Si el panel está oculto, lo muestra
    if (panel.classList.contains('panel-oculto')) {
        panel.classList.remove('panel-oculto');
        
        // ¡NUEVO!: Actualizamos la memoria para que recuerde que lo acabas de abrir
        localStorage.setItem('estadoPanelTrabajadores', 'abierto');
    }
    
    // Limpia el formulario para que esté listo para un registro nuevo
    form.reset();
    
    // Hace que el cursor parpadee en el campo DNI para escribir de inmediato
    setTimeout(() => {
        document.querySelector('input[name="dni"]').focus();
    }, 400); // Espera 400ms a que termine la animación
}