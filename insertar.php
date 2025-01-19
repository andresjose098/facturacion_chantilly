<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $nit = $_POST['nit'];
    $barrio = $_POST['barrio'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $productos = isset($_POST['productos']) ? $_POST['productos'] : [];
    $adiciones = isset($_POST['adiciones']) ? $_POST['adiciones'] : [];
    $cantidades = isset($_POST['cantidad']) ? $_POST['cantidad'] : [];
    $metodopago = $_POST['metodopago'];
    $domicilio = $_POST['domicilio'];
    $valortotal = $_POST['valortotal'];
    $cambio = $_POST['cambio'];

    // Validar consistencia de datos
    if (count($productos) !== count($cantidades)) {
        die("Error: Los productos y cantidades no coinciden.");
    }

    if (!$conexion->begin_transaction()) {
        die("No se pudo iniciar la transacción: " . $conexion->error);
    }

    try {
        // Insertar cliente
        $sql_cliente = "INSERT INTO clientes (nombre, nit, barrio, direccion, telefono, metodopago, domicilio, valortotal, cambio)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_cliente = $conexion->prepare($sql_cliente);
        $stmt_cliente->bind_param("sssssssss", $nombre, $nit, $barrio, $direccion, $telefono, $metodopago, $domicilio, $valortotal, $cambio);
        $stmt_cliente->execute();
        $usuario_id = $conexion->insert_id;

        // Preparar consultas para productos y adiciones
        $sql_producto = "INSERT INTO productos (usuario_id, nombre_producto, cantidad) VALUES (?, ?, ?)";
        $stmt_producto = $conexion->prepare($sql_producto);

        $sql_adicion = "INSERT INTO adiciones (producto_id, nombre_adicion) VALUES (?, ?)";
        $stmt_adicion = $conexion->prepare($sql_adicion);


      
        // Insertar productos y adiciones
        foreach ($productos as $index => $producto) {
            $cantidad = isset($cantidades[$index]) ? $cantidades[$index] : 1;
            $precio = isset($_POST['precio'][$index]) ? $_POST['precio'][$index] : 0; // Capturar el precio
        
            // Insertar producto con precio
            $sql_producto = "INSERT INTO productos (usuario_id, nombre_producto, cantidad, precio) VALUES (?, ?, ?, ?)";
            $stmt_producto = $conexion->prepare($sql_producto);
            $stmt_producto->bind_param("isid", $usuario_id, $producto, $cantidad, $precio);
            $stmt_producto->execute();
            $producto_id = $conexion->insert_id;
        
            // Insertar adiciones asociadas
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
     //   echo('<pre>')   ; 

//print_r($productos);
//print_r($adiciones);
//die();

        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
