<nav class="navbar">
    <div class="container">
        <div class="navbar-content">
            <a href="index.php?accion=inicio" class="navbar-brand">
                <div class="logo-img-container">
                    <img src="views/img/logo_2.png" alt="Logo OEGPP">
                </div>
                <div class="brand-text">
                    <h1>OEGPP</h1>
                    <p></p>
                </div>
            </a>


            <div class="navbar-menu" id="navbar-links">
                <a href="index.php?accion=inicio" class="menu-item <?= (isset($pagina_actual) && $pagina_actual == 'inicio') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Inicio
                </a>
                <a href="index.php?accion=clientes" class="menu-item <?= (isset($pagina_actual) && $pagina_actual == 'clientes') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Clientes
                </a>
                <a href="index.php?accion=registros_capacitacion" class="menu-item <?= (isset($pagina_actual) && ($pagina_actual == 'registros_capacitacion' || $pagina_actual == 'registros_capacitacions')) ? 'active' : '' ?>">
                    <i class="fas fa-graduation-cap"></i> Diplomados
                </a>
                <a href="index.php?accion=notas" class="menu-item <?= (isset($pagina_actual) && in_array($pagina_actual, ['notas','notas_modulo'], true)) ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i> Notas
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

            <div class="mobile-menu">

                <button class="hamburger" onclick="toggleMenu()">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
</nav>
 