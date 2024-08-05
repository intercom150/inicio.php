<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Agregamos el campo de la contraseña
    $telefono = $_POST['telefono'];
    $fecha_registro = date('Y-m-d'); // Usamos la fecha actual
    $id_rol = 2; // El rol de proveedor es 2

    // Nota: Se recomienda hash para las contraseñas en entornos de producción
    $sql = "INSERT INTO usuario (nombre, email, fecha_registro, id_rol, password) VALUES ('$nombre', '$email', '$fecha_registro', $id_rol, '$password')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Proveedor registrado exitosamente";
        header("Location: provedores.php");
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Registrar Proveedor</h2>
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Registrar</button>
        </form>

    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>
