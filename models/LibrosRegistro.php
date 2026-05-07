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

    // ✅ Constructor estilo Clientes
    public function __construct($data = []) {
        $this->id_libro     = $data['id_libro'] ?? null;
        $this->tipo         = $data['tipo'] ?? null;
        $this->numero_libro = $data['numero_libro'] ?? null;
        $this->anio_inicio  = $data['anio_inicio'] ?? null;
        $this->fecha_fin    = $data['fecha_fin'] ?? null;
        $this->distrito     = $data['distrito'] ?? null;
        $this->provincia    = $data['provincia'] ?? null;
        $this->descripcion  = $data['descripcion'] ?? null;
    }

    // GETTERS Y SETTERS

    public function getIdLibro() { return $this->id_libro; }
    public function setIdLibro($id) { $this->id_libro = $id; }

    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) {
        $this->tipo = strtolower(trim($tipo));
    }

    public function getNumeroLibro() { return $this->numero_libro; }
    public function setNumeroLibro($num) {
        $this->numero_libro = trim($num);
    }

    public function getAnioInicio() { return $this->anio_inicio; }
    public function setAnioInicio($anio) {
        $this->anio_inicio = $anio;
    }

    public function getFechaFin() { return $this->fecha_fin; }
    public function setFechaFin($fecha) {
        $this->fecha_fin = $fecha ?: null;
    }

    public function getDistrito() { return $this->distrito; }
    public function setDistrito($distrito) {
        $this->distrito = ucwords(strtolower(trim($distrito)));
    }

    public function getProvincia() { return $this->provincia; }
    public function setProvincia($provincia) {
        $this->provincia = ucwords(strtolower(trim($provincia)));
    }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($desc) {
        $this->descripcion = trim($desc);
    }
}
?>