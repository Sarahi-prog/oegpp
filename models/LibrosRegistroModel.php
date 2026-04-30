<?php
require_once './config/DB.php';
require_once 'LibrosRegistro.php';

class LibrosRegistroModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function cargar() {
        // CORREGIDO: Consulta directa a la tabla para evitar el error de "función no existe"
        $sql = "SELECT * FROM libros_registro ORDER BY id_libro DESC";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        $filas = $ps->fetchAll();
        
        $libros = array();
        foreach ($filas as $f) {
            $lib = new LibrosRegistro();
            $lib->setIdLibro($f['id_libro']);
            $lib->setTipo($f['tipo']);
            $lib->setNumeroLibro($f['numero_libro']);
            $lib->setAnioInicio($f['anio_inicio']);
            $lib->setFechaFin($f['fecha_fin']);
            $lib->setDistrito($f['distrito'] ?? null); // Usamos el nombre de la columna por seguridad
            $lib->setProvincia($f['provincia'] ?? null);
            $lib->setDescripcion($f['descripcion'] ?? null);
            array_push($libros, $lib);
        }
        return $libros;
    }

    public function guardar(LibrosRegistro $libro) {
        $sql = "INSERT INTO libros_registro (tipo, numero_libro, anio_inicio, fecha_fin, distrito, provincia, descripcion) 
                VALUES (:t, :nl, :ai, :ff, :dt, :pv, :dc)";
        
        $ps = $this->db->prepare($sql);
        
        // Asignamos a variables para bindParam
        $t  = $libro->getTipo();
        $nl = $libro->getNumeroLibro();
        $ai = $libro->getAnioInicio();
        $ff = $libro->getFechaFin();
        $dt = $libro->getDistrito();
        $pv = $libro->getProvincia();
        $dc = $libro->getDescripcion();

        $ps->bindParam(":t", $t);
        $ps->bindParam(":nl", $nl);
        $ps->bindParam(":ai", $ai);
        $ps->bindParam(":ff", $ff);
        $ps->bindParam(":dt", $dt);
        $ps->bindParam(":pv", $pv);
        $ps->bindParam(":dc", $dc);
        
        return $ps->execute();
    }

    public function modificar(LibrosRegistro $libro) {
        $sql = "UPDATE libros_registro SET 
                tipo=:t, numero_libro=:nl, anio_inicio=:ai, fecha_fin=:ff, 
                distrito=:dt, provincia=:pv, descripcion=:dc 
                WHERE id_libro=:id";
        
        $ps = $this->db->prepare($sql);
        
        $id = $libro->getIdLibro();
        $t  = $libro->getTipo();
        $nl = $libro->getNumeroLibro();
        $ai = $libro->getAnioInicio();
        $ff = $libro->getFechaFin();
        $dt = $libro->getDistrito();
        $pv = $libro->getProvincia();
        $dc = $libro->getDescripcion();

        $ps->bindParam(":id", $id);
        $ps->bindParam(":t", $t);
        $ps->bindParam(":nl", $nl);
        $ps->bindParam(":ai", $ai);
        $ps->bindParam(":ff", $ff);
        $ps->bindParam(":dt", $dt);
        $ps->bindParam(":pv", $pv);
        $ps->bindParam(":dc", $dc);

        return $ps->execute();
    }
}