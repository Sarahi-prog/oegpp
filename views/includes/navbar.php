<nav class="navbar">
    <div class="container">
        <div class="navbar-content">
            <a href="index.php?accion=inicio" class="navbar-brand" style="text-decoration: none;">
                <div class="logo"><i class="fas fa-graduation-cap"></i></div>
                <div class="brand-text">
                    <h1>OEGPP</h1>
                    <p>BRINDACIÓN DE CURSOS</p>
                </div>
            </a>

            <div class="navbar-menu">
                <a href="index.php?accion=inicio" class="menu-item <?= ($pagina_actual == 'inicio') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Inicio
                </a>
                
                <a href="index.php?accion=trabajadores" class="menu-item <?= ($pagina_actual == 'trabajadores') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Trabajadores
                </a>
                
                <a href="#" class="menu-item <?= ($pagina_actual == 'cursos') ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar"></i> Cursos
                </a>
                
                <?php if (isset($_SESSION['admin_usuario'])): ?>
                <div class="admin-info">
                    <div class="admin-card">
                        <div class="admin-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="admin-details">
                            <span class="admin-label">Administrador General</span>
                            <span class="admin-usuario"><?= htmlspecialchars($_SESSION['admin_usuario']) ?></span>
                            <span class="admin-correo"><?= htmlspecialchars($_SESSION['admin_correo'] ?? '') ?></span>
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