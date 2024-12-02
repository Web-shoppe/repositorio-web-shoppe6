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

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$cep = $_POST['cep'] ?? '';
$estado = $_POST['estado'] ?? '';
$bairro = $_POST['bairro'] ?? '';
$endereco = $_POST['endereco'] ?? '';
$numero = $_POST['numero'] ?? '';
$metodoPagamento = $_POST['metodoPagamento'] ?? '';
$subtotal = 100; 
$frete = 10; 
$total = $subtotal + $frete;
$produtos = 'Nome do Produto'; 


$sql = "INSERT INTO reservas (cod_usuario, nome, email, cpf, telefone, cep, estado, bairro, endereco, numero, metodo_pagamento, subtotal, frete, total, produtos) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("isssssssssdidds", $usuario_id, $nome, $email, $cpf, $telefone, $cep, $estado, $bairro, $endereco, $numero, $metodoPagamento, $subtotal, $frete, $total, $produtos);

if ($stmt->execute()) {
    echo "Reserva realizada com sucesso!";
} else {
    echo "Erro ao realizar a reserva: " . $stmt->error;
}

$stmt->close();
$con->close();
?>
