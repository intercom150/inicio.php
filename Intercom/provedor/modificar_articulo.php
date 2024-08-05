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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Manejo de la imagen
    $imagen = $_FILES['imagen']['name'];
    if ($imagen) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
        $sql = "UPDATE articulos SET nombre=?, descripcion=?, precio=?, stock=?, id_usuario=?, imagen=? WHERE id=?";
    } else {
        $sql = "UPDATE articulos SET nombre=?, descripcion=?, precio=?, stock=?, id_usuario=? WHERE id=?";
    }
    
    $stmt = $conn->prepare($sql);
    if ($imagen) {
        $stmt->bind_param("ssdiisi", $nombre, $descripcion, $precio, $stock, $id_usuario, $imagen, $id);
    } else {
        $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $id_usuario, $id);
    }

    if ($stmt->execute()) {
        $message = "Artículo actualizado exitosamente";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM articulos WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $articulo = $result->fetch_assoc();

    // Obtener la lista de usuarios para el formulario
    $sql_usuarios = "SELECT id, nombre FROM usuario";
    $result_usuarios = $conn->query($sql_usuarios);

    $stmt->close();
}

$conn->close();
?>

<?php include 'template/cabecera.php'; ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Modificar Artículo</h2>
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($articulo['id']); ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($articulo['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo htmlspecialchars($articulo['descripcion']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($articulo['precio']); ?>" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($articulo['stock']); ?>" required>
            </div>
       
            <div class="form-group">
                <label for="imagen">Imagen (deja en blanco si no deseas cambiarla)</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>
    </div>

    <?php include 'template/pie.php'; ?>
</body>
</html>


