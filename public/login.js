document.addEventListener('DOMContentLoaded', function() {
    // Crear partículas animadas
    function createParticles() {
        const particlesContainer = document.createElement('div');
        particlesContainer.className = 'particles';
        document.body.appendChild(particlesContainer);
        
        const particleCount = 50;
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            const size = Math.random() * 6 + 2;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = Math.random() * 15 + 8 + 's';
            particle.style.animationDelay = Math.random() * 5 + 's';
            particlesContainer.appendChild(particle);
        }
    }
    createParticles();
    
    // Elementos del modal de login
    const modal = document.getElementById('loginModal');
    const showLoginBtn = document.getElementById('showLoginBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const loginForm = document.getElementById('loginForm');
    const alertMessage = document.getElementById('alertMessage');
    const loginBtn = document.getElementById('loginBtn');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const usuarioInput = document.getElementById('usuario');
    const rememberCheck = document.getElementById('rememberMe');
    
    // Elementos del modal de registro
    const registerModal = document.getElementById('registerAdminModal');
    const registerAdminBtn = document.getElementById('registerAdminBtn');
    const closeRegisterModalBtn = document.getElementById('closeRegisterModalBtn');
    const registerForm = document.getElementById('registerAdminForm');
    const registerAlertMessage = document.getElementById('registerAlertMessage');
    
    // Abrir modal de login
    if (showLoginBtn) {
        showLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
                if (usuarioInput) usuarioInput.focus();
            }
        });
    }
    
    // Cerrar modal de login
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
                if (loginForm) loginForm.reset();
            }
        });
    }
    
    // Abrir modal de registro
    if (registerAdminBtn) {
        registerAdminBtn.addEventListener('click', function() {
            if (modal) {
                modal.classList.remove('show');
            }
            if (registerModal) {
                registerModal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        });
    }
    
    // Cerrar modal de registro
    if (closeRegisterModalBtn) {
        closeRegisterModalBtn.addEventListener('click', function() {
            if (registerModal) {
                registerModal.classList.remove('show');
                document.body.style.overflow = 'auto';
                if (registerForm) registerForm.reset();
                if (registerAlertMessage) registerAlertMessage.classList.remove('show');
            }
        });
    }
    
    // Cerrar modales al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
                if (loginForm) loginForm.reset();
            }
        }
        if (e.target === registerModal) {
            if (registerModal) {
                registerModal.classList.remove('show');
                document.body.style.overflow = 'auto';
                if (registerForm) registerForm.reset();
                if (registerAlertMessage) registerAlertMessage.classList.remove('show');
            }
        }
    });
    
    // Mostrar/ocultar contraseña en login
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    // Mostrar/ocultar contraseña en registro
    window.toggleRegisterPassword = function(inputId, icon) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    };
    
    // Función para mostrar alerta en login
    function showAlert(message, type) {
        if (!alertMessage) return;
        alertMessage.innerHTML = `<i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i><span>${message}</span>`;
        alertMessage.className = `alert-message alert-${type} show`;
        setTimeout(function() {
            if (alertMessage) {
                alertMessage.classList.remove('show');
            }
        }, 5000);
    }
    
    // Función para mostrar alerta en registro
    function showRegisterAlert(message, type) {
        if (!registerAlertMessage) return;
        registerAlertMessage.innerHTML = `<i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i><span>${message}</span>`;
        registerAlertMessage.className = `alert-message alert-${type} show`;
        setTimeout(function() {
            registerAlertMessage.classList.remove('show');
        }, 5000);
    }
    
    // Guardar credenciales
    function guardarCredenciales(usuario) {
        if (rememberCheck && rememberCheck.checked && usuario) {
            localStorage.setItem('rememberedUser', usuario);
        } else {
            localStorage.removeItem('rememberedUser');
        }
    }
    
    // Cargar credenciales
    function cargarCredenciales() {
        const rememberedUser = localStorage.getItem('rememberedUser');
        if (rememberedUser && usuarioInput) {
            usuarioInput.value = rememberedUser;
            if (rememberCheck) rememberCheck.checked = true;
        }
    }
    
    cargarCredenciales();
    
    // Envío del formulario de login
