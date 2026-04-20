// public/login.js - Versión simplificada SOLO para login

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== ELEMENTOS DEL MODAL DE LOGIN =====
    const modal = document.getElementById('loginModal');
    const showLoginBtn = document.getElementById('showLoginBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const loginForm = document.getElementById('loginForm');
    const alertMessage = document.getElementById('alertMessage');
    const loginBtn = document.getElementById('loginBtn');
    const togglePassword = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('loginPassword');
    const usuarioInput = document.getElementById('usuario');
    const rememberCheck = document.getElementById('rememberMe');
    
    // ===== ABRIR MODAL =====
    if (showLoginBtn && modal) {
        showLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            if (usuarioInput) usuarioInput.focus();
        });
    }
    
    // ===== CERRAR MODAL =====
    if (closeModalBtn && modal) {
        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            if (loginForm) loginForm.reset();
            // Limpiar mensaje de error
            const alertDiv = document.querySelector('.alert-message');
            if (alertDiv) alertDiv.style.display = 'none';
        });
    }
    
    // ===== CERRAR MODAL CLIC FUERA =====
    if (modal) {
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                if (loginForm) loginForm.reset();
            }
        });
    }
    
    // ===== MOSTRAR/OCULTAR CONTRASEÑA =====
if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.classList.remove('fa-eye-slash');
            togglePassword.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            togglePassword.classList.remove('fa-eye');
            togglePassword.classList.add('fa-eye-slash');
        }
    });
}
    
    // ===== FUNCIÓN PARA MOSTRAR ALERTA =====
    function showAlert(message, type) {
        // Buscar o crear elemento de alerta
        let alertDiv = document.querySelector('.alert-message');
        if (!alertDiv) {
            alertDiv = document.createElement('div');
            alertDiv.className = 'alert-message';
            if (loginForm) {
                loginForm.insertBefore(alertDiv, loginForm.firstChild);
            }
        }
        
        alertDiv.innerHTML = `<i class="fas ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i><span>${message}</span>`;
        alertDiv.className = `alert-message alert-${type} show`;
        alertDiv.style.display = 'block';
        
        setTimeout(function() {
            alertDiv.style.display = 'none';
        }, 5000);
    }
    
    // ===== GUARDAR CREDENCIALES =====
    function guardarCredenciales(usuario) {
        if (rememberCheck && rememberCheck.checked && usuario) {
            localStorage.setItem('rememberedUser', usuario);
        } else {
            localStorage.removeItem('rememberedUser');
        }
    }
    
    // ===== CARGAR CREDENCIALES =====
    function cargarCredenciales() {
        const rememberedUser = localStorage.getItem('rememberedUser');
        if (rememberedUser && usuarioInput) {
            usuarioInput.value = rememberedUser;
            if (rememberCheck) rememberCheck.checked = true;
        }
    }
    
    cargarCredenciales();
    
    // ===== ENVÍO DEL FORMULARIO DE LOGIN =====
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const usuario = usuarioInput ? usuarioInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value : '';
            
            // Validar campos
            if (!usuario || !password) {
                showAlert('❌ Complete todos los campos', 'error');
                if (!usuario && usuarioInput) {
                    usuarioInput.style.animation = 'shake 0.3s';
                    setTimeout(() => { if(usuarioInput) usuarioInput.style.animation = ''; }, 300);
                }
                if (!password && passwordInput) {
                    passwordInput.style.animation = 'shake 0.3s';
                    setTimeout(() => { if(passwordInput) passwordInput.style.animation = ''; }, 300);
                }
                return;
            }
            
            // Mostrar loading en el botón
            if (loginBtn) {
                loginBtn.disabled = true;
                loginBtn.classList.add('loading');
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Validando...';
            }
            
            try {
                // Enviar al controlador PHP mediante POST normal (no fetch)
                // Creamos un formulario temporal
                const formData = new FormData();
                formData.append('usuario', usuario);
                formData.append('password', password);
                
                const response = await fetch('index.php?accion=procesar_login', {
                    method: 'POST',
                    body: formData
                });
                    
                const text = await response.text();
                
                // Verificar si hubo redirección (éxito)
                if (response.redirected || text.includes('Location:') || response.url.includes('accion=inicio')) {
                    guardarCredenciales(usuario);
                    showAlert('✅ Inicio de sesión exitoso', 'success');
                    setTimeout(function() {
                        window.location.href = 'index.php?accion=inicio';
                    }, 1000);
                } else {
                    // Si hay mensaje de error en sesión
                    showAlert('❌ Usuario o contraseña incorrectos', 'error');
                    if (loginBtn) {
                        loginBtn.disabled = false;
                        loginBtn.classList.remove('loading');
                        loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Ingresar';
                    }
                    if (passwordInput) {
                        passwordInput.value = '';
                        passwordInput.focus();
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('❌ Error al conectar con el servidor', 'error');
                if (loginBtn) {
                    loginBtn.disabled = false;
                    loginBtn.classList.remove('loading');
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Ingresar';
                }
            }
        });
    }
    
    // ===== ENTER PARA ENVIAR =====
    if (passwordInput) {
        passwordInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && loginForm) {
                loginForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    if (usuarioInput) {
        usuarioInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && loginForm) {
                loginForm.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    // ===== BOTÓN DE REGISTRO DE USUARIO =====
    const registerUserBtn = document.getElementById('registerUserBtn');
    if (registerUserBtn) {
        registerUserBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'index.php?accion=registroUsuario';
        });
    }
    
    // ===== ANIMACIÓN DE SHAKE =====
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .alert-message {
            display: none;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: fadeIn 0.3s ease;
        }
        .alert-message.alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-message.alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-message.show {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-login.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
    `;
    document.head.appendChild(style);
});