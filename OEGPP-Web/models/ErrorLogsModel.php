<?php
    require_once './config/DB.php';
    require_once 'ErrorLogs.php';

    class ErrorLogsModel {
        private $db;
        public function __construct() {
            $this->db = DB::conectar();
        }

        public function cargar() {
            $sql = "SELECT * FROM obtener_error_logs()";
            $ps = $this->db->prepare($sql);
            $ps->execute();
            $filas = $ps->fetchAll();
            $errorLogs = array();
            foreach ($filas as $f) {
                $log = new ErrorLogs();
                $log->setId($f[0]);
                $log->setUsuarioId($f[1]);
                $log->setMensaje($f[2]);
                $log->setTipo($f[3]);
                $log->setArchivo($f[4]);
                $log->setFecha($f[5]);
                $log->setStackTrace($f[6]);
                array_push($errorLogs, $log);
            }
            return $errorLogs;
        }

        public function buscar($texto, $campo = null) {
            $texto = trim($texto);
            if ($texto === '') {
                return $this->cargar();
            }
            $allowedFields = ['usuario_id', 'mensaje', 'tipo', 'archivo', 'linea', 'fecha'];
            if ($campo !== null && in_array($campo, $allowedFields, true)) {
                $sql = "SELECT * FROM error_logs WHERE $campo LIKE :q";
            } else {
                $sql = "SELECT * FROM error_logs
                    WHERE usuario_id::text LIKE :q
                       OR mensaje LIKE :q
                       OR tipo LIKE :q
                       OR archivo LIKE :q
                       OR linea::text LIKE :q
                       OR fecha::text LIKE :q";
            }
            $ps = $this->db->prepare($sql);
            $ps->bindValue(':q', '%' . $texto . '%', PDO::PARAM_STR);
            $ps->execute();
            $filas = $ps->fetchAll();
            $errorLogs = array();
            foreach ($filas as $f) {
                $log = new ErrorLogs();
                $log->setId($f[0]);
                $log->setUsuarioId($f[1]);
                $log->setMensaje($f[2]);
                $log->setTipo($f[3]);
                $log->setArchivo($f[4]);
                $log->setLinea($f[5]);
                $log->setFecha($f[6]);
                array_push($errorLogs, $log);
            }
            return $errorLogs;
        }

        public function guardar(ErrorLogs $log) {
            $sql = "INSERT INTO error_logs 
            (usuario_id, mensaje, tipo, archivo, linea, stack_trace) 
            VALUES (:uid,:men,:tip,:arc,:lin, :sat)";
            $ps = $this->db->prepare($sql);
            $uid= $log->getUsuarioId();
            $men= $log->getMensaje();
            $tip= $log->getTipo();
            $arc= $log->getArchivo();
            $lin= $log->getLinea();
            $sat= $log->getStackTrace();
            $stack_trace= $log->getStackTrace();
            $ps->bindParam(":uid", $uid);
            $ps->bindParam(":men", $men);
            $ps->bindParam(":tip", $tip);
            $ps->bindParam(":arc", $arc);
            $ps->bindParam(":lin", $lin);
            $ps->bindParam(":sat", $sat);
            $ps->execute();
        }
    }
?>