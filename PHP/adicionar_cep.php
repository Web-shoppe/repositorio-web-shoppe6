<?php 
session_start(); 
require_once "../base/conex.php"; 
require_once "../base/config.php";   

$conexao = new mysqli("localhost", "root", "", "web_shoppe");

$cep = $_POST['cep'] ?? '';
$rua = $_POST['rua'] ?? '';
$bairro = $_POST['bairro'] ?? '';
$numero = $_POST['numero'] ?? '';
$complemento = $_POST['complemento'] ?? '';
$cidade = $_POST['cidade'] ?? '';

$query = "INSERT INTO logradouro (cep, rua, bairro, numero, complemento, cidade) VALUES (?, ?, ?, ?, ?, ?)";

if ($stmt = $conexao->prepare($query)) {
    $stmt->bind_param('ssssss', $cep, $rua, $bairro, $numero, $complemento, $cidade); 

    if ($stmt->execute()) {
        $ultimo_id = $conexao->insert_id; 
        $_SESSION['ultimo_id'] = $ultimo_id; 

        header("Location: fcadastro.php");
        exit();
    } else {
        echo "Erro ao inserir: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Erro ao preparar consulta: " . $conexao->error;
}
?>
