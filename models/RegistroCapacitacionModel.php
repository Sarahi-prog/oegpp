<?php
class RegistroCapacitacionModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function cargar() {
        // Usamos COALESCE o CONCAT dependiendo de tu DB (asumo PostgreSQL/SQLite por el ||)
        $sql = "SELECT rc.*, 
                       cl.nombres || ' ' || cl.apellidos AS nombre_cliente,
                       cl.dni,
                       cu.nombre_curso,
                       cu.tipo,
                       li.numero_libro AS nombre_libro
                FROM registros_capacitacion rc
                LEFT JOIN clientes cl ON rc.clientes_id = cl.id_cliente
                LEFT JOIN cursos cu ON rc.curso_id = cu.id_curso
                LEFT JOIN libros_registro li ON rc.libro_id = li.id_libro
                ORDER BY rc.id_registro DESC";

        $ps = $this->db->prepare($sql);
        $ps->execute();
        // CAMBIO: FETCH_OBJ para que funcione con la vista ($r->propiedad)
        return $ps->fetchAll(PDO::FETCH_OBJ);
    }

    // Métodos auxiliares para los SELECT del formulario
    public function obtenerTodosClientes() {
        $sql = "SELECT id_cliente, nombres, apellidos FROM clientes ORDER BY apellidos ASC";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_OBJ);
    }

    public function obtenerTodosCursos() {
        $sql = "SELECT id_curso, nombre_curso FROM cursos ORDER BY nombre_curso ASC";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_OBJ);
    }

    public function obtenerTodosLibros() {
        $sql = "SELECT id_libro, numero_libro FROM libros_registro ORDER BY numero_libro DESC";
        $ps = $this->db->prepare($sql);
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_OBJ);
    }

    public function guardar(RegistroCapacitacion $registro) {
        $sql = "INSERT INTO registros_capacitacion (clientes_id, curso_id, libro_id, registro, horas_realizadas, fecha_inicio, fecha_fin, fecha_emision, folio, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $ps = $this->db->prepare($sql);
        $ps->execute([
            $registro->getClienteId(),
            $registro->getCursoId(),
            $registro->getLibroId(),
            $registro->getRegistro(),
            $registro->getHorasRealizadas(),
            $registro->getFechaInicio(),
            $registro->getFechaFin(),
            $registro->getFechaEmision(),
            $registro->getFolio(),
            $registro->getEstado()
        ]);
    }
    
    // ... implementar modificar similar a guardar ...
}