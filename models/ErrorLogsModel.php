<?php
require_once './config/DB.php';
require_once 'ErrorLogs.php';

class ErrorLogsModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    // =========================
    // CARGAR
    // =========================
    public function cargar() {
        $sql = "SELECT * FROM error_logs ORDER BY id DESC";
        $ps = $this->db->prepare($sql);
        $ps->execute();

        $filas = $ps->fetchAll(PDO::FETCH_ASSOC);
        $errorLogs = [];

        foreach ($filas as $f) {
            $log = new ErrorLogs();
            $log->setId($f['id']);
            $log->setUsuarioId($f['admin_id']); // 🔥 CORREGIDO
            $log->setMensaje($f['mensaje']);
            $log->setTipo($f['tipo']);
            $log->setArchivo($f['archivo']);
            $log->setLinea($f['linea']);
            $log->setFecha($f['fecha']);
            $log->setStackTrace($f['stack_trace']);

            $errorLogs[] = $log;
        }

        return $errorLogs;
    }

    // =========================
    // BUSCAR
    // =========================
    public function buscar($texto, $campo = null) {
        $texto = trim($texto);

        if ($texto === '') {
            return $this->cargar();
        }

        $allowedFields = ['admin_id', 'mensaje', 'tipo', 'archivo', 'linea', 'fecha'];

        if ($campo !== null && in_array($campo, $allowedFields, true)) {
            $sql = "SELECT * FROM error_logs WHERE $campo::text ILIKE :q";
        } else {
            $sql = "SELECT * FROM error_logs
                    WHERE admin_id::text ILIKE :q
                       OR mensaje ILIKE :q
                       OR tipo ILIKE :q
                       OR archivo ILIKE :q
                       OR linea::text ILIKE :q
                       OR fecha::text ILIKE :q";
        }

        $ps = $this->db->prepare($sql);
        $ps->bindValue(':q', '%' . $texto . '%', PDO::PARAM_STR);
        $ps->execute();

        $filas = $ps->fetchAll(PDO::FETCH_ASSOC);
        $errorLogs = [];

        foreach ($filas as $f) {
            $log = new ErrorLogs();
            $log->setId($f['id']);
            $log->setUsuarioId($f['admin_id']); // 🔥 CORREGIDO
            $log->setMensaje($f['mensaje']);
            $log->setTipo($f['tipo']);
            $log->setArchivo($f['archivo']);
            $log->setLinea($f['linea']);
            $log->setFecha($f['fecha']);
            $log->setStackTrace($f['stack_trace']);

            $errorLogs[] = $log;
        }

        return $errorLogs;
    }

    // =========================
    // GUARDAR
    // =========================
    public function guardar(ErrorLogs $log) {

        $sql = "INSERT INTO error_logs 
                (admin_id, mensaje, tipo, archivo, linea, stack_trace) 
                VALUES (:aid, :men, :tip, :arc, :lin, :stk)";

        $ps = $this->db->prepare($sql);

        $aid = $log->getUsuarioId();
        $men = $log->getMensaje();
        $tip = $log->getTipo();
        $arc = $log->getArchivo();
        $lin = $log->getLinea();
        $stk = $log->getStackTrace();

        $ps->bindParam(":aid", $aid);
        $ps->bindParam(":men", $men);
        $ps->bindParam(":tip", $tip);
        $ps->bindParam(":arc", $arc);
        $ps->bindParam(":lin", $lin);
        $ps->bindParam(":stk", $stk);

        $ps->execute();
    }
}
?>