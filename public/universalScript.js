//HAMBURGUESA UNIVERSAL
//formulario desplegable

function toggleRegistro() {
    const panel = document.getElementById('seccionRegistro');
    if (panel) {
        panel.classList.toggle('panel-oculto');
    } else {
        console.warn("Advertencia: No se encontró el panel 'seccionRegistro' en esta página.");
    }
}


// --- 1. NOTIFICACIONES ---
const NotificacionOEGPP = Swal.mixin({
    toast: true,
    position: 'bottom',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    background: '#363636',
    color: '#ffffff',
    iconColor: '#00d1b2',
});

// Variable para rastrear cambios
let datosOriginales = null;

// --- 2. BUSCADOR UNIVERSAL ---
function iniciarBuscador(inputId, tbodyId) {
    const input = document.getElementById(inputId);
    const tbody = document.getElementById(tbodyId);
    if (!input || !tbody) return;

    input.addEventListener('input', function () {
        const valor = this.value.toLowerCase();
        Array.from(tbody.querySelectorAll('tr')).forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(valor) ? '' : 'none';
        });
    });
}

// --- 3. FORMULARIO UNIVERSAL ---
function modoFormularioUniversal(config, datos = false) {
    const { entity, formId, btnId, btnCancelId, labels } = config;
    const esEdicion = !!datos;

    const form = document.getElementById(formId);
    const title = document.getElementById('form-title');
    const btnSubmit = document.getElementById(btnId);
    const btnCancelar = document.getElementById(btnCancelId);

    if (!form || !btnSubmit) return;

    // Buscamos el icono y el span dentro del botón
    const iconoBoton = btnSubmit.querySelector('i');
    const spanBoton = btnSubmit.querySelector('span');

    if (esEdicion) {
        form.action = `index.php?accion=modificar_${entity}`;
        if (title) title.innerHTML = `<i class="fas fa-edit"></i> Editar ${labels.singular}`;
        
        // Cambio de Texto e Icono para Edición
        if (spanBoton) spanBoton.innerText = `Actualizar Datos`;
        if (iconoBoton) {
            iconoBoton.className = 'fas fa-sync-alt'; // Icono de refrescar/actualizar
        }

        if (btnCancelar) btnCancelar.style.display = 'block';

        // Guardamos copia para validar si hubo cambios luego
        datosOriginales = { ...datos };

        Object.entries(datos).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) input.value = value ?? '';
        });

    } else {
        form.reset();
        datosOriginales = null;
        form.action = `index.php?accion=guardar_${entity}`;
        if (title) title.innerHTML = `<i class="fas fa-plus-circle"></i> Datos del ${labels.singular}`;
        
        // Cambio de Texto e Icono para Guardar Nuevo
        if (spanBoton) spanBoton.innerText = `Guardar ${labels.singular}`;
        if (iconoBoton) {
            iconoBoton.className = 'fas fa-save'; // Icono de guardar normal
        }

        if (btnCancelar) btnCancelar.style.display = 'none';

        const idHidden = form.querySelector(`input[name="id_${entity}"]`);
        if (idHidden) idHidden.value = '';
    }
}