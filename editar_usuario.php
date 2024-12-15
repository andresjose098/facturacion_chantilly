<?php
include('conexion.php');
include('header.php');
// Verificar si se recibió un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del usuario
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    // Verificar si el usuario existe
    if (!$usuario) {
        die("Usuario no encontrado.");
    }

    // Procesar la edición del usuario
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];

        $sql_update = "UPDATE clientes SET nombre = ?, direccion = ?, telefono = ? WHERE id = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("sssi", $nombre, $direccion, $telefono, $id);

        if ($stmt_update->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error al actualizar el usuario.";
        }
    }
} else {
    die("ID no especificado.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body style="background-image: url('images/chantilly.jpg');
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
    padding:40px;">
    <h1>Editar Usuario</h1>
    <form action="" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>" required>
        <br>
        <label for="direccion">Dirección:</label>
        <input type="text" name="direccion" value="<?= $usuario['direccion'] ?>" required>
        <br>
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" value="<?= $usuario['telefono'] ?>" required>
        <br>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
