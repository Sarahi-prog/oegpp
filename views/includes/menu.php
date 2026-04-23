<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<nav class="navbar">
    <div class="container">
        <div class="navbar-content">
            <a href="index.php?accion=inicio" class="navbar-brand" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
                <div class="logo">
                    <img src="public/images/logo.jpg" alt="OEGPP Logo" class="logo-img" style="max-height: 50px; display: block;">
                </div>
                <div class="brand-text">
                    <h1>OEGPP</h1>
                    <p>BRINDACIÓN DE CURSOS</p>
                </div>
            </a>

            <div class="navbar-menu">
                <a href="index.php?accion=inicio" class="menu-item <?= ($pagina_actual == 'inicio') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="index.php?accion=registros_capacitacion" class="menu-item <?= ($pagina_actual == 'registros') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Registros de Capacitación
                </a>
                <a href="index.php?accion=notas" class="menu-item <?= ($pagina_actual == 'notas') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Notas de Diplomados
                </a>
                <a href="index.php?accion=trabajadores" class="menu-item <?= ($pagina_actual == 'clientes') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Clientes
                </a>

                <a href="#" class="menu-item <?= ($pagina_actual == 'cursos') ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i> Cursos
                </a>

                <?php if (isset($_SESSION['admin_general']) && $_SESSION['admin_general'] === true): ?>
                    <a href="index.php?accion=autorizacionUsuarios" class="menu-item <?= ($pagina_actual == 'autorizacion') ? 'active' : '' ?>" style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; margin-top: 5px;">
                        <i class="fas fa-user-shield"></i> Autorizaciones
                    </a>
                <?php endif; ?>

                <?php if (!empty($_SESSION['admin_usuario']) || !empty($_SESSION['admin_correo'])): ?>
                    <div class="admin-info">
                        <div class="admin-card">
                            <div class="admin-avatar">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="admin-details">
                                <span class="admin-label">
                                    <?= (!empty($_SESSION['admin_general']) && $_SESSION['admin_general'] === true) ? 'Administrador General' : 'Administrador' ?>
                                </span>
                                <span class="admin-usuario">
                                    <?= htmlspecialchars($_SESSION['admin_usuario'] ?? $_SESSION['admin_correo'] ?? 'Administrador') ?>
                                </span>
                                <?php if (!empty($_SESSION['admin_correo'])): ?>
                                    <span class="admin-correo"><?= htmlspecialchars($_SESSION['admin_correo']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <a href="index.php?accion=logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>
    </div>
</nav>
