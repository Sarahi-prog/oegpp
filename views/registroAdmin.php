<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <link rel="stylesheet" href="public/login.css">
    <style>
        .registro-container {
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
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.3);
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
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
        .link-login {
            text-align: center;
            margin-top: 15px;
        }
        .link-login a {
            color: #007bff;
            text-decoration: none;
        }
        .link-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="registro-container">
        <h2>Registrar Nuevo Administrador</h2>
        
        <div id="mensaje" class="mensaje"></div>

        <form id="formRegistro">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmacion">Confirmar Contraseña:</label>
                <input type="password" id="password_confirmacion" name="password_confirmacion" required>
            </div>

            <button type="submit">Registrarse</button>
        </form>

        <div class="link-login">
            <p>¿Ya tienes cuenta? <a href="index.php?accion=login">Inicia sesión aquí</a></p>
            <p>¿Necesitas verificar tu token? <a href="index.php?accion=verificar">Verifica aquí</a></p>
        </div>
    </div>

    <script>
        document.getElementById('formRegistro').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(document.getElementById('formRegistro'));
            const response = await fetch('index.php?accion=procesarRegistro', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            const msgDiv = document.getElementById('mensaje');

            if (data.success) {
                msgDiv.className = 'mensaje exito';
                msgDiv.innerHTML = `
                    <strong>✓ Registro Exitoso</strong><br>
                    ${data.mensaje}<br><br>
                    <strong>Tu Token:</strong> <code style="background: #fff; padding: 5px; border: 1px solid #ccc; border-radius: 3px;">${data.token}</code><br>
                    <small>Comparte este token con el administrador general para verificar tu cuenta</small>
                `;
                document.getElementById('formRegistro').reset();
            } else {
                msgDiv.className = 'mensaje error';
                msgDiv.textContent = '✗ ' + data.message;
            }
        });
    </script>
</body>
</html>
