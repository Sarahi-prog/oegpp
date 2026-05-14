<?php
class ProgramaEducativo {

    private ?int $id_programa = null;
    private ?string $codigo = null;
    private ?string $nombre = null;
    private ?string $tipo = null;
    private ?int $horas_totales = null;
    private int $estado = 1;

    public function __construct() {}

    // ID
    public function getIdPrograma(): ?int {
        return $this->id_programa;
    }

    public function setIdPrograma(?int $id): void {
        $this->id_programa = $id;
    }

    // CODIGO
    public function getCodigo(): ?string {
        return $this->codigo;
    }

    public function setCodigo(?string $codigo): void {
        $this->codigo = $codigo;
    }

    // NOMBRE
    public function getNombre(): ?string {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): void {
        $this->nombre = $nombre;
    }

    // TIPO
    public function getTipo(): ?string {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): void {
        $this->tipo = $tipo;
    }

    // HORAS
    public function getHorasTotales(): ?int {
        return $this->horas_totales;
    }

    public function setHorasTotales(?int $horas): void {
        $this->horas_totales = $horas;
    }

    // ESTADO
    public function getEstado(): int {
        return $this->estado;
    }

    public function setEstado(?int $estado): void {
        $this->estado = $estado ?? 1;
    }
}
?>