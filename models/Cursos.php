<?php

class Cursos {
    // 1. Declaración de propiedades (Añadimos $estado y tipos de datos)
    private ?int $id_curso = null;
    private ?string $codigo_curso = null;
    private ?string $nombre_curso = null;
    private ?string $tipo = null;
    private ?int $horas_totales = null;
    private int $estado = 1; // Por defecto 1 (Activo)

    public function __construct() {}

    // --- GETTERS Y SETTERS ---

    public function getIdCurso(): ?int { 
        return $this->id_curso; 
    }
    public function setIdCurso(?int $id): void { 
        $this->id_curso = $id; 
    }

    public function getCodigoCurso(): ?string { 
        return $this->codigo_curso; 
    }
    public function setCodigoCurso(?string $codigo): void { 
        $this->codigo_curso = $codigo; 
    }

    public function getNombreCurso(): ?string { 
        return $this->nombre_curso; 
    }
    public function setNombreCurso(?string $nombre): void { 
        $this->nombre_curso = $nombre; 
    }

    public function getTipo(): ?string { 
        return $this->tipo; 
    }
    public function setTipo(?string $tipo): void { 
        $this->tipo = $tipo; 
    }

    public function getHorasTotales(): ?int { 
        return $this->horas_totales; 
    }
    public function setHorasTotales(?int $horas): void { 
        $this->horas_totales = $horas; 
    }
    
    public function getEstado(): int {
        return $this->estado;
    }
    public function setEstado(int $estado): void {
        $this->estado = $estado;
    }
}
?>