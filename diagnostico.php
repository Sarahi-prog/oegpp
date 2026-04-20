<?php
// diagnostico.php - Herramienta de diagnóstico del sistema de registro

session_start();
require_once __DIR__ . '/config/DB.php';

$diagnostico = [
    'base_datos' => [],
    'archivos' => [],
    'sesion' => [],
    'errores' => []
];

// ============================================================================
// 1. VERIFICAR BASE DE DATOS
// ============================================================================

try {
    $db = DB::conectar();
    $diagnostico['base_datos']['conexion'] = '✓ Conexión exitosa';
    
    // Verificar tabla solicitudes_registro
    try {
        $sql = "SELECT COUNT(*) as total FROM solicitudes_registro";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $diagnostico['base_datos']['solicitudes_registro'] = '✓ Tabla existe (' . $row['total'] . ' registros)';
    } catch (Exception $e) {
        $diagnostico['errores'][] = '✗ Tabla solicitudes_registro no existe: ' . $e->getMessage();
    }
    
    // Verificar tabla administradores
    try {
        $sql = "SELECT COUNT(*) as total FROM administradores";
        $result = $db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $diagnostico['base_datos']['administradores'] = '✓ Tabla existe (' . $row['total'] . ' registros)';
    } catch (Exception $e) {
        $diagnostico['errores'][] = '✗ Tabla administradores no existe: ' . $e->getMessage();
    }
    
    // Verificar estructura de solicitudes_registro
    try {
        $sql = "DESCRIBE solicitudes_registro";
        $result = $db->query($sql);
        $columnas = $result->fetchAll(PDO::FETCH_ASSOC);
        $columnas_nombres = array_column($columnas, 'Field');
        
        $requeridas = ['id_solicitud', 'usuario', 'correo', 'password_hash', 'rol', 'estado', 'fecha_solicitud'];
        $faltantes = array_diff($requeridas, $columnas_nombres);
        
        if (empty($faltantes)) {
            $diagnostico['base_datos']['estructura'] = '✓ Estructura correcta';
        } else {
            $diagnostico['errores'][] = '✗ Columnas faltantes: ' . implode(', ', $faltantes);
        }
    } catch (Exception $e) {
        $diagnostico['errores'][] = '✗ Error al verificar estructura: ' . $e->getMessage();
    }
    
} catch (Exception $e) {
    $diagnostico['errores'][] = '✗ Error de conexión a BD: ' . $e->getMessage();
}

// ============================================================================
// 2. VERIFICAR ARCHIVOS
// ============================================================================

$archivos_requeridos = [
    'controllers/SolicitudesRegistroController.php',
    'controllers/AdministradoresController.php',
    'models/SolicitudesRegistroModel.php',
    'models/AdministradoresModel.php',
    'views/inicio/registro.php',
    'views/autorizacionUsuarios.php',
    'public/login.js',
    'index.php'
];

foreach ($archivos_requeridos as $archivo) {
    $ruta = __DIR__ . '/' . $archivo;
    if (file_exists($ruta)) {
        $diagnostico['archivos'][$archivo] = '✓ Existe';
    } else {
        $diagnostico['archivos'][$archivo] = '✗ NO EXISTE';
        $diagnostico['errores'][] = '✗ Archivo faltante: ' . $archivo;
    }
}

// ============================================================================
// 3. VERIFICAR SESIÓN
// ============================================================================

if (isset($_SESSION['admin_id'])) {
    $diagnostico['sesion']['admin_id'] = $_SESSION['admin_id'];
    $diagnostico['sesion']['admin_usuario'] = $_SESSION['admin_usuario'] ?? 'N/A';
    $diagnostico['sesion']['admin_general'] = $_SESSION['admin_general'] ?? false ? 'Sí' : 'No';
    $diagnostico['sesion']['rol'] = $_SESSION['rol'] ?? 'N/A';
} else {
    $diagnostico['sesion']['estado'] = 'Sin sesión activa (normal para registro público)';
}

// ============================================================================
// 3. VERIFICAR MÉTODOS EN CONTROLADORES
// ============================================================================

$metodos_requeridos = [
    'SolicitudesRegistroController' => ['crearSolicitud', 'obtenerHistorial', 'aprobarSolicitud', 'rechazarSolicitud', 'eliminarSolicitud'],
    'AdministradoresController' => ['cargar', 'guardar', 'aprobar', 'rechazar']
];

