let workers = [];

async function loadWorkers() {
    const response = await fetch('controllers/TrabajadoresController.php');
    workers = await response.json();

    totalWorkers.textContent = workers.length;
    totalCount.textContent = workers.length;

    renderTable(workers);
}

// Elementos del DOM
const searchInput = document.getElementById('searchInput');
const workersTableBody = document.getElementById('workersTableBody');
const emptyState = document.getElementById('emptyState');
const tableContainer = document.querySelector('.table-container');
const totalWorkers = document.getElementById('totalWorkers');
const displayCount = document.getElementById('displayCount');
const totalCount = document.getElementById('totalCount');
const modalOverlay = document.getElementById('modalOverlay');
const workerForm = document.getElementById('workerForm');
const emptyMessage = document.getElementById('emptyMessage');

// Renderizar tabla
function renderTable(workersToShow) {
    // Limpiar tabla
    workersTableBody.innerHTML = '';

    if (workersToShow.length === 0) {
        // Mostrar estado vacío
        tableContainer.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    // Ocultar estado vacío
    tableContainer.style.display = 'block';
    emptyState.style.display = 'none';

    // Renderizar filas
    workersToShow.forEach((worker) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>#${String(worker.id).padStart(3, '0')}</td>
            <td>${worker.nombres}</td>
            <td>${worker.apellidos}</td>
            <td>${worker.dni}</td>
            <td>${worker.correo}</td>
            <td class="text-right">
                <button class="action-btn" onclick="showWorkerOptions(${worker.id})">
                    <i class="fas fa-ellipsis-vertical"></i>
                </button>
            </td>
        `;
        workersTableBody.appendChild(row);
    });

    // Actualizar contadores
    displayCount.textContent = workersToShow.length;
    totalCount.textContent = workers.length;
}

// Buscar trabajadores
function searchWorkers(searchTerm) {
    const term = searchTerm.toLowerCase().trim();
    
    if (term === '') {
        emptyMessage.textContent = 'No hay profesionales registrados aún.';
        return workers;
    }

    const filtered = workers.filter((worker) => {
        return (
            worker.nombres.toLowerCase().includes(term) ||
            worker.apellidos.toLowerCase().includes(term) ||
            worker.dni.includes(term) ||
            worker.correo.toLowerCase().includes(term)
        );
    });

    emptyMessage.textContent = 'No se encontraron profesionales con ese criterio de búsqueda.';
    return filtered;
}

// Event listener para búsqueda
searchInput.addEventListener('input', (e) => {
    const searchTerm = e.target.value;
    const filteredWorkers = searchWorkers(searchTerm);
    renderTable(filteredWorkers);
});

// Modal functions
function openModal() {
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = 'auto';
    workerForm.reset();
}

// Cerrar modal con ESC
document.addEventListener('DOMContentLoaded', () => {
    loadWorkers();
});

// Enviar formulario
workerForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = {
        id: workers.length > 0 ? Math.max(...workers.map(w => w.id)) + 1 : 1,
        nombres: document.getElementById('nombres').value.trim(),
        apellidos: document.getElementById('apellidos').value.trim(),
        dni: document.getElementById('dni').value.trim(),
        correo: document.getElementById('correo').value.trim(),
    };

    // Agregar trabajador
    workers.push(formData);

    // Actualizar UI
    totalWorkers.textContent = workers.length;
    renderTable(searchWorkers(searchInput.value));

    // Cerrar modal
    closeModal();

    // Mostrar notificación (opcional)
    showNotification('Profesional agregado exitosamente');
});

// Mostrar opciones de trabajador
function showWorkerOptions(id) {
    const worker = workers.find(w => w.id === id);
    if (worker) {
        alert(`Opciones para: ${worker.nombres} ${worker.apellidos}\n\nID: ${worker.id}\nDNI: ${worker.dni}\nCorreo: ${worker.correo}`);
    }
}

// Notificación simple
function showNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #22c55e 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
        font-weight: 600;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Animaciones CSS para notificaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);


//REGISTRAR USUAARIO
function nextStep(step) {
    document.querySelectorAll(".step").forEach(s => s.style.display = "none");
    document.getElementById("step" + step).style.display = "block";
}

function prevStep(step) {
    document.querySelectorAll(".step").forEach(s => s.style.display = "none");
    document.getElementById("step" + step).style.display = "block";
}


//dfdsfsdfdsf


// 1. Lógica para abrir y cerrar el Modal
    function abrirModal() {
        document.getElementById('modalNuevoUsuario').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('modalNuevoUsuario').style.display = 'none';
    }

    // 2. Simulación de guardado rápido y actualización del select principal
    function guardarNuevoTrabajador(event) {
        event.preventDefault(); // Evita que la página se recargue
        
        let nombres = document.getElementById('nuevoNombres').value;
        let apellidos = document.getElementById('nuevoApellidos').value;
        let dni = document.getElementById('nuevoDni').value;

        // Crear una nueva opción para el select principal
        let selectUsuario = document.getElementById('usuarioSelect');
        let nuevaOpcion = document.createElement('option');
        
        // Asignamos un ID temporal (En la vida real, este ID vendría de tu base de datos)
        nuevaOpcion.value = "nuevo_" + dni; 
        nuevaOpcion.text = `${nombres} ${apellidos} (DNI: ${dni})`;
        
        // Agregamos la opción y la dejamos seleccionada automáticamente
        selectUsuario.add(nuevaOpcion);
        selectUsuario.value = nuevaOpcion.value;

        alert('Trabajador agregado temporalmente a la lista.');
        
        // Limpiamos y cerramos el modal
        document.getElementById('nuevoTrabajadorForm').reset();
        cerrarModal();
    }

    // 3. Lógica para cambiar los cursos disponibles según el tipo (Certificado o Diplomado)
    function actualizarCursos() {
        let tipo = document.getElementById('tipoCurso').value;
        let cursoSelect = document.getElementById('cursoSelect');
        
        // Limpiamos las opciones actuales
        cursoSelect.innerHTML = '<option value="">-- Seleccione un curso --</option>';

        // Opciones simuladas (Esto normalmente se trae de la BD)
        if (tipo === 'certificados') {
            cursoSelect.add(new Option('Gestión Pública Básica', 'c1'));
            cursoSelect.add(new Option('Ofimática para el Estado', 'c2'));
        } else if (tipo === 'diplomados') {
            cursoSelect.add(new Option('Diplomado en Contrataciones del Estado', 'd1'));
            cursoSelect.add(new Option('Diplomado en Proyectos de Inversión', 'd2'));
        }
    }

    ///////////// Modal de Registro de Cursos - Lógica para abrir, cerrar y cargar cursos dinámicamente
    function toggleModal(show) {
            const modal = document.getElementById('modalRegistro');
            modal.style.display = show ? 'flex' : 'none';
        }

        // Cerrar al hacer click fuera de la caja blanca
        window.onclick = function(event) {
            const modal = document.getElementById('modalRegistro');
            if (event.target == modal) {
                toggleModal(false);
            }
        }

        // Lógica simple para cargar cursos (Ejemplo)
        function cargarCursos() {
            const tipo = document.getElementById('tipoCurso').value;
            console.log("Cargando lista de tipo: " + tipo);
        }