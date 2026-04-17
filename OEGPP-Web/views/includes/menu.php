<nav class="navbar">
    <div class="container">
            <div class="navbar-content">

                <a href="index.php?accion=inicio" class="navbar-brand">
                    <div class="logo-img-container">
                        <img src="views/img/logo_2.png" alt="Logo OEGPP">
                    </div>
                    
                    <div class="brand-text">
                        <h1>OEGPP</h1>
                        <p>BRINDACIÓN DE CURSOS</p>
                    </div>
                </a>

                <div class="navbar-menu">
                    
                    <a href="index.php?accion=inicio" class="menu-item <?= (isset($pagina_actual) && $pagina_actual == 'inicio') ? 'active' : '' ?>">
                        <i class="fas fa-home"></i> Inicio
                    </a>
                    
                    <a href="index.php?accion=clientes" class="menu-item <?= (isset($pagina_actual) && $pagina_actual == 'clientes') ? 'active' : '' ?>">
                        <i class="fas fa-users"></i> Clientes
                    </a>
                    
                    <a href="index.php?accion=registros_capacitacion" class="menu-item <?= (isset($pagina_actual) && $pagina_actual == 'registros_capacitacions') ? 'active' : '' ?>">
                        <i class="fas fa-graduation-cap"></i> Diplomados y Certificados
                    </a>
                    
                    <a href="index.php?accion=notas" class="menu-item <?= (isset($pagina_actual) && in_array($pagina_actual, ['notas','notas_modulo'], true)) ? 'active' : '' ?>">
                        <i class="fas fa-cubes"></i> Notas de Diplomados
                    </a>
                    
                </div>



                
                
            </div>
        </div>
    </nav>

 