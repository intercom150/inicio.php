<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

// Obtener todos los pedidos de la base de datos
$sql = "SELECT pedido.*, usuario.nombre AS usuario_nombre FROM pedido JOIN usuario ON pedido.id_usuario = usuario.id";
$result = $conn->query($sql);
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Lista de Pedidos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['fecha']}</td>
                                <td>{$row['usuario_nombre']}</td>
                                <td>{$row['total']}</td>
                                <td>
                                    <a href='borrar_pedido.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas borrar este pedido?\");'>Borrar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No hay pedidos registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'template/pie.php' ?>
</body>
</html>

<?php
$conn->close();
?>
