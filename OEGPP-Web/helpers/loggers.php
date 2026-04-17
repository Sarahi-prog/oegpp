<?php
require_once 'models/ErrorLogs.php';
require_once 'models/ErrorLogsModel.php';

class Logger {

    public static function error($e) {
        $errorModel = new ErrorLogsModel();
        
        $log = new ErrorLogs([
            'usuario_id' => $_SESSION['usuario_id'] ?? null,
            'mensaje' => $e->getMessage(),
            'tipo' => 'ERROR',
            'archivo' => $e->getFile(),
            'linea' => $e->getLine(),
            'stack_trace' => $e->getTraceAsString()
        ]);
        $errorModel->guardar($log);
    }
}
?>