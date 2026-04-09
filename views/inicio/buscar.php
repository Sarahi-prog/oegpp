<?php
echo "<h1>Buscando trabajadores.php</h1>";

echo "<h2>Ubicación actual:</h2>";
echo "Directorio actual: " . __DIR__ . "<br>";
echo "URL actual: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "<br><br>";

echo "<h2>Buscando en diferentes ubicaciones:</h2>";

// Buscar en la misma carpeta (views/inicio/)
if (file_exists('trabajadores.php')) {
    echo "✅ Encontrado en: " . realpath('trabajadores.php') . "<br>";
    echo "URL: /views/inicio/trabajadores.php<br>";
} else {
    echo "❌ No está en /views/inicio/<br>";
}

// Buscar en la carpeta padre (views/)
if (file_exists('../trabajadores.php')) {
    echo "✅ Encontrado en: " . realpath('../trabajadores.php') . "<br>";
    echo "URL: /views/trabajadores.php<br>";
} else {
    echo "❌ No está en /views/<br>";
}

// Buscar en la raíz del proyecto
if (file_exists('../../trabajadores.php')) {
    echo "✅ Encontrado en: " . realpath('../../trabajadores.php') . "<br>";
    echo "URL: /trabajadores.php<br>";
} else {
    echo "❌ No está en la raíz<br>";
}

echo "<h2>Contenido de la carpeta actual (/views/inicio/):</h2>";
$archivos = scandir('.');
foreach ($archivos as $archivo) {
    if ($archivo != '.' && $archivo != '..') {
        echo "- $archivo<br>";
    }
}

echo "<h2>Contenido de la carpeta padre (/views/):</h2>";
$archivos_padre = scandir('..');
foreach ($archivos_padre as $archivo) {
    if ($archivo != '.' && $archivo != '..') {
        echo "- $archivo<br>";
    }
}

echo "<h2>Contenido de la raíz (2 niveles arriba):</h2>";
$archivos_raiz = scandir('../..');
foreach ($archivos_raiz as $archivo) {
    if ($archivo != '.' && $archivo != '..') {
        echo "- $archivo<br>";
    }
}
?>
