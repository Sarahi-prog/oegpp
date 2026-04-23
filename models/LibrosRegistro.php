<?php
    class LibrosRegistro {
        private $id_libro;
        private $tipo;
        private $numero_libro;
        private $anio_inicio;
        private $fecha_fin;
        private $distrito;
        private $provincia;
        private $descripcion;


        public function getIdLibro() {
            return $this->id_libro;
        }
        public function setIdLibro($id_libro) {
            $this->id_libro = $id_libro;
        }
        public function getTipo() {
            return $this->tipo;
        }
        public function setTipo($tipo) {
            $this->tipo = $tipo;
        }
        public function getNumeroLibro() {
            return $this->numero_libro;
        }
        public function setNumeroLibro($numero_libro) {
            $this->numero_libro = $numero_libro;
        }
        public function getAnioInicio() {
            return $this->anio_inicio;
        }
        public function setAnioInicio($anio_inicio) {
            $this->anio_inicio = $anio_inicio;
        }
        public function getFechaFin() {
            return $this->fecha_fin;
        }
        public function setFechaFin($fecha_fin) {
            $this->fecha_fin = $fecha_fin;
        }

        public function getDistrito() {
            return $this->distrito;
        }
        public function setDistrito($distrito) {
            $this->distrito = $distrito;
        }
        public function getProvincia() {
            return $this->provincia;
        }
        public function setProvincia($provincia) {
            $this->provincia = $provincia;
        }
        public function getDescripcion() {
            return $this->descripcion;
        }
        public function setDescripcion($descripcion) {
            $this->descripcion = $descripcion;
        }
    }
?>