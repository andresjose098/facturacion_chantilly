<?php
include('conexion.php');
include('header.php');

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
<!-- Navegación de Paginación -->
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
        <label for="barrio">Barrio:</label>
        <input type="text" class="form-control" id="barrio" name="barrio" placeholder="Ingrese el barrio" required>
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
    <select class="form-select" name="productos[${index}]" required>
        <option value="" disabled selected>Seleccione un producto</option>
        <option value=""></option>
                <option value="Merengon mixto">Merengon mixto</option>
                <option value="Merengon fresa">Merengon fresa</option>
                <option value="Merengon Guanabana">Merengon Guanabana</option>
                <option value="Merengon fresa y durazno">Merengon fresa y durazno</option>
                <option value="Merengon fresa guanabana">Merengon fresa guanabana</option>
                <option value="Merengon Durazno guanabana">Merengon Durazno guanabana</option>
                <option value="Merengon Oreo">Merengon Oreo</option>
                <option value="Merengon M&M">Merengon M&M</option>
                <option value="Merengon Milo">Merengon Milo</option>
                <option value="Merengon Fresas con crema">Fresas con crema</option>
                <option value="Porcion torta">Porcion de torta</option>
                <option value="Genovesa fresa y dur 1/4 $65.000">Genovesa fresa y dur 1/4 $65.000</option>
                <option value="Genovesa fresa y dur 1/2 $85.000">Genovesa fresa y dur 1/2 $85.000</option>
                <option value="Genovesa fresa 1/4 $65.000">Genovesa fresa 1/4 $65.000 </option>
                <option value="Genovesa fresa 1/2 $85.000">Genovesa fresa 1/2 $85.000</option>
                <option value="Genovesa durazno 1/4 $68.000">Genovesa durazno 1/4 $68.000 </option>
                <option value="Genovesa durazno 1/2  $88.000">Genovesa durazno 1/2  $88.000</option>
                <option value="Genovesa mora 1/4 $80.000">Genovesa mora 1/4 $80.000</option>
                <option value="Genovesa Milo 1/4 $60.000">Genovesa Milo 1/4 $60.000</option>
                <option value="Genovesa Milo 1/2 $80.000">Genovesa Milo 1/2 $80.000</option>
                <option value="Genovesa oreo 1/4 $60.000">Genovesa oreo 1/4 $60.000 </option>
                <option value="Genovesa oreo 1/2$80.000">Genovesa oreo 1/2 $80.000 </option>
                <option value="Genovesa caramelo areq 1/4 $60.000">Genovesa caramelo areq 1/4 $60.000 </option>
                <option value="Genovesa caramelo areq 1/2 $80.000">Genovesa caramelo areq 1/2 $80.000</option>
                <option value="Genovesa maracuyá 1/4 $60.000">Genovesa maracuyá 1/4 $60.000 </option>
                <option value="Genovesa maracuyá 1/2 $80.000">Genovesa maracuyá 1/2 $80.000 </option>
                <option value="Torta red velvet 1/4 $60.000">Torta red velvet 1/4 $60.000</option>
                <option value="Torta red velvet 1/2 $80.000">Torta red velvet 1/2 $80.000 </option>
                <option value="Rollo de fresa $80.000">Rollo de fresa $80.000 </option>

            </select>
    </select>
</div>

<div >
            <label>Cantidad:</label>
            <input type="number" name="cantidad[${index}]" class="form-control small-input" min="1" value="1" required style="width: 60px; font-size: 17px; padding: 2px; height: auto;" >
        </div>
