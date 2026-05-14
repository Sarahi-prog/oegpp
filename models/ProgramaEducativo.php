<?php
class ProgramaEducativo {
    // 1. Declaración de propiedades (Añadimos $estado y tipos de datos)
    private ?int $id = null;
    private ?string $codigo = null;
    private ?string $nombre = null;
    private ?string $tipo = null;
    private ?int $horas_totales = null;
    private int $estado = 1; // Por defecto 1 (Activo)
 
    public function __construct() {}
    // --- GETTERS Y SETTERS ---
 
    public function getId(): ?int { 
        return $this->id; 
    }
 
    public function setId(?int $id): void { 
        $this->id = $id; 
    }

    public function getCodigo(): ?string { 
        return $this->codigo; 
    }
 
    public function setCodigo(?string $codigo): void { 
        $this->codigo = $codigo; 
    }

    public function getNombre(): ?string { 
        return $this->nombre; 
    }
    public function setNombre(?string $nombre): void { 
        $this->nombre = $nombre; 
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
    /// cambio
    public function setEstado(?int $estado): void {
    $this->estado = $estado ?? 1; // Si es null, le pone 1
}
}
?>