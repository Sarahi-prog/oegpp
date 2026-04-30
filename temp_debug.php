<?php
require_once 'config/DB.php';
require_once 'models/RegistroCapacitacion.php';
require_once 'models/RegistroCapacitacionModel.php';

try {
    $model = new RegistroCapacitacionModel();
    $rows = $model->cargar();
    echo 'OK '.count($rows).' rows';
} catch (Throwable $e) {
    echo 'EXCEPTION: '.get_class($e)." - " . $e->getMessage();
}
