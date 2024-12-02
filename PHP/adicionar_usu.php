<?php 
session_start(); 
require_once "../base/conex.php"; 
require_once "../base/config.php";   

$conexao = new mysqli("localhost", "root", "", "web_shoppe");


$nome = $_POST['nome'] ?? '';
$senha = $_POST['senha'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$cep = $_POST['cep'] ?? '';
$ativo = $_POST['ativo'] ?? 1;
$nivel = $_POST['nivel'] ?? 1;
$cadastro = $_POST['cadastro'] ?? date('Y-m-d');

$query = "INSERT INTO usuario (nome, senha, email, tel, cpf, cep, ativo, nivel, cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conexao->prepare($query)) {

    $stmt->bind_param('sssssssss', $nome, $senha, $email, $telefone, $cpf, $cep, $ativo, $nivel, $cadastro);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Erro ao inserir: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Erro ao preparar consulta: " . $conexao->error;
}
?>
