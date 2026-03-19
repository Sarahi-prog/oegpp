<?php require_once 'menu.php'; ?>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Trabajadores Registrados</h2>
    <a href="index.php?accion=guardartrabajador" class="btn btn-success">+ Nuevo Trabajador</a>
  </div>

  <?php if (empty($trabajadores)): ?>
    <div class="alert alert-warning text-center">No hay trabajadores registrados aún.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Correo</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($trabajadores as $t): ?>
            <tr>
              <td><?= htmlspecialchars($t->getId_trabajador()) ?></td>
              <td><?= $t->getNombres() ?></td>
              <td><?= $t->getApellidos() ?></td>
              <td><?= $t->getDni() ?></td>
              <td><?= $t->getCorreo() ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>