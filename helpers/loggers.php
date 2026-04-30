<?php
// Archivo: helpers/loggers.php

// Comentamos la llamada al modelo para que no busque la tabla "error_logs"
// require_once __DIR__ . '/../models/ErrorLogsModel.php'; 

class Logger {
    public static function error($e) {
        
        // Bloqueamos la ejecución normal y forzamos a mostrar el error REAL en pantalla
        echo "<div style='background:#fee2e2; color:#b91c1c; padding:20px; border:1px solid #ef4444; border-radius:8px; margin:20px; font-family: sans-serif; position: relative; z-index: 9999;'>";
        echo "<h3 style='margin-top:0;'>¡El Logger fue pausado! Aquí está el error ORIGINAL:</h3>";
        echo "<strong>Mensaje de PostgreSQL:</strong> " . $e->getMessage() . "<br><br>";
        echo "<strong>Ocurrió en el archivo:</strong> " . $e->getFile() . "<br>";
        echo "<strong>En la línea:</strong> " . $e->getLine() . "<br>";
        echo "</div>";
        
        // Detenemos PHP para que no se quede la pantalla en blanco
        die(); 
    }
}
?>