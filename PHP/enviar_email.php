<?php

$dbHost = "localhost";      
$dbUsername = "root";      
$dbPassword = "";          
$dbName = "Web_shoppe";      


$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}


$nome = $_POST['nome'];
$email = $_POST['email'];
$produto = $_POST['produto'];
$total = floatval(str_replace('R$', '', str_replace('.', '', $total))); 
$cpf = $_POST['cpf']; 
$telefone = $_POST['telefone']; 
$cep = $_POST['cep']; 
$estado = $_POST['estado']; 
$bairro = $_POST['bairro']; 
$endereco = $_POST['endereco']; 
$numero = $_POST['numero']; 
$metodo_pagamento = $_POST['metodo_pagamento']; 
$cod_usuario = $_POST['cod_usuario']; 

$sql = "INSERT INTO compras (cod_usuario, subtotal, frete, total, produtos, nome, email, cpf, telefone, cep, estado, bairro, endereco, numero, metodo_pagamento) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("idsssssssssssss", $cod_usuario, $subtotal, $frete, $total, $produto, $nome, $email, $cpf, $telefone, $cep, $estado, $bairro, $endereco, $numero, $metodo_pagamento);

$subtotal = 100.00; 
$frete = 10.00; 

if ($stmt->execute()) {

    $to = $email;
    $subject = "Confirmação de Compra";
    $message = "Olá $nome,\n\nSua compra foi confirmada com sucesso! Detalhes:\n\nProduto: $produto\nTotal: R$ $total\n\nObrigado por comprar conosco!";
    $headers = "From: noreply@seudominio.com"; 

    mail($to, $subject, $message, $headers);

    echo "Compra finalizada e e-mail enviado.";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