<!-- Contenedor de Adiciones -->
<div class="adiciones" id="adiciones_${index}" style="margin-top: 10px;">
    <label>Adiciones:</label>
    <select class="form-select" name="adiciones[${index}][]" required>
    
        <option value="" disabled selected>Seleccione una adición</option>
        <option value="ninguna">ninguna</option>
        <option value="Salsa de arequipe">Salsa de arequipe</option>
        <option value="Salsa lechera">Salsa lechera</option>
        <option value="Salsa de fresa">Salsa de fresa</option>
        <option value="Salsa de chocolate">Salsa de chocolate</option>
        <option value="Chispas de chocolate">Chispas de chocolate</option>
        <option value="Crema Chantilly">Crema Chantilly</option>
        <option value="M&M">M&M</option>
        <option value="Milo">Milo</option>
        <option value="fruta fresa">Fruta fresa</option>
        <option value="fruta durazno">Fruta durazno</option>
        <option value="fruta guanabana">Fruta guanabana</option>
        <option value="Galleta oreo">Galleta oreo</option>
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
        <option></option>
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
    <label for="valortotal">Costo Total:</label>
    <input type="text" class="form-control" id="valortotal" name="valortotal" placeholder="Costo total" readonly>
</div>


    <div class="form-group">
        <label for="direccion">Paga:</label>
        <input type="text" class="form-control" id="cambio" name="cambio" placeholder="Ingrese el valor del pago " required>
    </div>

    
    <button type="submit" class="btn btn-primary">Registrar</button>
</form>

</body>

<script>
// Lista de precios por producto
const preciosProductos = {
    "Merengon mixto": 15000,
    "Merengon fresa": 15000,
    "Merengon Guanabana": 15000,
    "Merengon fresa y durazno": 15000,
    "Merengon fresa guanabana": 15000,
    "Merengon Durazno guanabana": 15000,
    "Merengon Oreo": 16000,
    "Merengon M&M": 17000,
    "Merengon Milo": 16000,
    "Fresas con crema": 16000,
    "Porcion torta": 12000,
    "Genovesa fresa y dur 1/4 $65.000": 65000,
    "Genovesa fresa y dur 1/2 $85.000": 85000,
    "Genovesa de fresa 1/4 $65.000": 65000,
    "Genovesa de fresa 1/2 $85.000": 85000,
    "Genovesa de durazno 1/4 $68.000": 68000,
    "Genovesa de durazno 1/2 $88.000": 88000,
    "Genovesa de mora 1/4 $60.000": 60000,
    "Genovesa de mora 1/2 $80.000": 80000,
    "Genovesa de Milo 1/4 $60.000": 60000,
    "Genovesa de Milo 1/2 $80.000": 80000,
    "Genovesa de oreo 1/4 $60.000": 60000,
    "Genovesa de oreo 1/2 $80.000": 80000,
    "Genovesa de caramelo arequipe 1/4 $60.000": 60000,
    "Genovesa de caramelo arequipe 1/2 $80.000": 80000,
    "Genovesa de maracuyá 1/4 $60.000": 60000,
    "Genovesa maracuyá 1/2 $80.000": 80000,
    "Torta red velvet 1/4 $60.000": 60000,
    "Torta red velvet 1/2 $80.000": 80000,
    "Rollo de fresa $80.000": 80000,
};

// Lista de precios por adición
const preciosAdiciones = {
    "ninguna":0,
    "Salsa de arequipe": 1000,
    "Salsa lechera": 1000,
    "Salsa de fresa": 1000,
    "Salsa de chocolate": 1000,
    "Chispas de chocolate": 2000,
    "Crema Chantilly": 3000,
    "M&M": 4000,
    "Milo": 3000,
    "fruta fresa": 3000,
    "fruta durazno": 4000,
    "fruta guanabana": 3000,
    "Galleta oreo": 4000,
};

