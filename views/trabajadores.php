<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../public/script.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-brand">
                    <div class="logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="brand-text">
                        <h1>OEGPP</h1>
                        <p>BRINDACION DE CURSOS </p>
                    </div>
                </div>

                <div class="navbar-menu">
                    <a href="#" class="menu-item active">
                        <i class="fas fa-users"></i>
                        Trabajadores
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-award"></i>
                        Certificaciones
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-chart-bar"></i>
                        cursos
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-building"></i>
                        
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-building"></i>
                        sertificados
                    </a>
                    <a href="#" class="menu-item">
                        <i class="fas fa-building"></i>
                        diplomas
                    </a>
                </div>



                <div class="navbar-user">
                    <div class="user-info">
                        <p class="user-name">Director RR.HH.</p>
                        <p class="user-email">director@academia.com</p>
                    </div>
                    <div class="user-avatar">DR</div>
                    <button class="mobile-menu-btn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="hero-overlay"></div>
        <div class="hero-bg"></div>
        <div class="container hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Organización de Especialistas en Gestión Publica y Privada</h1>
                <p class="hero-subtitle">Realiza cursos con nostros</p>
                <div class="hero-buttons">
                    <button class="btn btn-primary-white" onclick="openModal()">
                        <i class="fas fa-plus"></i>
                        Registrar nuevo usuario
                    </button>
                    <button class="btn btn-outline-white">
                        <i class="fas fa-download"></i>
                        Exportar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container main-content">
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header green-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Total de usuarios</p>
                    <p class="stat-value" id="totalWorkers">7</p>
                    <p class="stat-trend green">
                        <i class="fas fa-arrow-trend-up"></i>
                        +12% este mes
                    </p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header blue-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-book-open"></i>
                    </div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Cursos</p>
                    <p class="stat-value">24</p>
                    <p class="stat-trend blue">
                        <i class="fas fa-arrow-trend-up"></i>
                        8 nuevos cursos
                    </p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header orange-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-award"></i>
                    </div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">Certificaciones</p>
                    <p class="stat-value">156</p>
                    <p class="stat-trend orange">
                        <i class="fas fa-arrow-trend-up"></i>
                        +28 este mes
                    </p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header purple-gradient">
                    <div class="stat-bg"></div>
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-arrow-trend-up"></i>
                    </div>
                </div>
                <div class="stat-body">
                    <p class="stat-label">diplomas</p>
                    <p class="stat-value">94%</p>
                    <p class="stat-trend purple">
                        <i class="fas fa-arrow-trend-up"></i>
                        +5% vs anterior
                    </p>
                </div>
            </div>
        </div>

        <!-- Directory Header -->
        <div class="section-header">
            <div class="section-title">
                <h2>Directorio de Usuarios</h2>
                <p>Listado completo de usuarios registrados completados</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Buscar por nombre, apellido, DNI o correo..."
                    class="search-input"
                >
            </div>
            <button class="btn btn-outline">
                <i class="fas fa-filter"></i>
                Filtros Avanzados
            </button>
            <button class="btn btn-outline">
                <i class="fas fa-download"></i>
                Exportar PDF
            </button>
        </div>

        <!-- Table -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre y Apellidos</th>
                            <th>DNI</th>
                            <th>N de Celular</th>
                            <th>Correo Electrónico</th>
                            <th>area</th>
                            <th>fecha</th>
                            <th>Horas</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="workersTableBody">
                        <!-- Rows will be inserted by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="table-footer">
                <p class="footer-text">
                    Mostrando <span class="highlight-green" id="displayCount">7</span> de 
                    <span class="highlight-dark" id="totalCount">7</span> usuarios   registrados
                </p>
                <div class="pagination">
                    <button class="btn btn-sm" disabled>Anterior</button>
                    <button class="btn btn-sm" disabled>Siguiente</button>
                </div>
            </div>
        </div>

        <!-- Empty State (hidden by default) -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="alert">
                <p id="emptyMessage">No hay usuarios registrados.</p>
            </div>
        </div>
    </div>
    <div>
        <?php
           require_once('../controllers/TrabajadoresController.php'); 
              $controller = new TrabajadoresController();
                $trabajadores = $controller->Cargar();
                
            ?>
    </div>

</body>
</html>