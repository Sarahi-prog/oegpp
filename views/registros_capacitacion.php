<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Capacitaciones - OEGPP</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/capacitacionesStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Estilos adicionales para centrar el buscador */
        .search-hero {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            text-align: center;
        }
        .search-hero-wrapper {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            gap: 10px;
        }
        .search-hero-input {
            flex: 1;
            padding: 12px 20px;
            font-size: 1.1em;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-family: 'Inter', sans-serif;
        }
        .dashboard-wrapper {
            display: block; 
        }
        /* Estilo para la alerta de resultados */
        .alert-resultados {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #166534;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head> 
<body>
    <?php include 'includes/menu.php'; ?>

    <div class="container main-content">
    
        <div class="header-acciones">
            <div class="titulo-con-boton">
                <div class="directorio-title-container">
                    <h2><i class="fas fa-search"></i> Consulta de Capacitaciones</h2>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Ingrese el DNI del trabajador para verificar sus cursos y libros de registro.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">
            
            <div class="search-hero">
                <form action="index.php" method="GET" class="search-hero-wrapper">
                    <input type="hidden" name="accion" value="buscar_dni">
                    
                    <input type="text" name="dni" id="inputDni" class="search-hero-input" 
                        placeholder="Ingrese el número de DNI..." 
                        value="<?php echo isset($_GET['dni']) ? htmlspecialchars($_GET['dni']) : ''; ?>" required>
                        
                    <button type="submit" class="btn btn-primary-green">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
            </div>

            <div class="table-section">
                <div class="table-card">
                    
                    <?php if (isset($capacitaciones) && !empty($capacitaciones)): ?>
                        <div class="alert-resultados">
                            <i class="fas fa-check-circle"></i> Mostrando registros para: <strong><?php echo htmlspecialchars($capacitaciones[0]['nombre_cliente']); ?></strong> 
                            (DNI: <?php echo htmlspecialchars($capacitaciones[0]['dni']); ?>)
                        </div>
                    <?php endif; ?>

                    <div class="table-container">
                        <table class="data-table" id="tablaResultados">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>TRABAJADOR</th>
                                    <th>DNI</th>
                                    <th>CURSO</th>
                                    <th>LIBRO DE REGISTRO</th>
                                    <th>N° REG</th>
                                    <th>HORAS</th>
                                    <th>INICIO / FIN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($capacitaciones) && !empty($capacitaciones)): ?>
                                    
                                    <?php foreach ($capacitaciones as $index => $reg): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($reg['nombre_cliente']); ?></td>
                                            <td><?php echo htmlspecialchars($reg['dni']); ?></td>
                                            <td><?php echo htmlspecialchars($reg['nombre_curso']); ?></td>
                                            <td><?php echo htmlspecialchars($reg['nombre_libro']); ?></td>
                                            <td><?php echo htmlspecialchars($reg['registro']); ?></td>
                                            <td><?php echo htmlspecialchars($reg['horas_realizadas']); ?> h</td>
                                            <td>
                                                <?php echo htmlspecialchars($reg['fecha_inicio']); ?> / 
                                                <?php echo htmlspecialchars($reg['fecha_fin']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 4rem 2rem;">
                                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.7;">
                                                <i class="fas fa-id-card" style="font-size: 4rem; color: #94a3b8; margin-bottom: 15px;"></i>
                                                
                                                <?php if(isset($_GET['dni'])): ?>
                                                    <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Sin resultados</h4>
                                                    <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">No se encontraron capacitaciones registradas para el DNI <strong><?php echo htmlspecialchars($_GET['dni']); ?></strong>.</p>
                                                <?php else: ?>
                                                    <h4 style="margin: 0; color: #0f172a; font-size: 1.2rem; font-weight: 600;">Realice una búsqueda</h4>
                                                    <p style="margin: 5px 0 0 0; color: #64748b; font-size: 0.95rem;">Ingrese un número de DNI válido para ver los registros del trabajador.</p>
                                                <?php endif; ?>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
    
    <script src="public/UniversalScript.js?v=<?= time(); ?>"></script>
    <script src="public/clientesScript.js?v=<?= time(); ?>"></script>
</body>
</html>