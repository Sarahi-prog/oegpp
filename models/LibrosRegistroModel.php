<?php
require_once './config/DB.php'; 

class LibrosRegistroModel {
    private $conexion;
    public $ultimoError;

    public function __construct() {
        $this->conexion = DB::conectar();
        $this->ultimoError = null;
    }

    public function cargar() {
        try {
            // Usamos FETCH_OBJ para que PHP cree los objetos automáticamente
            $query = "SELECT id_libro, tipo, numero_libro, anio_inicio, fecha_fin, distrito, provincia, descripcion 
                      FROM libros_registro ORDER BY id_libro DESC";
            $stmt = $this->conexion->query($query);

            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: []; 
        } catch (PDOException $e) {
            return [];
        }
    }

    public function guardarLibro($libro) {
        try {
            $sql = "INSERT INTO libros_registro (tipo, numero_libro, anio_inicio, fecha_fin, distrito, provincia, descripcion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conexion->prepare($sql);
            
            return $stmt->execute([
                $libro->getTipo(),
                $libro->getNumeroLibro(),
                $libro->getAnioInicio(),
                $libro->getFechaFin(),
                $libro->getDistrito(),
                $libro->getProvincia(),
                $libro->getDescripcion()
            ]);
            
        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error BD (Libros): " . $this->ultimoError);
            return false;
        }
    }

    public function modificarLibro($libro) {
        try {
            $sql = "UPDATE libros_registro SET tipo = ?, numero_libro = ?, anio_inicio = ?, 
                           fecha_fin = ?, distrito = ?, provincia = ?, descripcion = ? 
                    WHERE id_libro = ?";
            
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([
                $libro->getTipo(),
                $libro->getNumeroLibro(),
                $libro->getAnioInicio(),
                $libro->getFechaFin(),
                $libro->getDistrito(),
                $libro->getProvincia(),
                $libro->getDescripcion(),
                $libro->getIdLibro() 
            ]);
        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            return false;
        }
    }

    public function eliminarLibro($id_libro) {
        try {
            $sql = "DELETE FROM libros_registro WHERE id_libro = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_libro]);
        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al eliminar libro: " . $e->getMessage());
            return false;
        }
    }
}