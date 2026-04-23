<?php
require_once 'models/ErrorLogs.php';
require_once 'models/ErrorLogsModel.php';
require_once 'helpers/loggers.php';

class ErrorLogsController {
    private $model;

    public function __construct() {
        $this->model = new ErrorLogsModel();
    }

    public function buscar() {
        try {
            if (isset($_GET['q'])) {
                $texto = $_GET['q'];
                $campo = $_GET['campo'] ?? null;
                $errorLogs = $this->model->buscar($texto, $campo);
                require_once 'views/error_logs/index.php';
            } else {
                header("Location: index.php?controller=ErrorLogs");
            }
        } catch (Exception $e) {
            Logger::error($e);
            header("Location: index.php?controller=ErrorLogs");
            
        }
    }
}
?>