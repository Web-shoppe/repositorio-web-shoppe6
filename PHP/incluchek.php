<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "web_shoppe");
if (!$con) {
    die("Erro de conexão: " . mysqli_connect_error());
}

$usuario_id = $_SESSION['UsuarioID'] ?? null;
if (!$usuario_id) {
    die("Você precisa estar logado para finalizar a compra.");
}


$subtotal = $_GET['subtotal'] ?? 0;
$frete = $_GET['frete'] ?? 0;
$total = $subtotal + $frete;
$produtos = $_GET['produtos'] ?? '';


$sql = "INSERT INTO reservas (cod_usuario, subtotal, frete, total, produtos) VALUES (?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("iddss", $usuario_id, $subtotal, $frete, $total, $produtos);

if ($stmt->execute()) {
    echo "Reserva realizada com sucesso!";
} else {
    echo "Erro ao realizar a reserva: " . $stmt->error;
}

$stmt->close();
$con->close();
?>
