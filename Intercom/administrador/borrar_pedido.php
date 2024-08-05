<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el pedido de la base de datos
    $sql = "DELETE FROM pedido WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = "Pedido borrado exitosamente";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $message = "ID del pedido no proporcionado";
}

$conn->close();
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <a href="pedidos.php" class="btn btn-primary">Volver a la lista de pedidos</a>
    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>