if (loginForm) {
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const usuario = usuarioInput ? usuarioInput.value.trim() : '';
        const password = passwordInput ? passwordInput.value : '';
        
        if (!usuario || !password) {
            showAlert('Complete todos los campos', 'error');
            if (!usuario) usuarioInput.style.animation = 'shake 0.3s';
            if (!password) passwordInput.style.animation = 'shake 0.3s';
            setTimeout(() => {
                if (usuarioInput) usuarioInput.style.animation = '';
                if (passwordInput) passwordInput.style.animation = '';
            }, 300);
            return;
        }
        
        if (loginBtn) {
            loginBtn.disabled = true;
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Validando...';
        }
        
        try {
            const response = await fetch('controllers/procesarLogin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ usuario, password })
            });
            
            const data = await response.json();
            
            if (data.success) {
                guardarCredenciales(usuario);
                showAlert(data.message, 'success');
                setTimeout(function() {
                    window.location.href = 'index.php?accion=inicio';
                }, 1500);
            } else {
                showAlert(data.message, 'error');
                if (loginBtn) {
                    loginBtn.disabled = false;
                    loginBtn.classList.remove('loading');
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Ingresar';
                }
                if (passwordInput) {
                    passwordInput.value = '';
                    passwordInput.focus();
                    passwordInput.style.animation = 'shake 0.3s';
                    setTimeout(() => {
                        passwordInput.style.animation = '';
                    }, 300);
                }
            }
        } catch (error) {
            showAlert('Error al conectar con el servidor', 'error');
            if (loginBtn) {
                loginBtn.disabled = false;
                loginBtn.classList.remove('loading');
                loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Ingresar';
            }
        }
    });
}
    
    // Registro de Administrador
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Obtener valores del formulario
            const nombre = document.getElementById('reg_nombre').value.trim();
            const email = document.getElementById('reg_email').value.trim();
            const usuario = document.getElementById('reg_usuario').value.trim();
            const password = document.getElementById('reg_password').value;
            const confirmPassword = document.getElementById('reg_confirm_password').value;
            
            // Validaciones
            if (!nombre || !email || !usuario || !password || !confirmPassword) {
                showRegisterAlert('Todos los campos son obligatorios', 'error');
                return;
            }
            
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showRegisterAlert('Ingrese un correo electrónico válido', 'error');
                return;
            }
            
            // Validar longitud de contraseña
            if (password.length < 6) {
                showRegisterAlert('La contraseña debe tener al menos 6 caracteres', 'error');
                return;
            }
            
            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                showRegisterAlert('Las contraseñas no coinciden', 'error');
                return;
            }
            
            // Deshabilitar botón mientras se procesa
            const submitBtn = registerForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Registrando...';
            
            try {
                // ✅ RUTA CORREGIDA
                const response = await fetch('controllers/registrar_admin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        nombre: nombre,
                        email: email,
                        usuario: usuario,
                        password: password
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showRegisterAlert(data.message, 'success');
                    // Limpiar formulario y cerrar modal después de 2 segundos
                    setTimeout(() => {
                        registerForm.reset();
                        if (registerModal) {
                            registerModal.classList.remove('show');
                        }
                        // Abrir modal de login
                        if (modal) {
                            modal.classList.add('show');
                        }
                        document.body.style.overflow = 'hidden';
                        // Limpiar alerta
                        if (registerAlertMessage) {
                            registerAlertMessage.classList.remove('show');
                        }
                    }, 2000);
                } else {
                    showRegisterAlert(data.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                showRegisterAlert('Error al conectar con el servidor', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
    
    // Enter para enviar en login
    if (passwordInput) {
        passwordInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && loginForm) {
                loginForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // Enter para enviar en registro
    const regConfirmPassword = document.getElementById('reg_confirm_password');
    if (regConfirmPassword) {
        regConfirmPassword.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && registerForm) {
                registerForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // Efecto hover en feature cards
    const cards = document.querySelectorAll('.feature-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'all 0.3s';
        });
    });
    
    // Animación de números en estadísticas (opcional)
    const stats = document.querySelectorAll('.stat-number');
    stats.forEach(stat => {
        const target = parseInt(stat.innerText);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                stat.innerText = target;
                clearInterval(timer);
            } else {
                stat.innerText = Math.floor(current);
            }
        }, 30);
    });
});