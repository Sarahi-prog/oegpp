<?php
require_once __DIR__ . '/../config/DB.php';
require_once __DIR__ . '/RegistroCapacitacion.php';

class RegistroCapacitacionModel {
    private $db;

    public function __construct() {
        $this->db = DB::conectar();
    }

    public function cargar() {
        $sql = "SELECT rc.*, 
                       cl.nombres || ' ' || cl.apellidos AS nombre_cliente,
                       cl.dni,
                       cu.nombre_curso,
                       li.tipo || ' ' || li.numero_libro AS nombre_libro
                FROM registros_capacitacion rc
                -- CORRECCIÓN: rc.clientes_id (con 's')
                LEFT JOIN clientes cl ON rc.clientes_id = cl.id_cliente
                LEFT JOIN cursos cu ON rc.curso_id = cu.id_curso
                LEFT JOIN libros_registro li ON rc.libro_id = li.id_libro
                ORDER BY rc.id_registro DESC";

        $ps = $this->db->prepare($sql);
        $ps->execute();
        $result = $ps->fetchAll(PDO::FETCH_ASSOC);
        return $result ? $result : [];
    }

    public function guardar(RegistroCapacitacion $registro) {
        // CORRECCIÓN: clientes_id (con 's')
        $sql = "INSERT INTO registros_capacitacion (
                    clientes_id,
                    curso_id,
                    libro_id,
                    registro,
                    horas_realizadas,
                    fecha_inicio,
                    fecha_fin,
                    fecha_emision,
                    folio
                ) VALUES (
                    :clientes_id,
                    :curso_id,
                    :libro_id,
                    :registro,
                    :horas_realizadas,
                    :fecha_inicio,
                    :fecha_fin,
                    :fecha_emision,
                    :folio
                )";

        $ps = $this->db->prepare($sql);

        $cliente_id = $registro->getClienteId(); // Mantengo el método de tu clase
        $curso_id = $registro->getCursoId();
        $libro_id = $registro->getLibroId();
        $registro_num = $registro->getRegistro();
        $horas_realizadas = $registro->getHorasRealizadas();
        
        $fecha_inicio = $registro->getFechaInicio() === '' ? null : $registro->getFechaInicio();
        $fecha_fin = $registro->getFechaFin() === '' ? null : $registro->getFechaFin();
        $fecha_emision = $registro->getFechaEmision() === '' ? null : $registro->getFechaEmision();
        
        $folio = $registro->getFolio();

        // CORRECCIÓN: bindParam a :clientes_id
        $ps->bindParam(':clientes_id', $cliente_id);
        $ps->bindParam(':curso_id', $curso_id);
        $ps->bindParam(':libro_id', $libro_id);
        $ps->bindParam(':registro', $registro_num);
        $ps->bindParam(':horas_realizadas', $horas_realizadas);
        $ps->bindParam(':fecha_inicio', $fecha_inicio);
        $ps->bindParam(':fecha_fin', $fecha_fin);
        $ps->bindParam(':fecha_emision', $fecha_emision);
        $ps->bindParam(':folio', $folio);
        $ps->execute();
    }

    public function modificar(RegistroCapacitacion $registro) {
        // CORRECCIÓN: clientes_id (con 's')
        $sql = "UPDATE registros_capacitacion SET 
                    clientes_id = :clientes_id,
                    curso_id = :curso_id,
                    libro_id = :libro_id,
                    registro = :registro,
                    horas_realizadas = :horas_realizadas,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin,
                    fecha_emision = :fecha_emision,
                    folio = :folio
                WHERE id_registro = :id_registro";

        $ps = $this->db->prepare($sql);

        $id_registro = $registro->getIdRegistro();
        $cliente_id = $registro->getClienteId();
        $curso_id = $registro->getCursoId();
        $libro_id = $registro->getLibroId();
        $registro_num = $registro->getRegistro();
        $horas_realizadas = $registro->getHorasRealizadas();
        
        $fecha_inicio = $registro->getFechaInicio() === '' ? null : $registro->getFechaInicio();
        $fecha_fin = $registro->getFechaFin() === '' ? null : $registro->getFechaFin();
        $fecha_emision = $registro->getFechaEmision() === '' ? null : $registro->getFechaEmision();
        
        $folio = $registro->getFolio();

        $ps->bindParam(':id_registro', $id_registro);
        // CORRECCIÓN: bindParam a :clientes_id
        $ps->bindParam(':clientes_id', $cliente_id);
        $ps->bindParam(':curso_id', $curso_id);
        $ps->bindParam(':libro_id', $libro_id);
        $ps->bindParam(':registro', $registro_num);
        $ps->bindParam(':horas_realizadas', $horas_realizadas);
        $ps->bindParam(':fecha_inicio', $fecha_inicio);
        $ps->bindParam(':fecha_fin', $fecha_fin);
        $ps->bindParam(':fecha_emision', $fecha_emision);
        $ps->bindParam(':folio', $folio);
        $ps->execute();
    }

    public function buscarPorDni($dni) {
        $sql = "SELECT rc.*, 
                       cl.nombres || ' ' || cl.apellidos AS nombre_cliente,
                       cl.dni,
                       cu.nombre_curso,
                       li.tipo || ' ' || li.numero_libro AS nombre_libro
                FROM registros_capacitacion rc
                -- CORRECCIÓN: rc.clientes_id (con 's')
                LEFT JOIN clientes cl ON rc.clientes_id = cl.id_cliente
                LEFT JOIN cursos cu ON rc.curso_id = cu.id_curso
                LEFT JOIN libros_registro li ON rc.libro_id = li.id_libro
                WHERE cl.dni = :dni
                ORDER BY rc.fecha_emision DESC";

        $ps = $this->db->prepare($sql);
        $ps->bindParam(':dni', $dni, PDO::PARAM_STR);
        $ps->execute();
        
        return $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>