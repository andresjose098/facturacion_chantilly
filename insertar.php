<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $barrio = $_POST['barrio'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $productos = isset($_POST['productos']) ? $_POST['productos'] : [];
    $adiciones = isset($_POST['adiciones']) ? $_POST['adiciones'] : [];
    $cantidades = isset($_POST['cantidad']) ? $_POST['cantidad'] : []; // Capturar las cantidades
    $metodopago = $_POST['metodopago'];
    $domicilio = $_POST['domicilio'];
    $valortotal = $_POST['valortotal'];
    $cambio = $_POST['cambio'];

    $conexion->begin_transaction();

    try {
        // Insertar el cliente
        $sql_cliente = "INSERT INTO clientes (nombre, barrio, direccion, telefono, metodopago, domicilio, valortotal, cambio)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_cliente = $conexion->prepare($sql_cliente);
        $stmt_cliente->bind_param("ssssssss", $nombre, $barrio, $direccion, $telefono, $metodopago, $domicilio, $valortotal, $cambio);
        $stmt_cliente->execute();
        $usuario_id = $conexion->insert_id;

        // Insertar productos y sus respectivas adiciones
        foreach ($productos as $index => $producto) {
            $cantidad = isset($cantidades[$index]) ? $cantidades[$index] : 1; // Obtener cantidad o asignar valor predeterminado de 1

            // Insertar producto
            $sql_producto = "INSERT INTO productos (usuario_id, nombre_producto, cantidad) VALUES (?, ?, ?)";
            $stmt_producto = $conexion->prepare($sql_producto);
            $stmt_producto->bind_param("isi", $usuario_id, $producto, $cantidad);
            $stmt_producto->execute();
            $producto_id = $conexion->insert_id;

            // Validar y asignar adiciones para el producto actual
            if (isset($adiciones[$index]) && is_array($adiciones[$index])) {
                foreach ($adiciones[$index] as $adicion) {
                    $sql_adicion = "INSERT INTO adiciones (producto_id, nombre_adicion) VALUES (?, ?)";
                    $stmt_adicion = $conexion->prepare($sql_adicion);
                    $stmt_adicion->bind_param("is", $producto_id, $adicion);
                    $stmt_adicion->execute();
                }
            }
        }

        $conexion->commit();
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
