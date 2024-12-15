<?php
require_once('tcpdf/tcpdf.php'); // Incluye la librería TCPDF

include('conexion.php');  // Conexión a la base de datos

// Recuperar el ID del cliente desde la URL (por ejemplo: generar_pdf.php?id=1)
$id = $_GET['id'];

// Consultar los datos del cliente desde la base de datos
$sql = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("No se encontró el usuario.");
}

// Obtener los datos del cliente
$nombre = $usuario['nombre'];
$direccion = $usuario['direccion'];
$telefono = $usuario['telefono'];
$metodopago = $usuario['metodopago'];
$domicilio = $usuario['domicilio'];
$valortotal = $usuario['valortotal'];

// Consultar los productos asociados al cliente
$sql_productos = "SELECT * FROM productos WHERE usuario_id = ?";
$stmt_productos = $conexion->prepare($sql_productos);
$stmt_productos->bind_param("i", $id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

// Crear un objeto TCPDF
$pdf = new TCPDF();

// Establecer los márgenes y otras configuraciones del PDF
$pdf->SetMargins(15, 15, 15);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Tienda');
$pdf->SetTitle('Registro de Cliente');
$pdf->SetSubject('Datos de Cliente');
$pdf->SetKeywords('Cliente, PDF, Tienda');

// Agregar una página al PDF
$pdf->AddPage();

// Configurar la fuente
$pdf->SetFont('helvetica', '', 12);

// Crear contenido del PDF (HTML)
$html = '
    <h2>Chantilly Pastelería Artesanal</h2>
    <h1>Registro de Usuario</h1>
    <strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '<br>
    <strong>Dirección:</strong> ' . htmlspecialchars($direccion) . '<br>
    <strong>Teléfono:</strong> ' . htmlspecialchars($telefono) . '<br>
    <strong>Método de Pago:</strong> ' . htmlspecialchars($metodopago) . '<br>
    <strong>Domicilio:</strong> ' . htmlspecialchars($domicilio) . '<br>
    <strong>Valor Total:</strong> ' . htmlspecialchars($valortotal) . '<br>
    <hr>
    <h2>Productos y Adiciones:</h2>
    <ul>
';

// Agregar los productos y sus adiciones al contenido
while ($producto = $result_productos->fetch_assoc()) {
    $html .= '<li><strong>Producto:</strong> ' . htmlspecialchars($producto['nombre_producto']) . '<ul>';
    
    // Consultar las adiciones asociadas al producto actual
    $producto_id = $producto['id'];
    $sql_adiciones = "SELECT nombre_adicion FROM adiciones WHERE producto_id = ?";
    $stmt_adiciones = $conexion->prepare($sql_adiciones);
    $stmt_adiciones->bind_param("i", $producto_id);
    $stmt_adiciones->execute();
    $result_adiciones = $stmt_adiciones->get_result();

    // Agregar las adiciones como sub-lista
    if ($result_adiciones->num_rows > 0) {
        while ($adicion = $result_adiciones->fetch_assoc()) {
            $html .= '<li>' . htmlspecialchars($adicion['nombre_adicion']) . '</li>';
        }
    } else {
        $html .= '<li>Ninguna</li>';
    }

    $html .= '</ul></li><br>';
}

$html .= '
    </ul>
    <hr>
    <p><strong>Gracias por confiar en nosotros!</strong></p>
    <p>UrbanSoft empresa dedicada al desarrollo del software!</p>
    <p>Whatsapp 3165155249</p>
';

// Escribir el HTML en el PDF
$pdf->writeHTML($html);

// Generar el PDF y mostrarlo en el navegador
$pdf->Output('registro_usuario_' . $id . '.pdf', 'I');  // 'I' indica que se abrirá en el navegador
?>
