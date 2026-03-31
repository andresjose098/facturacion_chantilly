<?php
require_once('tcpdf/tcpdf.php'); // Incluye la librería TCPDF

include('conexion.php');  // Conexión a la base de datos

$id = $_GET['id'];  // Recuperar el ID del cliente desde la URL

$sql = "SELECT * FROM clientes WHERE id = ?";  // Consultar los datos del cliente desde la base de datos
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("No se encontró el usuario.");
}

$nombre = $usuario['nombre'];
$nit = $usuario['nit'];
$barrio = $usuario['barrio'];
$direccion = $usuario['direccion'];
$telefono = $usuario['telefono'];
$metodopago = $usuario['metodopago'];
$domicilio = $usuario['domicilio'];
$valortotal = $usuario['valortotal'];
$cambio = $usuario['cambio'];

$sql_productos = "SELECT * FROM productos WHERE usuario_id = ?";  // Consultar los productos asociados al cliente
$stmt_productos = $conexion->prepare($sql_productos);
$stmt_productos->bind_param("i", $id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

$total_cantidad = 0;  // Variable para acumular el total de cantidades
$pdf = new TCPDF();  // Crear un objeto TCPDF

$pdf->SetMargins(3, 3, 3);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Tienda');
$pdf->SetTitle('Registro de Cliente');
$pdf->SetSubject('Datos de Cliente');
$pdf->SetKeywords('Cliente, PDF, Tienda');

$pdf->AddPage();  // Agregar una página al PDF
$pdf->SetFont('helvetica', '', 9);
$pdf->setListIndentWidth(0);

// Sección cabecera con interlineado normal
$html_cabecera = '
    <h2 style="text-align: left; margin-left: 20px; font-size: 12px; margin-bottom: 2px;">Chantilly Pastelería Artesanal</h2>
    <p style="text-align: left; margin-left: 20px; font-size: 8px; margin: 0; padding: 0;">nit 1112486866-1</p>
    <p style="text-align: left; margin-left: 20px; font-size: 8px; margin: 0; padding: 0;">Whatsapp 3185212067</p>
    <h1 style="text-align: left; margin-left: 20px; font-size: 13px; margin-top: 4px; margin-bottom: 2px;">Registro de Usuario</h1>
    <table style="width: 100%; border-collapse: collapse; margin-left: 20px; font-size: 9px;" cellpadding="1">
        <tr>
            <td style="width: 20%;"><strong>Nombre:</strong></td>
            <td style="width: 60%;">' . htmlspecialchars($nombre) . '</td>
        </tr>
        <tr>
            <td style="width: 20%;"><strong>NIT:</strong></td>
            <td style="width: 60%;">' . htmlspecialchars($nit) . '</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong></td>
            <td>' . htmlspecialchars($direccion) . '</td>
        </tr>
        <tr>
            <td><strong>Barrio:</strong></td>
            <td>' . htmlspecialchars($barrio) . '</td>
        </tr>
        <tr>
            <td><strong>Teléfono:</strong></td>
            <td>' . htmlspecialchars($telefono) . '</td>
        </tr>
        <tr>
            <td><strong>Método de Pago:</strong></td>
            <td>' . htmlspecialchars($metodopago) . '</td>
        </tr>
        <tr>
            <td><strong>Domicilio:</strong></td>
            <td>' . htmlspecialchars($domicilio) . '</td>
        </tr>
        <tr>
            <td><strong>Valor Total:</strong></td>
            <td>' . htmlspecialchars($valortotal) . '</td>
        </tr>
        <tr>
            <td><strong>Paga:</strong></td>
            <td>' . htmlspecialchars($cambio) . '</td>
        </tr>
    </table>
    <hr style="margin: 2px 0;">
    <h2 style="text-align: left; margin-left: 0px; font-size: 10px; margin-bottom: 2px;">Productos y Adiciones:</h2>
';

$pdf->writeHTML($html_cabecera, true, false, true, false, '');

// Sección de productos con interlineado reducido
$pdf->setCellHeightRatio(1.2);
$pdf->SetFont('helvetica', '', 8);

$html = '<table cellpadding="0" cellspacing="0" border="0" style="font-size: 8px;">';

$contador = 1;
while ($producto = $result_productos->fetch_assoc()) {
    $cantidad = (int)$producto['cantidad'];
    $total_cantidad += $cantidad;

    $html .= '<tr><td><strong>Cantidad:</strong> ' . $cantidad . '</td></tr>';
    $html .= '<tr><td><strong>' . $contador . '. Producto:</strong> ' . htmlspecialchars($producto['nombre_producto']) . '</td></tr>';
    $html .= '<tr><td><strong>Adiciones:</strong> ';

    $producto_id = $producto['id'];
    $sql_adiciones = "SELECT nombre_adicion FROM adiciones WHERE producto_id = ?";
    $stmt_adiciones = $conexion->prepare($sql_adiciones);
    $stmt_adiciones->bind_param("i", $producto_id);
    $stmt_adiciones->execute();
    $result_adiciones = $stmt_adiciones->get_result();

    if ($result_adiciones->num_rows > 0) {
        $adiciones = [];
        while ($adicion = $result_adiciones->fetch_assoc()) {
            $adiciones[] = htmlspecialchars($adicion['nombre_adicion']);
        }
        $html .= implode(", ", $adiciones);
    } else {
        $html .= 'Ninguna';
    }

    $html .= '</td></tr>';
    $html .= '<tr><td style="border-bottom: 1px solid #ccc; font-size: 1px;">&nbsp;</td></tr>';
    $contador++;
}

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Restaurar interlineado normal para el pie
$pdf->setCellHeightRatio(1.25);

$html_pie = '
<hr style="margin: 2px 0;">
<h3 style="margin-left: 0px;"><strong>Total de Cantidades:</strong> ' . $total_cantidad . '</h3>
<p style="margin-left: 0px;"><strong>¡Gracias por confiar en nosotros!</strong></p>
<p style="margin-left: 0px;">UrbanSoft, empresa dedicada</p>
<p style="margin-left: 0px;">al desarrollo de software.</p>
<p style="margin-left: 0px;">WhatsApp: 3165155249</p>
';

$pdf->writeHTML($html_pie);
$pdf->Output('registro_usuario_' . $id . '.pdf', 'I');  // 'I' indica que se abrirá en el navegador


?>
