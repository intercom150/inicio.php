<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la imagen del artículo para poder eliminarla del servidor
    $sql = "SELECT imagen FROM articulos WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagen = $row['imagen'];
        $target_file = "uploads/" . $imagen;
        
        // Eliminar la imagen del servidor si existe
        if (file_exists($target_file)) {
            unlink($target_file);
        }

        // Eliminar el artículo de la base de datos
        $sql = "DELETE FROM articulos WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            $message = "Artículo borrado exitosamente";
           
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $message = "Artículo no encontrado";
    }
} else {
    $message = "ID del artículo no proporcionado";
}

$conn->close();
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <a href="productos.php" class="btn btn-primary">Volver a la lista de artículos</a>
    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>

