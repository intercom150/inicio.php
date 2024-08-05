<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

// Obtener todos los proveedores (usuarios con el rol 2) de la base de datos
$sql = "SELECT * FROM usuario WHERE id_rol = 2";
$result = $conn->query($sql);
?>

<?php include 'template/cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Lista de Proveedores</h2>
        <a href="formProvedores.php" class="btn btn-primary mb-3">Registrar Proveedor</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['fecha_registro']}</td>
                                <td>
                                    <a href='modificar_proveedor.php?id={$row['id']}' class='btn btn-warning btn-sm'>Modificar</a>
                                    <a href='borrar_proveedor.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas borrar este proveedor?\");'>Borrar</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No hay proveedores registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
    <br>
    <br>
    <br>


    <?php include 'template/pie.php' ?>
</body>
</html>

<?php
$conn->close();
?>
