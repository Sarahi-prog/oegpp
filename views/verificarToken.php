<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Token</title>
    <link rel="stylesheet" href="public/login.css">
    <style>
        .verificar-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40,167,69,0.3);
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        .mensaje {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            display: none;
        }
        .mensaje.exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        .info-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #004085;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .link-login {
            text-align: center;
            margin-top: 15px;
        }
        .link-login a {
            color: #28a745;
            text-decoration: none;
        }
        .link-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="verificar-container">
        <h2>🔐 Verificar Administrador</h2>
        
        <div class="info-box">
            📌 Ingresa tu ID de administrador y el token que recibiste durante el registro para verificar tu cuenta.
        </div>

        <div id="mensaje" class="mensaje"></div>

        <form id="formVerificacion">
            <div class="form-group">
                <label for="id_admin">ID de Administrador:</label>
                <input type="number" id="id_admin" name="id_admin" required placeholder="Ej: 1">
            </div>

            <div class="form-group">
                <label for="token">Token de Verificación:</label>
                <input type="text" id="token" name="token" required placeholder="Código de verificación">
            </div>

            <button type="submit">Verificar Token</button>
        </form>

        <div class="link-login">
            <p><a href="index.php?accion=login">Volver al Login</a></p>
            <p>¿No tienes cuenta? <a href="index.php?accion=registro">Registrate aquí</a></p>
        </div>
    </div>

    <script>
        document.getElementById('formVerificacion').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(document.getElementById('formVerificacion'));
            const response = await fetch('index.php?accion=procesarVerificacion', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            const msgDiv = document.getElementById('mensaje');

            if (data.success) {
                msgDiv.className = 'mensaje exito';
                msgDiv.innerHTML = `
                    <strong>✓ ${data.message}</strong><br>
                    <small>Ahora puedes <a href="index.php?accion=login" style="color: inherit; text-decoration: underline;">iniciar sesión</a></small>
                `;
                setTimeout(() => {
                    window.location.href = 'index.php?accion=login';
                }, 3000);
            } else {
                msgDiv.className = 'mensaje error';
                msgDiv.textContent = '✗ ' + data.message;
            }
        });
    </script>
</body>
</html>
