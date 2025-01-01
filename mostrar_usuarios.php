<?php
include('conexion.php');

// Obtener la página actual desde la URL, por defecto será la página 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Número de usuarios por página
$usuariosPorPagina = 4;

// Calcular el OFFSET para la consulta SQL
$offset = ($page - 1) * $usuariosPorPagina;

// Contar el total de usuarios en la tabla
$totalUsuariosQuery = "SELECT COUNT(*) AS total FROM clientes";
$totalUsuariosResult = $conexion->query($totalUsuariosQuery);
$totalUsuariosRow = $totalUsuariosResult->fetch_assoc();
$totalUsuarios = $totalUsuariosRow['total'];

// Calcular el número total de páginas
$totalPaginas = ceil($totalUsuarios / $usuariosPorPagina);

// Obtener los usuarios para la página actual, ordenados por los más recientes primero
$sql = "SELECT * FROM clientes ORDER BY id DESC LIMIT $usuariosPorPagina OFFSET $offset";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Usuarios Registrados</h1>

<table border="1" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Barrio</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Productos y Adiciones</th>
            <th>Método de Pago</th>
            <th>Domicilio</th>
            <th>Valor Total</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($usuario = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= $usuario['nombre'] ?></td>
                <td><?= $usuario['barrio'] ?></td>
                <td><?= $usuario['direccion'] ?></td>
                <td><?= $usuario['telefono'] ?></td>
                <td>
                    <?php
                    // Código para mostrar productos y adiciones aquí
                    ?>
                </td>
                <td><?= $usuario['metodopago'] ?></td>
                <td><?= $usuario['domicilio'] ?></td>
                <td><?= $usuario['valortotal'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-primary">Editar</a>
                    <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" 
                       onclick="return confirm('¿Estás seguro de eliminar este usuario?');" 
                       class="btn btn-danger">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div style="margin-top: 20px;">
    <ul style="list-style: none; display: flex; gap: 10px; justify-content: center;">
        <?php if ($page > 1): ?>
            <li><a href="?page=<?= $page - 1 ?>" class="btn btn-secondary">Anterior</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li>
                <a href="?page=<?= $i ?>" class="btn <?= $i == $page ? 'btn-primary' : 'btn-secondary' ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPaginas): ?>
            <li><a href="?page=<?= $page + 1 ?>" class="btn btn-secondary">Siguiente</a></li>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>
