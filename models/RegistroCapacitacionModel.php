<?php
require_once './config/DB.php'; 

class RegistroCapacitacionModel {
    private $conexion;
    public $ultimoError;

    public function __construct() {
        $this->conexion = DB::conectar();
        $this->ultimoError = null;
    }

    // 🔹 LISTAR REGISTROS
    public function cargar_registro() {
        try {
            $sql = "SELECT 
                rc.id_registro,
                rc.registro,
                cl.dni,
                cl.nombres || ' ' || cl.apellidos AS nombre_cliente,
                cs.nombre_curso,
                cs.tipo,
                cs.codigo_curso || '-' || rc.registro AS codigo_registro,
                lb.numero_libro,
                ('OEGPP-L' || lb.numero_libro) AS nombre_libro,
                rc.horas_realizadas,
                rc.fecha_inicio,
                rc.fecha_fin,
                rc.fecha_emision,
                rc.folio,
                rc.estado,
                rc.linkr
            FROM registros_capacitacion rc
            INNER JOIN clientes cl ON rc.clientes_id = cl.id_cliente
            INNER JOIN cursos cs ON rc.curso_id = cs.id_curso
            INNER JOIN libros_registro lb ON rc.libro_id = lb.id_libro
            ORDER BY rc.id_registro DESC";

            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            return [];
        }
    }

    // 🔹 GUARDAR REGISTRO
    public function guardar_registro($registro) {
        try {
            $sql = "INSERT INTO registros_capacitacion 
                    (clientes_id, curso_id, libro_id, registro, horas_realizadas, 
                    fecha_inicio, fecha_fin, fecha_emision, folio, estado)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conexion->prepare($sql);

            $stmt->execute([
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

            return $this->conexion->lastInsertId();

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al guardar registro: " . $e->getMessage());
            return null;
        }
    }

    // 🔹 MODIFICAR REGISTRO
    public function modificar_registro($registro) {
        try {
            $sql = "UPDATE registros_capacitacion SET 
                        clientes_id = ?, 
                        curso_id = ?, 
                        libro_id = ?, 
                        registro = ?, 
                        horas_realizadas = ?, 
                        fecha_inicio = ?, 
                        fecha_fin = ?, 
                        fecha_emision = ?, 
                        folio = ?, 
                        estado = ?
                    WHERE id_registro = ?";

            $stmt = $this->conexion->prepare($sql);

            return $stmt->execute([
                $registro->getClienteId(),
                $registro->getCursoId(),
                $registro->getLibroId(),
                $registro->getRegistro(),
                $registro->getHorasRealizadas(),
                $registro->getFechaInicio(),
                $registro->getFechaFin(),
                $registro->getFechaEmision(),
                $registro->getFolio(),
                $registro->getEstado(),
                $registro->getIdRegistro()
            ]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al modificar registro: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 ELIMINAR
    public function eliminar_registro($id_registro) {
        try {
            $sql = "DELETE FROM registros_capacitacion WHERE id_registro = ?";
            $stmt = $this->conexion->prepare($sql);
            return $stmt->execute([$id_registro]);

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            error_log("Error al eliminar registro: " . $e->getMessage());
            return false;
        }
    }

    // 🔹 BUSCAR POR DNI
    public function buscarPorDni($dni) {
        try {
            $sql = "SELECT rc.*, 
                           cl.nombres || ' ' || cl.apellidos AS nombre_cliente,
                           cl.dni,
                           cu.nombre_curso,
                           li.tipo || ' ' || li.numero_libro AS nombre_libro
                    FROM registros_capacitacion rc
                    LEFT JOIN clientes cl ON rc.clientes_id = cl.id_cliente
                    LEFT JOIN cursos cu ON rc.curso_id = cu.id_curso
                    LEFT JOIN libros_registro li ON rc.libro_id = li.id_libro
                    WHERE cl.dni = :dni
                    ORDER BY rc.fecha_emision DESC";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];

        } catch (PDOException $e) {
            $this->ultimoError = $e->getMessage();
            return [];
        }
    }
}
?>