foreach ($metodos_requeridos as $clase => $metodos) {
    $archivo = __DIR__ . '/controllers/' . $clase . '.php';
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        foreach ($metodos as $metodo) {
            if (strpos($contenido, 'public function ' . $metodo) !== false) {
                $diagnostico['archivos'][$clase . '::' . $metodo] = '✓ Existe';
            } else {
                $diagnostico['archivos'][$clase . '::' . $metodo] = '✗ NO EXISTE';
                $diagnostico['errores'][] = '✗ Método faltante: ' . $clase . '::' . $metodo;
            }
        }
    }
}

// ============================================================================
// MOSTRAR RESULTADOS
// ============================================================================

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico del Sistema</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1.1em;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h2 {
            color: #333;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        
        .item {
            padding: 12px 15px;
            margin-bottom: 8px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .item.success {
            border-left-color: #27ae60;
            background: #f0fdf4;
        }
        
        .item.error {
            border-left-color: #e74c3c;
            background: #fef2f2;
        }
        
        .item-label {
            font-weight: 600;
            color: #333;
        }
        
        .item-value {
            color: #666;
            font-size: 0.95em;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .errores-section {
            background: #fff5f5;
            border: 2px solid #e74c3c;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .errores-section h3 {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        
        .error-item {
            padding: 10px;
            margin-bottom: 8px;
            background: white;
            border-left: 4px solid #e74c3c;
            border-radius: 4px;
            color: #721c24;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .summary-card h3 {
            font-size: 2em;
            margin-bottom: 5px;
        }
        
        .summary-card p {
            opacity: 0.9;
            font-size: 0.95em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Diagnóstico del Sistema</h1>
            <p>Verificación de configuración y componentes</p>
        </div>
        
        <div class="content">
            <!-- RESUMEN -->
            <div class="summary">
                <div class="summary-card">
                    <h3><?php echo count($diagnostico['base_datos']); ?></h3>
                    <p>Componentes BD</p>
                </div>
                <div class="summary-card">
                    <h3><?php echo count($diagnostico['archivos']); ?></h3>
                    <p>Archivos OK</p>
                </div>
                <div class="summary-card">
                    <h3><?php echo count($diagnostico['errores']); ?></h3>
                    <p>Errores Encontrados</p>
                </div>
            </div>
            
            <!-- BASE DE DATOS -->
            <div class="section">
                <h2>📊 Base de Datos</h2>
                <?php foreach ($diagnostico['base_datos'] as $clave => $valor): ?>
                    <div class="item success">
                        <span class="item-label"><?php echo ucfirst(str_replace('_', ' ', $clave)); ?></span>
                        <span class="item-value"><?php echo $valor; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- ARCHIVOS -->
            <div class="section">
                <h2>📁 Archivos</h2>
                <?php foreach ($diagnostico['archivos'] as $clave => $valor): ?>
                    <div class="item <?php echo strpos($valor, '✓') !== false ? 'success' : 'error'; ?>">
                        <span class="item-label"><?php echo $clave; ?></span>
                        <span class="status-badge <?php echo strpos($valor, '✓') !== false ? 'status-success' : 'status-error'; ?>">
                            <?php echo $valor; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- SESIÓN -->
            <div class="section">
                <h2>👤 Sesión</h2>
                <?php if (!empty($diagnostico['sesion'])): ?>
                    <?php foreach ($diagnostico['sesion'] as $clave => $valor): ?>
                        <div class="item">
                            <span class="item-label"><?php echo ucfirst(str_replace('_', ' ', $clave)); ?></span>
                            <span class="item-value"><?php echo is_array($valor) ? json_encode($valor) : $valor; ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="item">
                        <span class="item-label">Estado</span>
                        <span class="item-value">Sin sesión activa</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- ERRORES -->
            <?php if (!empty($diagnostico['errores'])): ?>
                <div class="errores-section">
                    <h3>⚠️ Errores Encontrados (<?php echo count($diagnostico['errores']); ?>)</h3>
                    <?php foreach ($diagnostico['errores'] as $error): ?>
                        <div class="error-item"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="item success">
                    <span class="item-label">✓ Estado General</span>
                    <span class="status-badge status-success">TODO OK</span>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            <p>
                <strong>Próximos pasos:</strong>
                <a href="index.php?accion=registroUsuario">Probar Registro</a> | 
                <a href="index.php?accion=login">Ir al Login</a> | 
                <a href="test_registro.php">Test de Registro</a>
            </p>
        </div>
    </div>
</body>
</html>
