<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_usuario = $_POST['id_usuario'];

    // Manejo de la imagen
    $imagen = $_FILES['imagen']['name'];
    if ($imagen) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($imagen);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $target_file);
        $sql = "UPDATE articulos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', stock='$stock', id_usuario='$id_usuario', imagen='$imagen' WHERE id=$id";
    } else {
        $sql = "UPDATE articulos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', stock='$stock', id_usuario='$id_usuario' WHERE id=$id";
    }
    
    if ($conn->query($sql) === TRUE) {
        $message = "Artículo actualizado exitosamente";
        header("Location: productos.php");
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM articulos WHERE id=$id";
    $result = $conn->query($sql);
    $articulo = $result->fetch_assoc();

    // Obtener la lista de usuarios para el formulario
    $sql_usuarios = "SELECT id, nombre FROM usuario";
    $result_usuarios = $conn->query($sql_usuarios);
}

$conn->close();
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Modificar Artículo</h2>
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $articulo['id']; ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $articulo['nombre']; ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo $articulo['descripcion']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $articulo['precio']; ?>" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $articulo['stock']; ?>" required>
            </div>
            <div class="form-group">
                <label for="id_usuario">Usuario</label>
                <select class="form-control" id="id_usuario" name="id_usuario" required>
                    <?php
                    if ($result_usuarios->num_rows > 0) {
                        while($row = $result_usuarios->fetch_assoc()) {
                            echo "<option value='{$row['id']}'" . ($row['id'] == $articulo['id_usuario'] ? " selected" : "") . ">{$row['nombre']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen (deja en blanco si no deseas cambiarla)</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>
    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>
