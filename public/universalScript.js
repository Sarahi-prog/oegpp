// public/panelScript.js

// --- NUEVO: Detector automático de módulo ---
// Lee la URL (ej. ?accion=cursos) y crea un nombre único. Si no hay acción, usa 'global'.
function obtenerNombreModulo() {
    const parametrosUrl = new URLSearchParams(window.location.search);
    const accion = parametrosUrl.get('accion');
    return accion ? `estadoPanel_${accion}` : 'estadoPanel_global';
}

// 1. Cuando la página carga, revisamos la memoria ESPECÍFICA de este módulo
document.addEventListener('DOMContentLoaded', () => {
    const panel = document.getElementById('seccionRegistro');
    if (!panel) return; 

    const claveMemoria = obtenerNombreModulo();
    const estadoGuardado = localStorage.getItem(claveMemoria);

    if (estadoGuardado === 'cerrado') {
        panel.classList.add('panel-oculto'); 
    }
});

// 2. Función PRINCIPAL para el botón hamburguesa
function toggleRegistro() {
    const panel = document.getElementById('seccionRegistro');
    if (!panel) return;
    
    const claveMemoria = obtenerNombreModulo();
    panel.classList.toggle('panel-oculto');

    // Guarda el estado usando el nombre específico del módulo actual
    if (panel.classList.contains('panel-oculto')) {
        localStorage.setItem(claveMemoria, 'cerrado');
    } else {
        localStorage.setItem(claveMemoria, 'abierto');
    }
}

// 3. Función Universal para el botón verde "Nuevo Registro"
function abrirParaNuevo() {
    const panel = document.getElementById('seccionRegistro');
    if (!panel) return;

    const claveMemoria = obtenerNombreModulo();
    const form = panel.querySelector('form'); 
    
    if (panel.classList.contains('panel-oculto')) {
        panel.classList.remove('panel-oculto');
        localStorage.setItem(claveMemoria, 'abierto'); // Actualiza la memoria específica
    }
    
    if (form) {
        // Limpia el formulario
        form.reset();
        
        setTimeout(() => {
            // Busca el PRIMER campo del formulario que no esté oculto
            const primerCampo = form.querySelector('input:not([type="hidden"]), select, textarea');
            if (primerCampo) {
                primerCampo.focus(); 
            }
        }, 400); 
    }
}