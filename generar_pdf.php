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
$barrio=$usuario['barrio'];
$direccion = $usuario['direccion'];
$telefono = $usuario['telefono'];
$metodopago = $usuario['metodopago'];
$domicilio = $usuario['domicilio'];
$valortotal = $usuario['valortotal'];
$cambio=$usuario['cambio'];

// Consultar los productos asociados al cliente
$sql_productos = "SELECT * FROM productos WHERE usuario_id = ?";
$stmt_productos = $conexion->prepare($sql_productos);
$stmt_productos->bind_param("i", $id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

// Crear un objeto TCPDF
$pdf = new TCPDF();

// Establecer los márgenes y otras configuraciones del PDF
$pdf->SetMargins(5, 5, 5);
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
   <h2 style="text-align: left; margin: 0;">Chantilly Pastelería Artesanal</h2>
   <p style="text-align: left; margin: 0;">Whatsapp 3185212067</p>
   <h1 style="text-align: left; margin: 10px 0;">Registro de Usuario</h1>
   <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: left;">
        <tr>
            <td style="width: 20%; text-align: left; padding: 5px 0;"><strong>Nombre:</strong></td>
            <td style="width: 100%; text-align: left; padding: 5px 0;">' . htmlspecialchars($nombre) . '</td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 5px 0;"><strong>Dirección:</strong></td>
            <td style="text-align: left; padding: 5px 0;">' . htmlspecialchars($direccion) . '</td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 5px 0;"><strong>Barrio:</strong></td>
            <td style="text-align: left; padding: 5px 0;">' . htmlspecialchars($barrio) . '</td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 5px 0;"><strong>Teléfono:</strong></td>
            <td style="text-align: left; padding: 5px 0;">' . htmlspecialchars($telefono) . '</td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 5px 0;"><strong>Método de Pago:</strong></td>
            <td style="text-align: left; padding: 5px 0;">' . htmlspecialchars($metodopago) . '</td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 5px 0;"><strong>Domicilio:</strong></td>
            <td style="text-align: left; padding: 5px 0;">' . htmlspecialchars($domicilio) . '</td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 5px 0;"><strong>Valor Total:</strong></td>
            <td style="text-align: left; padding: 5px 0;">' . htmlspecialchars($valortotal) . '</td>
        </tr>
        <tr>
            <td style="text-align: left; padding: 5px 0;"><strong>Paga:</strong></td>
            <td style="text-align: left; padding: 5px 0;">' . htmlspecialchars($cambio) . '</td>
        </tr>
   </table>
   <hr style="margin: 10px 0;">
   <h2 style="text-align: left; margin: 10px 0;">Productos y Adiciones:</h2>
   <ul style="list-style: none; padding-left: 0; text-align: left;">
';

// Agregar los productos y sus adiciones al contenido
$contador = 1;
while ($producto = $result_productos->fetch_assoc()) {
    $html .= '<li style="text-align: left; margin-bottom: 10px;">
                <strong>' . $contador . '. Producto:</strong> ' . htmlspecialchars($producto['nombre_producto']) . '
                <ul style="list-style: none; padding-left: 20px; margin: 5px 0;">';
    
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
            $html .= '<li style="text-align: left; margin-left: 20px;">- ' . htmlspecialchars($adicion['nombre_adicion']) . '</li>';
        }
    } else {
        $html .= '<li style="text-align: left; margin-left: 20px;">- Ninguna</li>';
    }

    $html .= '</ul>
              </li>';
}

$html .= '
   </ul>
   <hr style="margin: 10px 0;">
   <p style="text-align: left; margin: 5px 0;"><strong>Gracias por confiar en nosotros!</strong></p>
   <p style="text-align: left; margin: 5px 0;">UrbanSoft empresa dedicada</p> 
   <p style="text-align: left; margin: 5px 0;">al desarrollo del software!</p>
   <p style="text-align: left; margin: 5px 0;">Whatsapp 3165155249</p>
   <strong style="text-align: left;">Feliz navidad</strong>
';

// Escribir el HTML en el PDF
$pdf->writeHTML($html);

// Generar el PDF y mostrarlo en el navegador
$pdf->Output('registro_usuario_' . $id . '.pdf', 'I');  // 'I' indica que se abrirá en el navegador
