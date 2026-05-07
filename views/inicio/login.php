<?php
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php?accion=inicio'); // ← así
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OEGPP - Organización de Especialistas en Gestión Pública y Privada</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/login.css">
    <style>
    body {
        background: 
            linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)),
            url("https://5092991.fs1.hubspotusercontent-na1.net/hubfs/5092991/Blog%20notas%20maestrias%20y%20diplomados/Gesti%C3%B3n%20empresarial%20efectiva%206.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .header {
        background: linear-gradient(135deg, rgba(39, 174, 96, 0.95) 0%, rgba(255, 255, 255, 0.95) 100%);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(39, 174, 96, 0.2);
        padding: 15px 0;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(248, 250, 249, 0.86);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 50px;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo-img {
        height: 50px;
        width: auto;
        filter: drop-shadow(0 3px 8px rgba(39, 174, 96, 0.5));
        transition: all 0.4s ease;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.9);
        padding: 5px;
        border: 2px solid rgba(39, 174, 96, 0.2);
    }

    .logo-img:hover {
        transform: scale(1.1) rotate(3deg);
        filter: drop-shadow(0 6px 16px rgba(39, 174, 96, 0.7));
        border-color: rgba(39, 174, 96, 0.5);
    }

    .logo-text {
        font-size: 28px;
        font-weight: 800;
        background: linear-gradient(135deg, #2c3e50 0%, #27ae60 50%, #2c3e50 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        letter-spacing: -0.5px;
        margin-left: 5px;
    }

    .logo-text span {
        color: #27ae60;
        text-shadow: 0 0 15px rgba(39, 174, 96, 0.4);
        filter: drop-shadow(0 0 8px rgba(39, 174, 96, 0.3));
    }

    .nav {
        display: flex;
        gap: 30px;
    }

    .nav a {
        color: #2c3e50;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        padding: 10px 18px;
        border-radius: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(39, 174, 96, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .nav a:hover::before {
        left: 100%;
    }

    .nav a:hover {
        background: rgba(39, 174, 96, 0.15);
        color: #27ae60;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.2);
    }

    .hero {
        padding: 120px 20px 80px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 50px;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .hero-title span {
        background: linear-gradient(135deg, #fff, #f0f8ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-description {
        font-size: 1.3rem;
        margin-bottom: 40px;
        opacity: 0.9;
        line-height: 1.6;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-image {
        position: relative;
        z-index: 2;
    }

    .image-placeholder {
        width: 350px;
        height: 250px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .image-placeholder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-hero {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        color: #27ae60;
        border: 2px solid white;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    }

    .features {
        padding: 80px 20px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
        font-size: 3rem;
        color: #27ae60;
        margin-bottom: 20px;
        background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(34, 153, 84, 0.1));
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .feature-card h3 {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .feature-card p {
        color: #6c757d;
        line-height: 1.6;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        margin: 5% auto;
        padding: 0;
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 20px 20px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .close-modal {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .close-modal:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 40px 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper i:first-child {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #27ae60;
        font-size: 16px;
    }

    .input-wrapper input {
        width: 100%;
        padding: 15px 15px 15px 45px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        color: #2c3e50;
    }

    .input-wrapper input:focus {
        outline: none;
        border-color: #27ae60;
        box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1);
        background: white;
        transform: translateY(-2px);
    }

    .input-wrapper input::placeholder {
        color: #adb5bd;
    }

    /* ===== BOTÓN MOSTRAR CONTRASEÑA (AGREGADO) ===== */
    .toggle-password-btn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        font-size: 18px;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .toggle-password-btn:hover {
        color: #27ae60;
    }

    /* ===== ELIMINAR CANDADO DEL NAVEGADOR (AGREGADO) ===== */
    input[type="password"]::-webkit-credentials-auto-fill-button,
    input[type="password"]::-webkit-credentials-auto-fill-button:focus,
    input[type="password"]::-webkit-credentials-auto-fill-button:hover,
    input::-webkit-credentials-auto-fill-button,
    input::-webkit-credentials-auto-fill-button:focus,
    input::-webkit-credentials-auto-fill-button:hover {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
        pointer-events: none !important;
    }

    input::-ms-clear,
    input::-ms-reveal {
        display: none !important;
    }

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .remember-check {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 14px;
        color: #6c757d;
    }

    .remember-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #27ae60;
    }

    .forgot-link {
        color: #27ae60;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .forgot-link:hover {
        color: #229954;
        text-decoration: underline;
    }

    .btn-login {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(39, 174, 96, 0.4);
    }

    .register-link {
        text-align: center;
        margin-top: 20px;
        color: #6c757d;
        font-size: 14px;
    }

    .register-link a {
        color: #27ae60;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .register-link a:hover {
        color: #229954;
        text-decoration: underline;
    }

    .alert-message {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: none;
        animation: slideIn 0.3s ease-out;
        border: 1px solid transparent;
    }

    .alert-message.success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-color: #c3e6cb;
    }

    .alert-message.error {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-color: #f5c6cb;
    }

    .footer {
        background: rgba(0, 0, 0, 0.8);
        color: white;
        text-align: center;
        padding: 30px 20px;
        margin-top: auto;
    }

    .footer p {
        margin: 0;
        font-size: 14px;
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        .hero {
            padding: 100px 20px 60px;
            flex-direction: column;
            text-align: center;
        }

        .hero-content {
            margin-bottom: 40px;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-description {
            font-size: 1.1rem;
        }

        .hero-image {
            position: static;
            transform: none;
            margin-top: 20px;
        }

        .image-placeholder {
            width: 250px;
            height: 150px;
            margin: 0 auto;
        }

        .nav {
            display: none;
        }

        .header {
            padding: 15px 20px;
        }

        .modal-content {
            width: 95%;
            margin: 10% auto;
        }

        .features {
            padding: 60px 20px;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .feature-card {
            padding: 30px 20px;
        }

        .footer div {
            flex-direction: column;
            gap: 10px;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 2rem;
        }

        .modal-content {
            width: 98%;
            margin: 5% auto;
        }

        .modal-body {
            padding: 30px 20px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="public/images/logo.jpg" alt="OEGPP Logo" class="logo-img" onerror="this.style.display='none'">
                <div class="logo-text">OEGPP<span>.</span></div>
            </div>
            <nav class="nav">
                <a href="index.php">Inicio</a>
                <a href="index.php?accion=clientes">Sistema</a>
                <a href="#features">Servicios</a>
                <a href="index.php?accion=registroUsuario">Registro</a>
            </nav>
        </header>

        <section class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Sistema de Gestión<br><span>OEGPP</span></h1>
                <p class="hero-description">
                    Gestiona clientes, cursos de capacitación y certificaciones profesionales.
                    Accede a tu panel administrativo para administrar usuarios, asignar capacitaciones
                    y generar reportes detallados de tu organización.
                </p>
                <button class="btn-hero" id="showLoginBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    Acceder al Sistema
                </button>
            </div>
            <div class="hero-image">
                <div class="image-placeholder">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=500&h=350&fit=crop" alt="Equipo profesional">
                </div>
            </div>
        </section>

        <section class="features" id="features">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Gestión de Clientes</h3>
                <p>Administra el registro completo de clientes, asigna cursos de capacitación,
                registra asistencias y genera reportes detallados del personal.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Sistema de Capacitación</h3>
                <p>Gestiona cursos, módulos y certificaciones. Asigna capacitaciones a clientes,
                registra progreso y genera certificados profesionales.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Reportes y Estadísticas</h3>
                <p>Visualiza estadísticas completas, genera reportes de asistencia, progreso
                de capacitaciones y métricas de rendimiento organizacional.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Control de Acceso Seguro</h3>
                <p>Sistema de autorización de usuarios con roles administrativos,
                control de permisos y registro de actividades para mayor seguridad.</p>
            </div>
        </section>

        <!-- Modal de Login (CON BOTÓN DE VER CONTRASEÑA FUNCIONAL) -->
        <div id="loginModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Iniciar Sesión</h2>
                    <button class="close-modal" id="closeModalBtn">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="alertMessage" class="alert-message"></div>
                <form id="loginForm" action="index.php?accion=procesar_login" method="POST">
                        <div class="form-group">
                            <label>Usuario o Correo</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" id="usuario" name="usuario" placeholder="usuario@ejemplo.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="loginPassword" name="password" placeholder="Ingresa tu contraseña" required>
                                <i class="fas fa-eye-slash toggle-password-btn" id="togglePasswordBtn"></i>
                            </div>
                        </div>
                        <div class="form-options">
                        </div>
                        <div class="form-options">
                            <label class="remember-check">
                                <input type="checkbox" id="rememberMe">
                                <span>Recordarme</span>
                            </label>
                        </div>
                        <button type="submit" class="btn-login" id="loginBtn">
                            <i class="fas fa-sign-in-alt"></i> Ingresar
                        </button>
                        <div class="register-link" style="margin-top: 15px; text-align: center;">
                            <span style="color: #6c757d; font-size: 14px;">¿No tienes cuenta?</span>
                            <button type="button" id="registerUserBtn" class="btn-login" style="margin-top: 10px; width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                                <i class="fas fa-user-plus"></i> Solicitar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div style="display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-copyright" style="color: #27ae60;"></i>
                    <span>2026 OEGPP - Sistema de Gestión Profesional</span>
                </div>
            </div>
        </footer>
    </div>

    <script src="public/login.js"></script>
<script>
    function toggleRegisterPassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>
</body>
</html>