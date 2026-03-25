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
            VALUES (
            :uid, 
            :men, 
            :tip, 
            :arc, 
            :lin
            )";
            $ps = $this->db->prepare($sql);
            $ps->bindParam(":uid", $log->getUsuarioId());
            $ps->bindParam(":men", $log->getMensaje());
            $ps->bindParam(":tip", $log->getTipo());
            $ps->bindParam(":arc", $log->getArchivo());
            $ps->bindParam(":lin", $log->getLinea());
            $ps->execute();
        }
    }
?>