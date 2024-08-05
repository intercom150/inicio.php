<?php
include '../db.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM usuario WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    $message = "Proveedor borrado exitosamente";
} else {
    $message = "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: provedores.php");
exit();
?>
