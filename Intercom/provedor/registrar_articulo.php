

<?php
session_start(); // Inicia la sesión

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 2) {
    header("Location: ../login.php");
    exit();
}

include '../db.php';

// Verifica si el ID del usuario está en la sesión
if (!isset($_SESSION['user_id'])) {
    die("No has iniciado sesión.");
}

// Obtén el ID del usuario desde la sesión
$id_usuario = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['imagen'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Manejo de la imagen
    $imagen = $_FILES['imagen']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($imagen);
    move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);

    // Usar consultas preparadas para evitar inyecciones SQL
    $sql = "INSERT INTO articulos (nombre, descripcion, precio, stock, id_usuario, imagen) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $id_usuario, $imagen);

    if ($stmt->execute()) {
        $message = "Artículo registrado exitosamente";
        header("Location: productos.php");
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>

<?php include 'template/cabecera.php'; ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Registrar Artículo</h2>
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" required>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Registrar</button>
        </form>
    </div>

    <?php include 'template/pie.php'; ?>
</body>
</html>
