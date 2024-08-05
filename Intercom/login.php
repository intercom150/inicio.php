<?php
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Nota: Usar contraseñas en texto claro no es seguro.
    $sql = "SELECT id, password, id_rol FROM usuario WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifica la contraseña en texto claro (solo para demostración).
        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_role'] = $row['id_rol'];
            
            // Redirigir al usuario según su rol
            switch ($row['id_rol']) {
                case 1:
                    header("Location: cliente/cl.php");
                    break;
                case 2:
                    header("Location: provedor/productos.php");
                    break;
                case 3:
                    header("Location: administrador/clientes.php");
                    break;
                default:
                    $message = "Rol desconocido";
                    break;
            }
            exit();
        } else {
            $message = "Contraseña incorrecta";
        }
    } else {
        $message = "Usuario no encontrado";
    }
}

$conn->close();
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Iniciar Sesión</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
        <?php if (isset($message)) { echo '<div class="alert alert-info mt-3">' . $message . '</div>'; } ?>
        <div class="mt-3">
            <p>¿No tienes una cuenta? <a href="registrarte.php" class="btn btn-secondary">Regístrate aquí</a></p>
        </div>
    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>