// clientesScript.js
// Función para editar un cliente (internamente trabajador)
function editarCliente(cliente) {
    const seccion = document.getElementById('seccionRegistro');
    if (seccion.classList.contains('panel-oculto')) {
        toggleRegistro(); // Mostrar panel si está oculto
    }

    const form = document.getElementById('formTrabajadorAjax');
    const title = document.querySelector('#seccionRegistro h3');
    const btnSubmit = form.querySelector('button[type="submit"]');

    // Cambiar acción a modificar
    form.action = "index.php?accion=modificar_cliente";
    title.innerHTML = '<i class="fas fa-edit"></i> Editar Cliente';

    // Llenar campos con los datos del cliente
    document.getElementById('id_trabajador').value = cliente.id_trabajador;
    form.querySelector('input[name="dni"]').value = cliente.dni;
    form.querySelector('input[name="nombres"]').value = cliente.nombres;
    form.querySelector('input[name="apellidos"]').value = cliente.apellidos;
    form.querySelector('input[name="correo"]').value = cliente.correo;
    form.querySelector('input[name="celular"]').value = cliente.celular;
    form.querySelector('input[name="area"]').value = cliente.area;
    form.querySelector('select[name="estado"]').value = cliente.estado;

    // Cambiar estilo del botón
    btnSubmit.innerHTML = '<i class="fas fa-save"></i> Actualizar Cliente';
    btnSubmit.classList.remove('btn-primary-green');
    btnSubmit.classList.add('btn-primary-blue');
}

// Función para resetear el formulario a modo "nuevo registro"
function resetearFormularioCliente() {
    const form = document.getElementById('formTrabajadorAjax');
    const title = document.querySelector('#seccionRegistro h3');
    const btnSubmit = form.querySelector('button[type="submit"]');

    form.reset();
    form.action = "index.php?accion=guardar_cliente";
    document.getElementById('id_trabajador').value = "";

    title.innerHTML = '<i class="fas fa-plus-circle"></i> Datos del Registro';
    btnSubmit.innerHTML = '<i class="fas fa-save"></i> Guardar Cliente';
    btnSubmit.classList.remove('btn-primary-blue');
    btnSubmit.classList.add('btn-primary-green');
}

// Ejemplo de cómo enganchar los botones de la tabla
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-edit').forEach((btn, index) => {
        btn.addEventListener('click', () => {
            // Aquí deberías tener los datos del cliente en JS.
            // Si los pasas como atributos data-* en el botón, puedes leerlos así:
            const cliente = {
                id_trabajador: btn.dataset.id,
                dni: btn.dataset.dni,
                nombres: btn.dataset.nombres,
                apellidos: btn.dataset.apellidos,
                correo: btn.dataset.correo,
                celular: btn.dataset.celular,
                area: btn.dataset.area,
                estado: btn.dataset.estado
            };
            editarCliente(cliente);
        });
    });

    document.querySelectorAll('.btn-delete').forEach((btn) => {
        btn.addEventListener('click', () => {
            if (confirm("¿Estás seguro de eliminar este cliente?")) {
                window.location.href = "index.php?accion=eliminar_cliente&id=" + btn.dataset.id;
            }
        });
    });
});
