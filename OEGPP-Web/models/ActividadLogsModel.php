<?php
    require_once './config/DB.php';
    require_once 'ActividadLogs.php';

    class ActividadLogsModel {
        private $db;
        public function __construct() {
            $this->db = DB::conectar();
        }

        public function cargar() {
            $sql = "SELECT * FROM obtener_actividad_logs()";
            $ps = $this->db->prepare($sql);
            $ps->execute();
            $filas = $ps->fetchAll();
            $actividadLogs = array();
            foreach ($filas as $f) {
                $log = new ActividadLogs();
                $log->setId($f[0]);
                $log->setUsuarioId($f[1]);
                $log->setAccion($f[2]);
                $log->setTablaAfectada($f[3]);
                $log->setRegistroId($f[4]);
                $log->setDescripcion($f[5]);
                $log->setFecha($f[6]);
                array_push($actividadLogs, $log);
            }
            return $actividadLogs;
        }

        public function guardar(ActividadLogs $log) {
            $sql = "INSERT INTO actividad_logs (
            accion, tabla_afectada, registro_id, descripcion, fecha) 
            VALUES (:acc, :taf, :rid, :des, :fec)";
            $ps = $this->db->prepare($sql);
            $ps->bindParam(":acc", $log->getAccion());
            $ps->bindParam(":taf", $log->getTablaAfectada());
            $ps->bindParam(":rid", $log->getRegistroId());
            $ps->bindParam(":des", $log->getDescripcion());
            $ps->bindParam(":fec", $log->getFecha());
            $ps->execute();
        }
    }
?>