<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $fecha_registro = date('Y-m-d');
    $id_rol = 1;
    $password = ($_POST['password']);

    $sql = "INSERT INTO usuario (nombre, email, fecha_registro, id_rol, password) VALUES ('$nombre', '$email', '$fecha_registro', $id_rol, '$password')";

    if ($conn->query($sql) === TRUE) {
        $message = "Registro exitoso";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<?php include 'template/cabecera.php'?>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Registro de Usuario</h2>
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
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        <?php if (isset($message)) { echo '<div class="alert alert-info mt-3">' . $message . '</div>'; } ?>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <?php include 'template/pie.php'?>
