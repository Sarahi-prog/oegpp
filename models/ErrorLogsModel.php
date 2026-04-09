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
                $log->setFecha($f[3]);
                array_push($errorLogs, $log);
            }
            return $errorLogs;
        }

        public function guardar(ErrorLogs $log) {
            $sql = "INSERT INTO error_logs 
            (usuario_id, mensaje, tipo, archivo, linea) 
            VALUES (:uid,:men,:tip,:arc,:lin)";
            $ps = $this->db->prepare($sql);
            $uid= $log->getUsuarioId();
            $men= $log->getMensaje();
            $tip= $log->getTipo();
            $arc= $log->getArchivo();
            $lin= $log->getLinea();
            $ps->bindParam(":uid", $uid);
            $ps->bindParam(":men", $men);
            $ps->bindParam(":tip", $tip);
            $ps->bindParam(":arc", $arc);
            $ps->bindParam(":lin", $lin);
            $ps->execute();
        }
    }
?>