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
            VALUES (:acc, :taf, :rid, :desc, :fec)";
            $ps = $this->db->prepare($sql);
            $acc= $log->getAccion();
            $taf= $log->getTablaAfectada();
            $rid= $log->getRegistroId();
            $des= $log->getDescripcion();
            $fec= $log->getFecha();
            $ps->bindParam(":acc", $acc);
            $ps->bindParam(":taf", $taf);
            $ps->bindParam(":rid", $rid);
            $ps->bindParam(":desc", $des);
            $ps->bindParam(":fec", $fec);
            $ps->execute();
        }
    }
?>