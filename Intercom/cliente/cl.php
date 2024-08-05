<?php
include '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Obtener todos los artículos de la base de datos
$sql = "SELECT * FROM articulos";
$result = $conn->query($sql);
?>

<?php include 'cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Lista de Artículos Disponibles</h2>
        <form action="realizar_pedido.php" method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Imagen</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['nombre']}</td>
                                    <td>{$row['descripcion']}</td>
                                    <td>{$row['precio']}</td>
                                    <td>{$row['stock']}</td>
                                   <td><img src='provedor/uploads/{$row['imagen']}' alt='{$row['nombre']}' style='width: 100px; height: auto;'></td>
                                    <td><input type='number' name='cantidad[{$row['id']}]' min='0' max='{$row['stock']}' class='form-control'></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No hay artículos disponibles</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Realizar Pedido</button>
        </form>
    </div>

    <?php include 'pie.php' ?>
</body>
</html>

<?php
$conn->close();
?>