<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Panel de Control OEGPP</title>
    
    <link rel="stylesheet" href="public/dashboardStyles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?php echo time(); ?>">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <?php include 'includes/menu.php'; ?>

    <div class="hero-banner">
        <div class="hero-overlay"></div> <div class="hero-bg"></div>      <div class="container hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Sistema de Gestión Académica OEGPP</h1>
                <p class="hero-subtitle">Administra trabajadores, cursos, diplomados y certificaciones desde un solo lugar.</p>
                
                <div class="hero-buttons">
                    <button class="btn btn-primary-green" onclick="window.location.href='index.php?accion=clientes'">
                        <i class="fas fa-users"></i> Ver Clientes
                    </button>
                    <button class="btn btn-primary-green" onclick="window.location.href='index.php?accion=cursos'">
                        <i class="fas fa-book-open"></i> Ver Cursos
                    </button>
                    <button class="btn btn-primary-green" onclick="window.location.href='index.php?accion=modulos'">
                        <i class="fas fa-cogs"></i> Ver Módulos
                    </button>
                    <button class="btn btn-primary-green" onclick="window.location.href='index.php?accion=libros_registro'">
                        <i class="fas fa-book"></i> Ver Libros
                    </button>
                    <button class="btn btn-outline-white" onclick="window.location.href='index.php?accion=registros_capacitacion'">
                        <i class="fas fa-plus"></i> Nueva Asignación
                    </button>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="container main-content">
        
        <div class="section-header">
            <div class="section-title">
                <h2>Resumen General</h2>
                <p>Métricas actualizadas del sistema</p>
            </div>
        </div>

        <div class="stats-grid">
            
            <div class="stat-card" onclick="window.location.href='index.php?accion=trabajadores'" style="cursor: pointer;">
                <div class="stat-header green-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper"><i class="fas fa-users"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Gestión de Usuarios</p>
                    <p class="stat-value">Ir al módulo <i class="fas fa-arrow-right" style="font-size: 1rem; margin-left: 5px;"></i></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header blue-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper"><i class="fas fa-book-open"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Cursos Activos</p>
                    <p class="stat-value">24</p>
                    <p class="stat-trend blue"><i class="fas fa-arrow-trend-up"></i> 8 nuevos cursos</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header orange-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper"><i class="fas fa-award"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Certificaciones Emitidas</p>
                    <p class="stat-value">156</p>
                    <p class="stat-trend orange"><i class="fas fa-arrow-trend-up"></i> +28 este mes</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header purple-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper"><i class="fas fa-file-contract"></i></div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Diplomas Entregados</p>
                    <p class="stat-value">94</p>
                    <p class="stat-trend purple"><i class="fas fa-arrow-trend-up"></i> +5% vs mes anterior</p>
                </div>
            </div>
            
        </div>
    </div> 

</body>
</html> 