
<?php
$usuario_id = $usuario['id'];
    $sql_productos = "SELECT * FROM productos WHERE usuario_id = $usuario_id";
    $result_productos = $conexion->query($sql_productos);

    // Mostrar los productos y sus cantidades
    while ($producto = $result_productos->fetch_assoc()) {
        echo "<strong>Producto:</strong> " . $producto['nombre_producto'] . " ";
        echo "<strong>Cantidad:</strong> " . $producto['cantidad'] . "<br>";

        // Obtener las adiciones de cada producto
        $producto_id = $producto['id'];
        $sql_adiciones = "SELECT * FROM adiciones WHERE producto_id = $producto_id";
        $result_adiciones = $conexion->query($sql_adiciones);

        // Mostrar las adiciones asociadas al producto
        if ($result_adiciones->num_rows > 0) {
            echo "<strong>Adiciones:</strong> ";
            $adiciones = [];
            while ($adicion = $result_adiciones->fetch_assoc()) {
                $adiciones[] = $adicion['nombre_adicion'];
            }
            echo implode(", ", $adiciones) . "<br>";
        } else {
            echo "<strong>Adiciones:</strong> Ninguna<br>";
        }
        echo "<hr>";
    }
    ?>