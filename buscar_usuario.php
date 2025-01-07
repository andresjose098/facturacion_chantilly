<?php
include('conexion.php');

// Verificar si se recibió el número de teléfono
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telefono'])) {
    $telefono = $_POST['telefono'];

    // Consultar los datos del usuario por teléfono
    $sql = "SELECT nombre, direccion, barrio FROM clientes WHERE telefono = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $telefono);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Devolver los datos en formato JSON
        echo json_encode([
            'status' => 'success',
            'data' => $usuario
        ]);
    } else {
        // Usuario no encontrado
        echo json_encode([
            'status' => 'error',
            'message' => 'Usuario no encontrado.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Teléfono no proporcionado.'
    ]);
}
?>
