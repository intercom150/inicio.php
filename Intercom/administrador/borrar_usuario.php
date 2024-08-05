<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuario WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = "Usuario borrado exitosamente";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $message = "ID del usuario no proporcionado";
}

$conn->close();
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <a href="clientes.php" class="btn btn-primary">Volver a la lista de usuarios</a>
    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>
