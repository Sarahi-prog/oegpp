<?php
    class Sesiones{
        private id_sesion;
        private usuario_id;
        private fecha_inicio;
        private fecha_fin;
        private activa;
        
        public function getIdSesion() {
            return $this->id_sesion;
        }
        public function setIdSesion($id_sesion) {
            $this->id_sesion = $id_sesion;
        }
        public function getUsuarioId() {
            return $this->usuario_id;
        }
        public function setUsuarioId($usuario_id) {
            $this->usuario_id = $usuario_id;
        }
        public function getFechaInicio() {
            return $this->fecha_inicio;
        }
        public function setFechaInicio($fecha_inicio) {
            $this->fecha_inicio = $fecha_inicio;
        }
        public function getFechaFin() {
            return $this->fecha_fin;
        }
        public function setFechaFin($fecha_fin) {
            $this->fecha_fin = $fecha_fin;
        }
        public function getActiva() {
            return $this->activa;
        }
        public function setActiva($activa) {
            $this->activa = $activa;
        }

    }
?>