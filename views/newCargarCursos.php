<?php requice_once 'menu.php'
?>

<div>
    <h1>Cargar Cursos</h1>
    <table>
        <tr>
            <th>ID Curso</th>
            <th>Código Curso</th>
            <th>Nombre Curso</th>
            <th>Horas Totales</th>
        </tr>
        <?php foreach($cursos as $c): ?>
        <tr>
            <td><?php echo $c->getid_curso(); ?></td>
            <td><?php echo $c->getCodigoCurso(); ?></td>
            <td><?php echo $c->getNombreCurso(); ?></td>
            <td><?php echo $c->getHorasTotales(); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>