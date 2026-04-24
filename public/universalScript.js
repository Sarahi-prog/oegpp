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

/*Esto es para cambiar texto del botón de guardar a actualizar datos */ 

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




/// UNIVERSAL SCRIPT PARA CLIENTES Y CURSOS (Y FUTURAS ENTIDADES) - REUTILIZABLE Y CENTRALIZADO
//cambio de texto en botones uwu

/**
 * CONFIGURACIÓN UNIVERSAL PARA FORMULARIOS
 * @param {Object} config - Objeto con IDs y etiquetas del módulo
 * @param {boolean|Object} modo - 'false' para modo Guardar, 'Objeto con datos' para modo Editar
 */
function modoFormularioUniversal(config, modo = false) {
    const { entity, formId, btnId, btnCancelId, labels } = config;
    const esEdicion = !!modo;

    const form = document.getElementById(formId);
    const title = document.getElementById('form-title');
    const btnSubmit = document.getElementById(btnId);
    const btnCancelar = document.getElementById(btnCancelId);
    const seccion = document.getElementById('seccionRegistro');

    // DEBUG: Si algo no funciona, esto te dirá qué falta en la consola (F12)
    if (!form) { console.warn(`Cuidado: No encontré el formulario con ID: ${formId}`); return; }
    if (!btnSubmit) { console.warn(`Cuidado: No encontré el botón con ID: ${btnId}`); return; }

    if (seccion && seccion.classList.contains('panel-oculto')) {
        toggleRegistro();
    }

    // Cambiar Texto del Botón (Soporta con span y sin span)
    const spanBoton = btnSubmit.querySelector('span');
    const textoBtn = esEdicion ? `Actualizar ${labels.singular}` : `Guardar ${labels.singular}`;
    
    if (spanBoton) {
        spanBoton.innerText = textoBtn;
    } else {
        btnSubmit.innerText = textoBtn; // Si no hay span, cambia el texto directo
    }

    if (esEdicion) {
        // MODO AZUL
        form.action = `index.php?accion=modificar_${entity}`;
        if (title) title.innerHTML = `<i class="fas fa-edit"></i> Editar ${labels.singular}`;
        
        btnSubmit.style.backgroundColor = "#007bff";
        btnSubmit.style.borderColor = "#0069d9";
        btnSubmit.classList.replace('btn-success', 'btn-primary'); // Cambia clases de Bootstrap si las usas
        
        if (btnCancelar) btnCancelar.style.setProperty("display", "block", "important");

        // Llenado de campos por NAME
        Object.keys(modo).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) input.value = modo[key];
        });
    } else {
        // MODO VERDE
        form.reset();
        form.action = `index.php?accion=guardar_${entity}`;
        if (title) title.innerHTML = `<i class="fas fa-plus-circle"></i> Datos del ${labels.singular}`;
        
        btnSubmit.style.backgroundColor = "#28a745";
        btnSubmit.style.borderColor = "#218838";
        btnSubmit.classList.replace('btn-primary', 'btn-success');
        
        if (btnCancelar) btnCancelar.style.display = 'none';

        const idHidden = form.querySelector('input[type="hidden"]');
        if (idHidden) idHidden.value = "";
    }

    if (seccion) seccion.scrollIntoView({ behavior: 'smooth' });
}
 

const configCliente = {
    entity: 'cliente',
    formId: 'formCliente',
    btnId: 'btn-submit-form',
    btnCancelId: 'btn-cancelar',
    labels: { singular: 'Cliente' } // <-- Aquí defines la palabra mágica
};

function editarCliente(data) {
    modoFormularioUniversal(configCliente, data);
}

function resetearFormulario() { // O cancelarEdicion
    modoFormularioUniversal(configCliente, false);
}

const configCurso = {
    entity: 'curso',
    formId: 'formCursoAjax',
    btnId: 'btnSubmitCurso',
    btnCancelId: 'btnCancelarEdicion',
    labels: { singular: 'Programa' } // <-- Aquí usas 'Programa' o 'Curso'
};

function editarCurso(data) {
    modoFormularioUniversal(configCurso, data);
}

function cancelarEdicion() {
    modoFormularioUniversal(configCurso, false);
}