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

// Consulta para obtener pedidos que contienen artículos de este usuario
$sql = "
    SELECT DISTINCT p.id AS pedido_id, p.fecha_pedido, p.total, p.estado
    FROM pedido p
    JOIN articulos_de_una_compra adc ON p.id = adc.id_pedido
    JOIN articulos a ON adc.id_articulo = a.id
    WHERE a.id_usuario = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<?php include 'template/cabecera.php'; ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Mis Pedidos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Fecha de Pedido</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['pedido_id']}</td>
                                <td>{$row['fecha_pedido']}</td>
                                <td>{$row['total']}</td>
                                <td>{$row['estado']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No hay pedidos para tus productos</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'template/pie.php'; ?>
</body>
</html>
