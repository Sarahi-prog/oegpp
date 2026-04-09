<?php
/**
 * VERIFICADOR DE INSTALACIÓN - Sistema de Autorización de Usuarios
 * 
 * Ejecuta este archivo en: http://localhost/oegpp-disenoWEB2/verificador.php
 * Verifica que todos los archivos y funcionalidades estén correctos
 */

require_once './config/DB.php';

$errores = [];
$advertencias = [];
$exitos = [];

// 1. Verificar archivos necesarios
$archivos_necesarios = [
    'models/SolicitudesRegistroModel.php',
    'controllers/SolicitudesRegistroController.php',
    'views/inicio/registro.php',
    'views/autorizacionUsuarios.php',
    'BD_autorización_usuarios.sql',
    'GUIA_AUTORIZACION_USUARIOS.md'
];

foreach ($archivos_necesarios as $archivo) {
    if (file_exists($archivo)) {
        $exitos[] = "✅ Archivo encontrado: <code>$archivo</code>";
    } else {
        $errores[] = "❌ Archivo NO encontrado: <code>$archivo</code>";
    }
}

// 2. Verificar tabla de solicitudes_registro
try {
    $conexion = DB::conectar();
    
    $sql = "SELECT EXISTS (
        SELECT 1 FROM information_schema.tables 
        WHERE table_name = 'solicitudes_registro'
    )";
    $stmt = $conexion->query($sql);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado['exists'] === 't') {
        $exitos[] = "✅ Tabla <code>solicitudes_registro</code> existe";
        
        // Verificar campos de la tabla
        $campos_esperados = [
            'id_solicitud', 'usuario', 'correo', 'password_hash', 
            'nombres', 'apellidos', 'dni', 'celular', 'area', 
            'estado', 'fecha_solicitud', 'fecha_autorizacion', 
            'motivo_rechazo', 'autorizado_por'
        ];
        
        $sql_campos = "SELECT column_name FROM information_schema.columns 
                      WHERE table_name = 'solicitudes_registro'";
        $stmt_campos = $conexion->query($sql_campos);
        $campos_reales = $stmt_campos->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($campos_esperados as $campo) {
            if (in_array($campo, $campos_reales)) {
                $exitos[] = "✅ Campo <code>$campo</code> existe";
            } else {
                $errores[] = "❌ Campo <code>$campo</code> NO existe";
            }
        }
    } else {
        $errores[] = "❌ Tabla <code>solicitudes_registro</code> NO existe";
        $errores[] = "⚠️ Ejecuta el SQL en: <code>BD_autorización_usuarios.sql</code>";
    }
    
} catch (Exception $e) {
    $errores[] = "❌ Error conectando BD: " . $e->getMessage();
}

// 3. Verificar columnas en tabla administradores
try {
    $conexion = DB::conectar();
    
    $sql = "SELECT column_name FROM information_schema.columns 
            WHERE table_name = 'administradores'";
    $stmt = $conexion->query($sql);
    $campos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('token_verificacion', $campos)) {
        $exitos[] = "✅ Columna <code>token_verificacion</code> en administradores";
    } else {
        $advertencias[] = "⚠️ Columna <code>token_verificacion</code> falta (considera agregar)";
    }
    
    if (in_array('token_expiracion', $campos)) {
        $exitos[] = "✅ Columna <code>token_expiracion</code> en administradores";
    } else {
        $advertencias[] = "⚠️ Columna <code>token_expiracion</code> falta (considera agregar)";
    }
    
} catch (Exception $e) {
    $errores[] = "❌ Error verificando administradores: " . $e->getMessage();
}

// 4. Verificar que las clases existan
$clases_necesarias = [
    'SolicitudesRegistroModel' => 'models/SolicitudesRegistroModel.php',
    'SolicitudesRegistroController' => 'controllers/SolicitudesRegistroController.php'
];

foreach ($clases_necesarias as $clase => $archivo) {
    if (file_exists($archivo)) {
        require_once $archivo;
        if (class_exists($clase)) {
            $exitos[] = "✅ Clase <code>$clase</code> existe";
        } else {
            $errores[] = "❌ Clase <code>$clase</code> NO está definida en $archivo";
        }
    }
}