// Función para calcular el costo total
function calcularCostoTotal() {
    const productosDivs = document.querySelectorAll(".producto");
    let totalProductos = 0;

    productosDivs.forEach((productoDiv, index) => {
        // Seleccionar elementos relevantes dentro de cada producto
        const productoSelect = productoDiv.querySelector(`select[name="productos[${index}]"]`);
        const cantidadInput = productoDiv.querySelector(`input[name="cantidad[${index}]"]`);
        const adicionSelects = productoDiv.querySelectorAll(`select[name="adiciones[${index}][]"]`);

        // Calcular precio del producto
        const productoSeleccionado = productoSelect ? productoSelect.value : null;
        const cantidad = cantidadInput ? parseInt(cantidadInput.value) || 1 : 1;
        let precioProducto = preciosProductos[productoSeleccionado] || 0;
        let precioTotalProducto = precioProducto * cantidad;

        // Calcular costo de las adiciones (dos primeras gratuitas)
        let costoAdiciones = 0;
        adicionSelects.forEach((select, i) => {
            if (select.value) {
                if (i >= 2) { // Cobrar solo a partir de la tercera adición
                    costoAdiciones += preciosAdiciones[select.value] || 0;
                }
            }
        });

        // Sumar adiciones al total del producto
        precioTotalProducto += costoAdiciones;

        // Acumular al total general
        totalProductos += precioTotalProducto;
    });

    // Obtener costo del domicilio
    const domicilioSelect = document.querySelector(`select[name="domicilio"]`);
    let costoDomicilio = 0;

    if (domicilioSelect && domicilioSelect.value) {
        // Convertir el valor del domicilio a número entero eliminando caracteres no numéricos
        costoDomicilio = parseInt(domicilioSelect.value.replace(/[$.]/g, "")) || 0;
    }

    // Calcular costo total (productos + domicilio)
    const costoTotal = totalProductos + costoDomicilio;

    // Mostrar el costo total en el campo correspondiente
    const valorTotalInput = document.querySelector(`input[name="valortotal"]`);
    if (valorTotalInput) {
        valorTotalInput.value = costoTotal.toLocaleString("es-CO");
    }
}


// Vincular eventos para recalcular el total automáticamente
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".form-select, .form-control").forEach(element => {
        element.addEventListener("change", calcularCostoTotal);
    });

    // Recalcular cuando se agregan productos
    document.getElementById("productos").addEventListener("change", calcularCostoTotal);
});



function calcularPrecio(index) {
    const productoSelect = document.querySelector(`select[name="productos[${index}]"]`);
    const adicionSelects = document.querySelectorAll(`select[name="adiciones[${index}][]"]`);
    const cantidadInput = document.querySelector(`input[name="cantidad[${index}]"]`);
    const precioInput = document.getElementById(`precio_${index}`);

    // Inicializar el precio con el valor del producto seleccionado
    let precioProducto = 0;
    if (productoSelect && productoSelect.value) {
        precioProducto = preciosProductos[productoSelect.value] || 0;
    }

    // Obtener la cantidad seleccionada
    const cantidad = parseInt(cantidadInput.value) || 1;

    // Calcular el precio base por cantidad
    let precioTotal = precioProducto * cantidad;

    // Calcular el costo de las adiciones (las dos primeras son gratuitas)
    let costoAdiciones = 0;
    adicionSelects.forEach((select, i) => {
        if (select.value) {
            if (i >= 2) { // Cobrar solo a partir de la tercera adición
                costoAdiciones += preciosAdiciones[select.value] || 0;
            }
        }
    });

    // Sumar el costo de las adiciones al precio total
    precioTotal += costoAdiciones;

    // Mostrar el precio calculado en el campo de precio
    precioInput.value = precioTotal;
}

// Vincular eventos de cambio para productos y adiciones
function agregarEventos(index) {
    const productoSelect = document.querySelector(`select[name="productos[${index}]"]`);
    const adicionSelects = document.querySelectorAll(`select[name="adiciones[${index}][]"]`);
    const cantidadInput = document.querySelector(`input[name="cantidad[${index}]"]`);

    // Recalcular precio cuando se selecciona un producto o cantidad
    if (productoSelect) {
        productoSelect.addEventListener("change", () => calcularPrecio(index));
    }
    if (cantidadInput) {
        cantidadInput.addEventListener("change", () => calcularPrecio(index));
    }

    // Recalcular precio cuando se selecciona o cambia una adición
    adicionSelects.forEach(select => {
        select.addEventListener("change", () => calcularPrecio(index));
    });
}

