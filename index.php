<?php
include('conexion.php');
include('header.php');

// Obtener todos los usuarios registrados
$sql = "SELECT * FROM clientes";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="style.css"> <!-- Asegúrate de tener tu archivo CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluimos jQuery -->
    <script src="script.js">
        // Función para agregar más productos al formulario
    // Función para agregar un nuevo producto con su campo de adiciones
// Función para agregar un nuevo producto con su campo de adiciones
</script>
    
</head>

<body style="background-image: url('images/chantilly.jpg');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
    padding:40px;">

<h1>Usuarios Registrados</h1>

<!-- Tabla de usuarios registrados -->
<table border="1" class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Productos y Adiciones</th>
            <th>Método de Pago</th>
            <th>Domicilio</th>
            <th>Valor Total</th>
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
                <td><?= $usuario['direccion'] ?></td>
                <td><?= $usuario['telefono'] ?></td>
                <td>
                    <?php
                    // Obtener los productos del usuario
                    $usuario_id = $usuario['id'];
                    $sql_productos = "SELECT * FROM productos WHERE usuario_id = $usuario_id";
                    $result_productos = $conexion->query($sql_productos);

                    // Mostrar los productos y sus adiciones
                    while ($producto = $result_productos->fetch_assoc()) {
                        echo "<strong>Producto:</strong> " . $producto['nombre_producto'] . "<br>";

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
                </td>
                <td><?= $usuario['metodopago'] ?></td>
                <td><?= $usuario['domicilio'] ?></td>
                <td><?= $usuario['valortotal'] ?></td>
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


<!-- Formulario para insertar nuevos usuarios -->
<h2>Registrar Nuevo Usuario</h2>
<form action="insertar.php" method="POST">
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingrese el teléfono" required>
    </div>
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre" required>
    </div>
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingrese la dirección" required>
    </div>

    <div id="productos">
    <label for="productos" class="form-label">Productos y Adiciones:</label>
    <div class="producto" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <!-- Select de Productos -->
        <div>
            <label>Producto:</label>
            <select class="form-select" name="productos[]" required>
                <option value="Merengon mixto">Merengon mixto</option>
                <option value="Merengon fresa">Merengon fresa</option>
                <option value="Merengon Guanabana">Merengon Guanabana</option>
                <option value="Merengon fresa y durazno">Merengon fresa y durazno</option>
                <option value="Merengon fresa guanabana">Merengon fresa guanabana</option>
                <option value="Merengon Durazno guanabana">Merengon Durazno guanabana</option>
                <option value="Merengon Oreo">Merengon Oreo</option>
                <option value="Merengon M&M">Merengon M&M</option>
                <option value="Merengon Milo">Merengon Milo</option>
                <option value="Merengon Fresas con crema">Merengon Fresas con crema</option>
            </select>
        </div>

        <!-- Contenedor de Adiciones -->
        <div class="adiciones" style="display: flex; gap: 10px; align-items: center;">
            <label>Adiciones:</label>
            <select class="form-select" name="adiciones[0][]" required>
                <option value="Arequipe">Arequipe</option>
                <option value="Lechera">Lechera</option>
                <option value="Fresa">Fresa</option>
                <option value="Chocolate">Chocolate</option>
                <option value="Chispas de chocolate">Chispas de chocolate</option>
                <option value="Crema Chantilly">Crema Chantilly</option>
            </select>
        </div>

        <!-- Botón para agregar más adiciones -->
        <button type="button" class="btn btn-secondary" onclick="agregarAdicion(this)">Agregar Adición</button>
        <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">Cancelar Producto</button>

    </div>
</div>

<!-- Botones para agregar productos y enviar formulario -->
<div style="display: flex; gap: 10px; margin-top: 10px;">
    <button type="button" class="btn btn-danger" onclick="agregarProducto()">Agregar Producto</button>
</div>







  
    <div class="form-group">
        <label for="metodopago">Metodo pago:</label>
        <select class="form-select" id="sel1" name="metodopago">
        <option>Nequi</option>
        <option>Bancolombia</option>
        <option>Efectivo</option>
        <option>DaviPlata</option>
    </select>
    </div>
    
    <div class="form-group">
        <label for="direccion">Domicilio:</label>
        <select class="form-select" id="sel1" name="domicilio">
        <option>$4.000</option>
        <option>$5.000</option>
        <option>$6.000</option>
        <option>$7.000</option>
        <option>$8.000</option>
        <option>$9.000</option>
        <option>$10.000</option>
        <option>$11.000</option>
        <option>$12.000</option>
        <option>$13.000</option>
        <option>$14.000</option>
        <option>$15.000</option>
                </select>
    </div>
    
    <div class="form-group">
        <label for="direccion">Valor total producto:</label>
        <input type="text" class="form-control" id="domicilio" name="valortotal" placeholder="Ingrese el valor total " required>
    </div>

    
    <button type="submit" class="btn btn-primary">Registrar</button>
</form>

</body>
</html>