// 5. Verificar rutas en index.php
if (file_exists('index.php')) {
    $contenido = file_get_contents('index.php');
    
    if (strpos($contenido, 'SolicitudesRegistroController') !== false) {
        $exitos[] = "✅ <code>SolicitudesRegistroController</code> importado en index.php";
    } else {
        $errores[] = "❌ <code>SolicitudesRegistroController</code> NO importado en index.php";
    }
    
    if (strpos($contenido, 'registroUsuario') !== false) {
        $exitos[] = "✅ Ruta <code>registroUsuario</code> definida en index.php";
    } else {
        $errores[] = "❌ Ruta <code>registroUsuario</code> NO definida";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificador - Sistema de Autorización</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .section {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .section h2 {
            color: #2c3e50;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .item {
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
            font-size: 14px;
            line-height: 1.6;
        }
        .item:last-child {
            border-bottom: none;
        }
        code {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        .estado-ok {
            color: #27ae60;
            font-weight: 600;
        }
        .estado-error {
            color: #e74c3c;
            font-weight: 600;
        }
        .estado-warning {
            color: #f39c12;
            font-weight: 600;
        }
        .resumen {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        .card-resumen {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .card-resumen.exitos { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); }
        .card-resumen.errores { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
        .card-resumen.advertencias { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }
        .numero {
            font-size: 32px;
            font-weight: 700;
        }
        .label-resumen {
            font-size: 12px;
            margin-top: 8px;
            opacity: 0.9;
        }
        .acciones {
            margin-top: 30px;
            padding: 20px;
            background: #d6eaf8;
            border-left: 4px solid #3498db;
            border-radius: 5px;
        }
        .acciones h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .acciones ul {
            margin-left: 20px;
            color: #34495e;
        }
        .acciones li {
            margin: 8px 0;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <i class="fas fa-check-circle" style="font-size: 28px;"></i>
            Verificador de Instalación
        </h1>
        <p class="subtitle">Sistema de Autorización de Usuarios</p>

        <!-- Resumen -->
        <div class="resumen">
            <div class="card-resumen exitos">
                <div class="numero"><?php echo count($exitos); ?></div>
                <div class="label-resumen">Verificados OK</div>
            </div>
            <div class="card-resumen errores" <?php echo count($errores) == 0 ? 'style="opacity:0.3;"' : ''; ?>>
                <div class="numero"><?php echo count($errores); ?></div>
                <div class="label-resumen">Errores Críticos</div>
            </div>
            <div class="card-resumen advertencias" <?php echo count($advertencias) == 0 ? 'style="opacity:0.3;"' : ''; ?>>
                <div class="numero"><?php echo count($advertencias); ?></div>
                <div class="label-resumen">Advertencias</div>
            </div>
        </div>

        <!-- Errores (si existen) -->
        <?php if (count($errores) > 0): ?>
            <div class="section" style="border-left-color: #e74c3c; background: #fadbd8;">
                <h2 style="color: #c0392b;">❌ ERRORES CRÍTICOS</h2>
                <?php foreach ($errores as $error): ?>
                    <div class="item estado-error"><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Advertencias -->
        <?php if (count($advertencias) > 0): ?>
            <div class="section" style="border-left-color: #f39c12; background: #fef5e7;">
                <h2 style="color: #d68910;">⚠️ ADVERTENCIAS</h2>
                <?php foreach ($advertencias as $adv): ?>
                    <div class="item" style="color: #7d6608;"><?php echo $adv; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Éxitos -->
        <div class="section" style="border-left-color: #27ae60; background: #d5f4e6;">
            <h2 style="color: #27ae60;">✅ VERIFICACIONES OK</h2>
            <?php foreach ($exitos as $exito): ?>
                <div class="item estado-ok"><?php echo $exito; ?></div>
            <?php endforeach; ?>
        </div>

        <!-- Acciones -->
        <div class="acciones">
            <h3>📋 Próximos Pasos:</h3>
            <ul>
                <?php if (count($errores) > 0): ?>
                    <li><strong>1. Soluciona los errores críticos listados arriba</strong></li>
                    <li>2. Vuelve a ejecutar este verificador</li>
                <?php else: ?>
                    <li><strong>✅ Sistema listo para usar</strong></li>
                    <li>3. Accede a: <code>index.php?accion=registroUsuario</code> para probar registro</li>
                    <li>4. Inicia sesión como admin general</li>
                    <li>5. Accede a: <code>index.php?accion=autorizacionUsuarios</code> para autorizar</li>
                    <li>6. Revisa la guía: <strong><a href="GUIA_AUTORIZACION_USUARIOS.md">GUIA_AUTORIZACION_USUARIOS.md</a></strong></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Ayuda rápida -->
        <div style="margin-top: 30px; padding: 15px; background: #e8daef; border-radius: 5px; border-left: 4px solid #8e44ad;">
            <strong style="color: #6c3483;">💡 Ayuda Rápida:</strong>
            <p style="margin-top: 10px; color: #5b2c6f; font-size: 13px;">
                Si tienes problemas, consulta:
                <a href="GUIA_AUTORIZACION_USUARIOS.md">📖 Guía de Usuario</a> | 
                <a href="BD_autorización_usuarios.sql">📊 Script SQL</a> |
                Revisa los logs en tu navegador (F12 → Console)
            </p>
        </div>
    </div>
</body>
</html>
