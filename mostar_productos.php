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
            <th>Acciones</th>
            <th>Acciones</th>
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
                    // Obtener los productos del usuario
                    $usuario_id = $usuario['id'];
                  // Consulta para obtener productos y adiciones
            $sql_productos = "
                  SELECT p.id AS producto_id, p.nombre_producto, p.cantidad, a.nombre_adicion
                  FROM productos p
                  LEFT JOIN adiciones a ON p.id = a.producto_id
                  WHERE p.usuario_id = ?
                  ORDER BY p.id
                ";
                  


// Preparar y ejecutar la consulta
// Preparar y ejecutar la consulta
$stmt_productos = $conexion->prepare($sql_productos);
if (!$stmt_productos) {
    die("Error al preparar la consulta de productos: " . $conexion->error);
}

$stmt_productos->bind_param("i", $usuario_id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

if (!$result_productos) {
    die("Error en la consulta de productos y adiciones: " . $conexion->error);
}

// Inicializar array para almacenar productos y sus adiciones
// Inicializar array para almacenar productos y sus adiciones
// Inicializar array para almacenar productos y sus adiciones
$productos = [];
$total_cantidad = 0; // Variable para acumular la cantidad total

// Procesar resultados
while ($row = $result_productos->fetch_assoc()) {
    $producto_id = $row['producto_id'];

    // Si el producto no existe en el array, inicializarlo
    if (!isset($productos[$producto_id])) {
        $productos[$producto_id] = [
            'nombre_producto' => $row['nombre_producto'],
            'cantidad' => $row['cantidad'], // Incluir la cantidad
            'adiciones' => [],
        ];

        // Sumar la cantidad al total
        $total_cantidad += (int)($row['cantidad'] ?? 0);

    }

    // Agregar la adición si existe
    if (!empty($row['nombre_adicion'])) {
        $productos[$producto_id]['adiciones'][] = $row['nombre_adicion'];
    }
}

// Mostrar los resultados
foreach ($productos as $producto) {
    echo "<strong>Producto:</strong> " . htmlspecialchars($producto['nombre_producto']) . "<br>";
    echo "<strong>Cantidad:</strong> " . htmlspecialchars($producto['cantidad']) . "<br>";
    echo "<strong>Adiciones:</strong> ";
    if (!empty($producto['adiciones'])) {
        echo htmlspecialchars(implode(", ", $producto['adiciones']));
    } else {
        echo "Ninguna";
    }
    echo "<br><hr>";
}

// Mostrar la cantidad total
echo "<strong>Total de Cantidades:</strong> " . htmlspecialchars($total_cantidad) . "<br>";



// Debugging: Para verificar los datos procesados
// echo "<pre>";
// print_r($productos);
// echo "</pre>";

                    ?>
                </td>
                <td><?= $usuario['metodopago'] ?></td>
                <td><?= $usuario['domicilio'] ?></td>
                <td><?= $usuario['valortotal'] ?></td>
                <td><?= $usuario['cambio'] ?></td>
                
                <td>
                    <!-- Botón para editar usuario -->
                    <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-primary">Editar</a>
                    <!-- Botón para eliminar usuario -->
                </td>
                <td>
                    <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" 
                       onclick="return confirm('¿Estás seguro de eliminar este usuario?');" 
                       class="btn btn-danger">Eliminar</a>
                </td>
                
                <td>
                    <a href="generar_pdf.php?id=<?= $usuario['id'] ?>" target="_blank" class="btn btn-danger">Generar PDF</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>