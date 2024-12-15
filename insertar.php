<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $barrio = $_POST['barrio'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $productos = isset($_POST['productos']) ? $_POST['productos'] : [];
    $cantidades = isset($_POST['cantidad']) ? $_POST['cantidad'] : [];
    $adiciones = isset($_POST['adiciones']) ? $_POST['adiciones'] : [];
    $metodopago = $_POST['metodopago'];
    $domicilio = $_POST['domicilio'];
    $valortotal = $_POST['valortotal'];
    $cambio = $_POST['cambio'];

    // Insertar el cliente en la tabla 'clientes'
    $sql_cliente = "INSERT INTO clientes (nombre, barrio, direccion, telefono, metodopago, domicilio, valortotal, cambio) 
                    VALUES ('$nombre', '$barrio', '$direccion', '$telefono', '$metodopago', '$domicilio', '$valortotal', '$cambio')";
    if ($conexion->query($sql_cliente) === TRUE) {
        // Obtener el último ID insertado del cliente
        $usuario_id = $conexion->insert_id;

        // Insertar los productos asociados al cliente
        foreach ($productos as $index => $producto) {
            $cantidad = isset($cantidades[$index]) ? $cantidades[$index] : 1; // Default cantidad a 1 si no está definida
            $sql_producto = "INSERT INTO productos (usuario_id, nombre_producto, cantidad) 
                             VALUES ('$usuario_id', '$producto', '$cantidad')";
            if ($conexion->query($sql_producto) === TRUE) {
                $producto_id = $conexion->insert_id;

                // Insertar las adiciones asociadas al producto
                if (isset($adiciones[$index])) {
                    foreach ($adiciones[$index] as $adicion) {
                        $sql_adicion = "INSERT INTO adiciones (producto_id, nombre_adicion) 
                                        VALUES ('$producto_id', '$adicion')";
                        $conexion->query($sql_adicion);
                    }
                }
            }
        }

        // Redirigir al index.php después de la inserción
        header("Location: index.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conexion->error;
    }
}
?>
