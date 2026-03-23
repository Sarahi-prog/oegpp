<div class="form-card">

    <h2>Nuevo Trabajador</h2>

    <form action="guardar.php" method="POST">

        <div class="form-group">
            <label>DNI</label>
            <input type="text" name="dni" required>
        </div>

        <div class="form-group">
            <label>Nombres</label>
            <input type="text" name="nombres" required>
        </div>

        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" name="apellidos" required>
        </div>

        <div class="form-group">
            <label>Correo</label>
            <input type="email" name="correo" required>
        </div>

        <div class="form-group">
            <label>Celular</label>
            <input type="text" name="celular">
        </div>

        <div class="form-group">
            <label>Área</label>
            <input type="text" name="area">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary-white">
                Guardar
            </button>

            <a href="trabajadores.php" class="btn btn-outline">
                Cancelar
            </a>
        </div>

    </form>

</div>