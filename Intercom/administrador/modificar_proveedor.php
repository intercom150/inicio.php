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
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE usuario SET nombre='$nombre', email='$email' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Proveedor actualizado exitosamente";
        header("Location: provedores.php");
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM usuario WHERE id=$id";
    $result = $conn->query($sql);
    $proveedor = $result->fetch_assoc();
}

$conn->close();
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Modificar Proveedor</h2>
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $proveedor['id']; ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $proveedor['nombre']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $proveedor['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $proveedor['telefono']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
        </form>
        <div class="mt-3">
            <p>¿No tienes una cuenta? <a href="login.html" class="btn btn-secondary">Iniciar Sesión</a></p>
        </div>
    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>
