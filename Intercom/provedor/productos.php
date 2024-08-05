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
$id_user = $_SESSION['user_id'];

// Preparar y ejecutar la consulta con el ID del usuario desde la sesión
$sql = "SELECT articulos.*, usuario.nombre AS usuario_nombre 
        FROM articulos 
        JOIN usuario ON articulos.id_usuario = usuario.id 
        WHERE articulos.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'template/cabecera.php'; ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Lista de Artículos</h2>
        <a href="registrar_articulo.php" class="btn btn-primary mb-3">Registrar Artículo</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Usuario</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['descripcion']}</td>
                                <td>{$row['precio']}</td>
                                <td>{$row['stock']}</td>
                                <td>{$row['usuario_nombre']}</td>
                                <td><img src='uploads/{$row['imagen']}' alt='{$row['nombre']}' width='100'></td>
                                <td>
                                    <a href='modificar_articulo.php?id={$row['id']}' class='btn btn-warning btn-sm'>Modificar</a>
                                    <a href='borrar_articulo.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas borrar este artículo?\");'>Borrar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No hay artículos registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php include 'template/pie.php'; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
