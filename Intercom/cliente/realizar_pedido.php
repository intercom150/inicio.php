<?php
include '../db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cantidades = $_POST['cantidad'];
    
    $total = 0;
    $pedido_items = [];
    
    // Calcular el total y preparar los datos para insertar en la base de datos
    foreach ($cantidades as $articulo_id => $cantidad) {
        if ($cantidad > 0) {
            $sql = "SELECT precio FROM articulos WHERE id=$articulo_id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $precio = $row['precio'];
                $subtotal = $precio * $cantidad;
                $total += $subtotal;
                $pedido_items[] = ['articulo_id' => $articulo_id, 'cantidad' => $cantidad, 'precio_unitario' => $precio, 'subtotal' => $subtotal];
            }
        }
    }
    
    if (!empty($pedido_items)) {
        // Insertar el pedido en la tabla pedido
        $sql = "INSERT INTO pedido (id_usuario, fecha_pedido, total, estado) VALUES ($user_id, NOW(), $total, 'Pendiente')";
        if ($conn->query($sql) === TRUE) {
            $pedido_id = $conn->insert_id;
            
            // Insertar los artículos del pedido en la tabla articulos_de_una_compra
            foreach ($pedido_items as $item) {
                $articulo_id = $item['articulo_id'];
                $cantidad = $item['cantidad'];
                $precio_unitario = $item['precio_unitario'];
                $sql = "INSERT INTO articulos_de_una_compra (id_pedido, id_articulo, cantidad, precio_unitario) VALUES ($pedido_id, $articulo_id, $cantidad, $precio_unitario)";
                $conn->query($sql);
                
                // Actualizar el stock del artículo
                $sql = "UPDATE articulos SET stock = stock - $cantidad WHERE id = $articulo_id";
                $conn->query($sql);
            }
            
            $message = "Pedido realizado exitosamente";
        } else {
            $message = "Error al realizar el pedido: " . $conn->error;
        }
    } else {
        $message = "No se seleccionaron artículos para el pedido";
    }
} else {
    $message = "Debe iniciar sesión para realizar un pedido";
}

$conn->close();
?>

<?php include 'cabecera.php' ?>
</head>
<body>
    <div class="container my-5">
        <?php if (isset($message)) { echo '<div class="alert alert-info">' . $message . '</div>'; } ?>
        <a href="cl.php" class="btn btn-primary">Volver a los artículos</a>
    </div>

    <?php include 'pie.php' ?>
</body>
</html>