document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const libro = {
            id_libro: btn.dataset.id,
            numero_libro: btn.dataset.numero,
            descripcion: btn.dataset.descripcion,
            estado: btn.dataset.estado
        };
        editarLibro(libro);
    });
});

document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm("¿Eliminar este libro de registro?")) {
            window.location.href = "index.php?accion=eliminar_libro&id=" + btn.dataset.id;
        }
    });
});
