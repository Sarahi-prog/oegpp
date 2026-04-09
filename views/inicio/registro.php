<?php
// Verificar si ya está logueado
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php?accion=inicio");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios - OEGPP</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/login.css">
    <style>
        body {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
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
            box-shadow: 0 2px 10px rgba(39, 174, 96, 0.1);
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

        .registro-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15),
                        0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 50px;
            max-width: 500px;
            margin: 100px auto 40px;
            animation: slideIn 0.5s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .registro-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #27ae60, #229954, #27ae60);
            border-radius: 20px 20px 0 0;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .registro-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .registro-header h1 {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .registro-header p {
            color: #6c757d;
            font-size: 16px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        label i {
            color: #27ae60;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #2c3e50;
        }

        input::placeholder {
            color: #adb5bd;
            font-style: italic;
        }

        input:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1);
            background: white;
            transform: translateY(-2px);
        }

        input[type="email"] {
            letter-spacing: 0.5px;
        }

        .password-strength {
            margin-top: 10px;
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.4s ease;
            border-radius: 3px;
        }

        .strength-bar.weak {
            width: 33%;
            background: linear-gradient(90deg, #ff6b6b, #ee5a52);
        }

        .strength-bar.medium {
            width: 66%;
            background: linear-gradient(90deg, #feca57, #ffb142);
        }

        .strength-bar.strong {
            width: 100%;
            background: linear-gradient(90deg, #48dbfb, #0abde3);
        }

        .form-group small {
            display: block;
            margin-top: 8px;
            color: #6c757d;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group small i {
            color: #27ae60;
            font-size: 12px;
        }

        .btn-registro {
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
        }

        .btn-registro::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-registro:hover::before {
            left: 100%;
        }

        .btn-registro:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(39, 174, 96, 0.4);
        }

        .btn-registro:disabled {
            background: #adb5bd;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .mensaje {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: none;
            animation: slideIn 0.3s ease-out;
            border: 1px solid transparent;
        }

        .mensaje.success {
            display: block;
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-color: #c3e6cb;
        }

        .mensaje.error {
            display: block;
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-color: #f5c6cb;
        }

        .toggle-password {
            position: relative;
        }

        .toggle-password input {
            padding-right: 50px;
        }

        .toggle-password-btn {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            font-size: 16px;
            transition: all 0.3s ease;
            padding: 5px;
            border-radius: 50%;
        }

        .toggle-password-btn:hover {
            color: #27ae60;
            background: rgba(39, 174, 96, 0.1);
        }

        .link-login {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
        }

        .link-login a {
            color: #27ae60;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .link-login a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #27ae60;
            transition: width 0.3s ease;
        }

        .link-login a:hover::after {
            width: 100%;
        }

        .link-login a:hover {
            color: #229954;
        }

        .loading {
            display: none;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #e9ecef;
            border-top-color: #27ae60;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .registro-container {
                margin: 80px 20px 40px;
                padding: 30px 25px;
            }

            .registro-header h1 {
                font-size: 28px;
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
                <a href="index.php?accion=login">Login</a>
            </nav>
        </header>

        <div class="registro-container">
            <div class="registro-header">
                <h1>🚀 Únete a OEGPP</h1>
                <p>Solicita tu acceso al sistema de gestión más avanzado</p>
            </div>

            <div id="mensaje" class="mensaje"></div>

            <form id="formRegistro" novalidate>
                <div class="form-group">
                    <label for="usuario">
                        <i class="fas fa-user-circle"></i> Nombre de Usuario
                    </label>
                    <input type="text" id="usuario" name="usuario" required placeholder="Ej: juan_perez">
                    <small>Mínimo 4 caracteres, sin espacios</small>
                </div>

                <div class="form-group">
                    <label for="correo">
                        <i class="fas fa-envelope-open"></i> Correo Electrónico
                    </label>
                    <input type="email" id="correo" name="correo" required placeholder="tu@email.com">
                </div>

                <div style="text-align: center; margin: 20px 0; position: relative;">
                    <div style="height: 1px; background: linear-gradient(90deg, transparent, #27ae60, transparent); margin: 10px 0;"></div>
                    <span style="background: rgba(255, 255, 255, 0.9); padding: 0 15px; color: #27ae60; font-weight: 600; font-size: 14px; position: relative; top: -8px;">
                        <i class="fas fa-lock"></i> CONTRASEÑA
                    </span>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-shield-alt"></i> Contraseña Segura
                    </label>
                    <div class="toggle-password">
                        <input type="password" id="password" name="password" required placeholder="Crea una contraseña fuerte">
                        <button type="button" class="toggle-password-btn" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <small><i class="fas fa-info-circle"></i> Mínimo 8 caracteres, combina letras, números y símbolos</small>
                </div>

                <div class="form-group">
                    <label for="confirmar_password">
                        <i class="fas fa-shield-alt"></i> Confirmar Contraseña
                    </label>
                    <input type="password" id="confirmar_password" name="confirmar_password" required placeholder="Repite tu contraseña">
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" id="terminos" required style="width: 18px; height: 18px; accent-color: #27ae60;">
                        <span style="font-weight: 400; font-size: 14px;">
                            <i class="fas fa-handshake"></i> Acepto los <a href="#" style="color: #27ae60; text-decoration: none; font-weight: 600;">términos y condiciones</a> de uso
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn-registro">
                    <span id="btnText">
                        <i class="fas fa-rocket"></i> Solicitar Acceso
                    </span>
                    <span class="loading" id="loading">
                        <span class="spinner"></span> Procesando solicitud...
                    </span>
                </button>
            </form>

            <div class="link-login">
                <i class="fas fa-sign-in-alt"></i> ¿Ya tienes cuenta? 
                <a href="index.php?accion=login">
                    <i class="fas fa-arrow-right"></i> Inicia sesión aquí
                </a>
            </div>
        </div>
    </div>

    <script>
        // Mostrar/ocultar contraseña
        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.querySelector('.toggle-password-btn i');
            
            if (input.type === 'password') {
                input.type = 'text';
                btn.classList.remove('fa-eye');
                btn.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                btn.classList.remove('fa-eye-slash');
                btn.classList.add('fa-eye');
            }
        }

        // Validar fortaleza de contraseña
        document.getElementById('password').addEventListener('input', function() {
            const strength = this.value.length;
            const bar = document.getElementById('strengthBar');
            
            if (strength < 8) {
                bar.className = 'strength-bar';
            } else if (strength < 10 || !/[0-9]/.test(this.value) || !/[a-z]/.test(this.value.toLowerCase())) {
                bar.className = 'strength-bar weak';
            } else if (strength < 15 || !/[!@#$%^&*]/.test(this.value)) {
                bar.className = 'strength-bar medium';
            } else {
                bar.className = 'strength-bar strong';
            }
        });

        // Enviar formulario
        document.getElementById('formRegistro').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.querySelector('.btn-registro');
            const btnText = document.getElementById('btnText');
            const loading = document.getElementById('loading');
            const mensaje = document.getElementById('mensaje');

            btn.disabled = true;
            btnText.style.display = 'none';
            loading.style.display = 'inline';

            const datos = {
                usuario: document.getElementById('usuario').value.trim().toLowerCase(),
                correo: document.getElementById('correo').value.trim().toLowerCase(),
                password: document.getElementById('password').value,
                confirmar_password: document.getElementById('confirmar_password').value
            };

            // Validar que las contraseñas coincidan
            if (datos.password !== datos.confirmar_password) {
                mensaje.className = 'mensaje error';
                mensaje.innerHTML = '<i class="fas fa-exclamation-circle"></i> Las contraseñas no coinciden';
                btn.disabled = false;
                btnText.style.display = 'inline';
                loading.style.display = 'none';
                return;
            }

            try {
                const response = await fetch('index.php?accion=registrarUsuario', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datos)
                });

                const result = await response.json();

                if (result.success) {
                    mensaje.className = 'mensaje success';
                    mensaje.innerHTML = '<i class="fas fa-check-circle"></i> ' + result.message;
                    document.getElementById('formRegistro').reset();
                    document.getElementById('strengthBar').className = 'strength-bar';
                    
                    // Redirigir después de 3 segundos
                    setTimeout(() => {
                        window.location.href = 'index.php?accion=login';
                    }, 3000);
                } else {
                    mensaje.className = 'mensaje error';
                    const errores = Array.isArray(result.message) ? result.message.join('<br>') : result.message;
                    mensaje.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errores;
                }
            } catch (error) {
                mensaje.className = 'mensaje error';
                mensaje.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error de conexión: ' + error.message;
            }

            btn.disabled = false;
            btnText.style.display = 'inline';
            loading.style.display = 'none';
        });

        // Validar usuario en tiempo real
        document.getElementById('usuario').addEventListener('change', async function() {
            const usuario = this.value.trim().toLowerCase();
            if (usuario.length < 4) return;

            try {
                const response = await fetch('index.php?accion=verificarUsuario&usuario=' + encodeURIComponent(usuario));
                const result = await response.json();
                
                if (!result.disponible) {
                    this.style.borderColor = '#e74c3c';
                    const msg = document.getElementById('mensaje');
                    msg.className = 'mensaje error';
                    msg.innerHTML = 'Usuario no disponible';
                } else {
                    this.style.borderColor = '#ecf0f1';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>
</html>