// Modificar agregarProducto para incluir eventos
function agregarProducto() {
    const contenedor = document.getElementById("productos");
    const index = document.querySelectorAll(".producto").length;

    // Crear un nuevo div para el producto
    const nuevoProducto = document.createElement("div");
    nuevoProducto.classList.add("producto");
    nuevoProducto.innerHTML = `
        <div>
            <label>Producto:</label>
            <select name="productos[${index}]" class="form-select" required>
                <option value="" disabled selected>Seleccione un producto</option>
                ${Object.keys(preciosProductos).map(producto => `<option value="${producto}">${producto}</option>`).join("")}
            </select>
        </div>

        <div>
            <label>Cantidad:</label>
            <input type="number" name="cantidad[${index}]" class="form-control" min="1" value="1" required style="width: 60px; font-size: 17px; padding: 2px; height: auto;">
        </div>

        <div class="adiciones" id="adiciones_${index}">
            <label>Adiciones:</label>
            <select name="adiciones[${index}][]" class="form-select" required>
                <option value="" disabled selected>Seleccione una adición</option>
                ${Object.keys(preciosAdiciones).map(adicion => `<option value="${adicion}">${adicion}</option>`).join("")}
            </select>
        </div>

        <div>
            <label>Precio:</label>
            <input type="text" name="precios[${index}]" class="form-control" id="precio_${index}" readonly style="width: 100px; font-size: 17px; padding: 2px; height: auto;" value="0">
        </div>

        <button type="button" class="btn btn-secondary" onclick="agregarAdicion(this, ${index})">Agregar Adición</button>
        <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">Cancelar Producto</button>
    `;

    contenedor.appendChild(nuevoProducto);
    agregarEventos(index);
}




function agregarAdicion(btn, index) {
    const adicionesDiv = document.getElementById(`adiciones_${index}`);
    if (!adicionesDiv) {
        console.error(`No se encontró el contenedor de adiciones para el índice ${index}`);
        return;
    }

    // Crear un nuevo selector de adiciones
    const nuevaAdicion = document.createElement("select");
    nuevaAdicion.name = `adiciones[${index}][]`;
    nuevaAdicion.classList.add("form-select");
    nuevaAdicion.required = true;
    nuevaAdicion.innerHTML = `
        <option value="" disabled selected>Seleccione una adición</option>
        ${Object.keys(preciosAdiciones).map(adicion => `<option value="${adicion}">${adicion}</option>`).join("")}
    `;

    // Agregar evento para recalcular precio al cambiar la nueva adición
    nuevaAdicion.addEventListener("change", () => calcularPrecio(index));

    // Añadir el nuevo selector al contenedor
    adicionesDiv.appendChild(nuevaAdicion);
}





function eliminarProducto(btn) {
    const productoDiv = btn.parentElement; // Div que contiene el producto y sus adiciones
    productoDiv.remove(); // Eliminar el producto
}

// Función para eliminar una adición
function eliminarAdicion(btn) {
    const adicionDiv = btn.parentElement; // Div que contiene la adición
    adicionDiv.remove(); // Eliminar la adición
}


// Buscar usuario por número de teléfono
$(document).ready(function () {
    $('#telefono').on('keyup', function () {
        var telefono = $(this).val(); // Obtener el valor del teléfono ingresado

        // Verificar si el campo no está vacío y tiene al menos 10 caracteres
        if (telefono.length >= 10) {
            $.ajax({
                url: 'buscar_usuario.php', // Archivo PHP para buscar datos
                method: 'POST',           // Método de solicitud
                data: { telefono: telefono }, // Datos enviados al servidor
                dataType: 'json',         // Esperar una respuesta en formato JSON
                success: function (response) {
                    if (response.status === 'success') {
                        // Rellenar los campos con los datos obtenidos
                        $('#nombre').val(response.data.nombre || '');
                        $('#direccion').val(response.data.direccion || '');
                        $('#barrio').val(response.data.barrio || '');
                    } else {
                        // Limpiar los campos si el usuario no es encontrado
                        $('#nombre').val('');
                        $('#direccion').val('');
                        $('#barrio').val('');
                        alert('Usuario no encontrado.');
                    }
                },
                error: function (xhr, status, error) {
                    // Manejar errores en la solicitud AJAX
                    console.error('Error al buscar el usuario:', error);
                    alert('Hubo un error al buscar el usuario. Por favor, intenta de nuevo.');
                }
            });
        } else {
            // Limpiar los campos si el teléfono no cumple con la longitud mínima
            $('#nombre').val('');
            $('#direccion').val('');
            $('#barrio').val('');
        }
    });
});
</script>
</html>