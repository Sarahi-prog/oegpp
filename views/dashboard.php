<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Panel de Control OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/dashboardStyles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="tech-theme">

    <?php include 'includes/menu.php'; ?>

    <div class="hero-banner">
        <div class="hero-overlay"></div> 
        <div class="hero-bg-grid"></div> <div class="container hero-content">
            <div class="hero-text">
                <div class="tech-badge">VERSIÓN 2.0</div>
                <h1 class="hero-title">Sistema de Gestión Académica <span class="highlight">OEGPP</span></h1>
                <p class="hero-subtitle">Administra trabajadores, cursos, diplomados y certificaciones desde un panel de control centralizado y de alto rendimiento.</p>
                
                <div class="hero-buttons">
                    <button class="btn btn-tech" onclick="window.location.href='index.php?accion=clientes'">
                        <i class="fas fa-users"></i> Clientes
                    </button>
                    <button class="btn btn-tech" onclick="window.location.href='index.php?accion=cursos'">
                        <i class="fas fa-book-open"></i> Cursos
                    </button>
                    <button class="btn btn-tech" onclick="window.location.href='index.php?accion=modulos'">
                        <i class="fas fa-cogs"></i> Módulos
                    </button>
                    <button class="btn btn-tech" onclick="window.location.href='index.php?accion=libros_registro'">
                        <i class="fas fa-book"></i> Libros
                    </button>
                    <button class="btn btn-tech-outline" onclick="window.location.href='index.php?accion=registros_capacitacion'">
                        <i class="fas fa-plus"></i> Nueva Asignación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container main-content">
        
        <div class="section-header">
            <div class="section-title">
                <h2>Métricas del Sistema <i class="fas fa-satellite-dish pulse-icon"></i></h2>
                <p>Datos sincronizados en tiempo real</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card glass-panel" onclick="window.location.href='index.php?accion=trabajadores'">
                <div class="stat-header icon-green">
                    <div class="stat-icon-wrapper"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Gestión de Usuarios</p>
                    <p class="stat-value action-text">Acceder al módulo <i class="fas fa-chevron-right"></i></p>
                </div>
                <div class="card-glow"></div>
            </div>

            <div class="stat-card glass-panel">
                <div class="stat-header icon-blue">
                    <div class="stat-icon-wrapper"><i class="fas fa-book-open"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Cursos Activos</p>
                    <p class="stat-value text-glow-blue">24</p>
                    <p class="stat-trend trend-up"><i class="fas fa-arrow-trend-up"></i> 8 nuevos cursos</p>
                </div>
                <div class="card-glow"></div>
            </div>

            <div class="stat-card glass-panel">
                <div class="stat-header icon-orange">
                    <div class="stat-icon-wrapper"><i class="fas fa-award"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Certificaciones Emitidas</p>
                    <p class="stat-value text-glow-orange">156</p>
                    <p class="stat-trend trend-up"><i class="fas fa-arrow-trend-up"></i> +28 este mes</p>
                </div>
                <div class="card-glow"></div>
            </div>

            <div class="stat-card glass-panel">
                <div class="stat-header icon-purple">
                    <div class="stat-icon-wrapper"><i class="fas fa-file-contract"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Diplomas Entregados</p>
                    <p class="stat-value text-glow-purple">94</p>
                    <p class="stat-trend trend-up"><i class="fas fa-arrow-trend-up"></i> +5% vs mes anterior</p>
                </div>
                <div class="card-glow"></div>
            </div>
        </div>

        <div class="video-section glass-panel mt-5">
            <div class="section-header center-header">
                <h2><i class="fas fa-play-circle"></i> Presentación del Sistema</h2>
                <p>Descubre cómo maximizar el uso del portal OEGPP</p>
            </div>
            
            <div class="video-container">
                <iframe 
                    src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                    title="Video OEGPP" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>

    </div> 
    <script>
  document.addEventListener('mousemove', e => {
    document.body.style.setProperty('--mouse-x', (e.clientX) + 'px');
    document.body.style.setProperty('--mouse-y', (e.clientY) + 'px');
  });
</script>   

</body>
</html>