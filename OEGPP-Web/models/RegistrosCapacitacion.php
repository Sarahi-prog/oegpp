<?php
    class RegistroCapacitacion{
        private $id_registro;
        private $trabajador_id;
        private $curso_id;
        private $libro_id;
        private $registro;
        private $horas_realizadas;
        private $fecha_inicio;
        private $fecha_fin;
        private $fecha_emision;
        private $folio;
        private $fecha_registro;
        private $estado;
        private $linkr;
        private $qr;
        private $entregado;
        private $entregadopor;

        public function getIdRegistro() {
            return $this->id_registro;
        }
        public function setIdRegistro($id_registro) {
            $this->id_registro = $id_registro;
        }
        public function getTrabajadorId() {
            return $this->trabajador_id;
        }
        public function setTrabajadorId($trabajador_id) {
            $this->trabajador_id = $trabajador_id;
        }
        public function getCursoId() {
            return $this->curso_id;
        }
        public function setCursoId($curso_id) {
            $this->curso_id = $curso_id;
        }
        public function getFechaRegistro() {
            return $this->fecha_registro;
        }
        public function setFechaRegistro($fecha_registro) {
            $this->fecha_registro = $fecha_registro;
        }
        public function getLibroId() {
            return $this->libro_id;
        }
        public function setLibroId($libro_id) {
            $this->libro_id = $libro_id;
        }
        public function getRegistro() {
            return $this->registro;
        }   
        public function setRegistro($registro) {
            $this->registro = $registro;
        }
        public function getHorasRealizadas() {
            return $this->horas_realizadas;
        }
        public function setHorasRealizadas($horas_realizadas) {
            $this->horas_realizadas = $horas_realizadas;
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
        public function getFechaEmision() {
            return $this->fecha_emision;
        }
        public function setFechaEmision($fecha_emision) {
            $this->fecha_emision = $fecha_emision;
        }
        public function getFolio() {
            return $this->folio;
        }
        public function setFolio($folio) {
            $this->folio = $folio;
        }
        public function getEstado() {
            return $this->estado;
        }
        public function setEstado($estado) {
            $this->estado = $estado;
        }
        public function getLinkr() {
            return $this->linkr;
        }
        public function setLinkr($linkr) {
            $this->linkr = $linkr;
        }
        public function getQr() {
            return $this->qr;
        }
        public function setQr($qr) {
            $this->qr = $qr;
        }
        public function getEntregado() {
            return $this->entregado;
        }
        public function setEntregado($entregado) {
            $this->entregado = $entregado;
        }
        public function getEntregadopor() {
            return $this->entregadopor;
        }
        public function setEntregadopor($entregadopor) {
            $this->entregadopor = $entregadopor;
        }
    }
?>