<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Capacitaciones - OEGPP</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Rajdhani:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/menuStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="public/capacitacionesStyles.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/menu.php'; ?>

    <div class="container main-content">

        <!-- ── Cabecera ── -->
        <div class="header-acciones">
            <div class="titulo-con-boton">
                <div class="directorio-title-container">
                    <h2><i class="fas fa-clipboard-check"></i> Consulta de Capacitaciones</h2>
                    <p>Verifique los cursos, libros de registro y certificados de un trabajador por su DNI.</p>
                </div>
            </div>
        </div>

        <div class="dashboard-wrapper">

            <!-- ── Buscador por DNI ── -->
            <div class="search-hero">
                <form action="index.php" method="GET" class="search-hero-wrapper">
                    <input type="hidden" name="accion" value="buscar_dni">
                    <input
                        type="text"
                        name="dni"
                        id="inputDni"
                        class="search-hero-input"
                        placeholder="Ingrese el número de DNI del trabajador..."
                        value="<?= isset($_GET['dni']) ? htmlspecialchars($_GET['dni']) : '' ?>"
                        maxlength="8"
                        pattern="\d{8}"
                        title="El DNI debe tener 8 dígitos"
                        required
                    >
                    <button type="submit" class="btn-primary-green">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
            </div>

            <!-- ── Tabla de resultados ── -->
            <div class="table-section">
                <div class="table-card">

                    <?php if (isset($capacitaciones) && !empty($capacitaciones)):
                        // Datos del trabajador desde la primera fila del JOIN con clientes
                        $trabajador     = htmlspecialchars($capacitaciones[0]['nombre_cliente']);
                        $dni            = htmlspecialchars($capacitaciones[0]['dni']);
                        $totalRegistros = count($capacitaciones);
                        $totalHoras     = array_sum(array_column($capacitaciones, 'horas_realizadas'));
                    ?>

                        <!-- Banner de resultado encontrado -->
                        <div class="alert-resultados">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                Trabajador: <strong><?= $trabajador ?></strong>
                                &nbsp;&mdash;&nbsp; DNI: <strong><?= $dni ?></strong>
                                &nbsp;&mdash;&nbsp;
                                <span><?= $totalRegistros ?> registro<?= $totalRegistros !== 1 ? 's' : '' ?></span>
                                &nbsp;&mdash;&nbsp;
                                <span><?= $totalHoras ?> horas en total</span>
                            </div>
                        </div>

                    <?php endif; ?>

                    <div class="table-container">
                        <table class="data-table" id="tablaCapacitaciones">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Curso</th>
                                    <th>Tipo</th>
                                    <th>Libro de registro</th>
                                    <th>N° Reg.</th>
                                    <th>Folio</th>
                                    <th>Horas</th>
                                    <th>Inicio / Fin</th>
                                    <th>Emisión</th>
                                    <th>Estado</th>
                                    <th>Entregado</th>
                                    <th style="text-align:center;">Certificado</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php if (isset($capacitaciones) && !empty($capacitaciones)):
                                $i = 1;
                                foreach ($capacitaciones as $reg):

                                    /* ── Tipo de curso (cursos.tipo) ── */
                                    $tipoRaw      = $reg['tipo'] ?? '';
                                    $tipoBadge    = ($tipoRaw === 'diplomados') ? 'badge-diplomado' : 'badge-certificado';
                                    $tipoLabel    = !empty($tipoRaw) ? ucfirst(rtrim($tipoRaw, 's')) : 'Sin tipo';

                                    /* ── Estado (registros_capacitacion.estado) ── */
                                    $estadoRaw    = strtolower(trim($reg['estado'] ?? ''));
                                    $estadoClass  = match($estadoRaw) {
                                        'aprobado'  => 'aprobado',
                                        'anulado'   => 'anulado',
                                        default     => 'pendiente',
                                    };
                                    $estadoIcono  = match($estadoRaw) {
                                        'aprobado'  => 'fa-check-circle',
                                        'anulado'   => 'fa-times-circle',
                                        default     => 'fa-clock',
                                    };
                                    $estadoLabel  = !empty($reg['estado']) ? ucfirst($reg['estado']) : 'Pendiente';

                                    /* ── Entregado (registros_capacitacion.entregado) ── */
                                    $entregado    = !empty($reg['entregado']) && strtolower(trim($reg['entregado'])) !== 'no';
                                    $entregadoPor = htmlspecialchars($reg['entregadopor'] ?? '');

                                    /* ── Fechas formateadas ── */
                                    $fmtFecha = fn($f) => !empty($f)
                                        ? date('d/m/Y', strtotime($f))
                                        : '<span class="sin-link">—</span>';
                            ?>
                                <tr class="fila-capacitacion">

                                    <!-- # -->
                                    <td class="id-column td-muted"><?= $i++ ?></td>

                                    <!-- Nombre del curso (cursos.nombre_curso) -->
                                    <td>
                                        <strong><?= htmlspecialchars($reg['nombre_curso']) ?></strong>
                                        <?php if (!empty($reg['codigo_curso'])): ?>
                                            <br>
                                            <span class="codigo-box" style="margin-top:4px; display:inline-block;">
                                                <?= htmlspecialchars($reg['codigo_curso']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Tipo de curso (cursos.tipo) -->
                                    <td>
                                        <span class="badge <?= $tipoBadge ?>">
                                            <?= $tipoLabel ?>
                                        </span>
                                    </td>

                                    <!-- Libro de registro (libros_registro JOIN) -->
                                    <td class="td-muted">
                                        <?= htmlspecialchars($reg['nombre_libro'] ?? '—') ?>
                                    </td>

                                    <!-- N° de registro (registros_capacitacion.registro) -->
                                    <td>
                                        <span class="badge-registro">
                                            <?= htmlspecialchars($reg['registro']) ?>
                                        </span>
                                    </td>

                                    <!-- Folio (registros_capacitacion.folio) -->
                                    <td>
                                        <?php if (!empty($reg['folio'])): ?>
                                            <span class="codigo-box">
                                                <?= htmlspecialchars($reg['folio']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="sin-link">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Horas realizadas (registros_capacitacion.horas_realizadas) -->
                                    <td class="td-muted td-nowrap horas-cell">
                                        <i class="far fa-clock"></i>
                                        <?= htmlspecialchars($reg['horas_realizadas']) ?> h
                                    </td>

                                    <!-- Fechas inicio / fin (registros_capacitacion.fecha_inicio, fecha_fin) -->
                                    <td class="td-muted td-nowrap">
                                        <?= $fmtFecha($reg['fecha_inicio']) ?> /
                                        <?= $fmtFecha($reg['fecha_fin']) ?>
                                    </td>

                                    <!-- Fecha de emisión del certificado (registros_capacitacion.fecha_emision) -->
                                    <td class="td-muted td-nowrap">
                                        <?= $fmtFecha($reg['fecha_emision']) ?>
                                    </td>

                                    <!-- Estado (registros_capacitacion.estado) -->
                                    <td>
                                        <span class="badge-estado <?= $estadoClass ?>">
                                            <i class="fas <?= $estadoIcono ?>"></i>
                                            <?= $estadoLabel ?>
                                        </span>
                                    </td>

                                    <!-- Entregado / entregadopor (registros_capacitacion.entregado, entregadopor) -->
                                    <td>
                                        <?php if ($entregado): ?>
                                            <span class="badge-entregado"
                                                  <?= $entregadoPor ? "title=\"Por: $entregadoPor\"" : '' ?>>
                                                <i class="fas fa-check"></i> Sí
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-no-entregado">
                                                <i class="fas fa-minus"></i> No
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- linkr: URL del certificado para escanear QR
                                         Campo: registros_capacitacion.linkr -->
                                    <td style="text-align:center;">
                                        <?php if (!empty($reg['linkr'])): ?>
                                            <a href="<?= htmlspecialchars($reg['linkr']) ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="btn-qr"
                                               title="Ver certificado / escanear QR">
                                                <i class="fas fa-qrcode"></i> Ver QR
                                            </a>
                                        <?php else: ?>
                                            <span class="sin-link"><i class="fas fa-minus"></i></span>
                                        <?php endif; ?>
                                    </td>

                                </tr>

                            <?php
                                endforeach;
                            else:
                            ?>

                                <!-- Estado vacío -->
                                <tr>
                                    <td colspan="12">
                                        <div class="empty-state-inner">
                                            <i class="fas fa-clipboard-list"></i>

                                            <?php if (isset($_GET['dni']) && $_GET['dni'] !== ''): ?>
                                                <h4>Sin resultados</h4>
                                                <p>No se encontraron capacitaciones para el DNI
                                                    <strong><?= htmlspecialchars($_GET['dni']) ?></strong>.
                                                </p>
                                            <?php else: ?>
                                                <h4>Realice una búsqueda</h4>
                                                <p>Ingrese un número de DNI válido para ver los registros del trabajador.</p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                            <?php endif; ?>

                            </tbody>
                        </table>
                    </div><!-- /.table-container -->

                </div><!-- /.table-card -->
            </div><!-- /.table-section -->

        </div><!-- /.dashboard-wrapper -->
    </div><!-- /.container -->

    <script src="public/UniversalScript.js?v=<?= time(); ?>"></script>
    <script src="public/capacitacionesScript.js?v=<?= time(); ?>"></script>
</body>
</html>