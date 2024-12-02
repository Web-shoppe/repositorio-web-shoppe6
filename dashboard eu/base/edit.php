<?php 
require_once "conexao.php";

$id = $_POST['id'] ?? '';
$usuario = $_POST['usuario'] ?? '';
$nome = $_POST['usuario'] ?? '';
$senha = $_POST['senha'] ?? '';
$email = $_POST['email'] ?? '';
$nivel = $_POST['nivel'] ?? '';
$ativo = $_POST['ativo'] ?? '';
$cidade = $_POST['cidade'] ?? '';
$cadastro = $_POST['cadastro'] ?? '';

$sql = "UPDATE usuario SET nome = ?, senha = ?, email = ?, nivel = ?, ativo = ?, cadastro = ? WHERE cod_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('ssssisi', $nome, $senha, $email, $nivel, $ativo, $cadastro, $id);

if ($stmt->execute()) {
    header("Location: ../dashboard.php?msg=1&tipo_msg=update&page=lista_usu.php");
} else {
    header("Location: ../dashboard.php?msg=2");
}

$stmt->close();
?>

<?php

require_once "conexao.php";

$id = $_POST['cod_produto'] ?? ''; 
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao_produto'] ?? '';
$foto = $_POST['foto_prod'] ?? '';
$valorVenda = $_POST['valor_venda_produto'] ?? '';
$valorDesconto = $_POST['valor_desconto'] ?? '';
$qtEstoque = $_POST['qt_produto_estocado'] ?? '';
$fabricante = $_POST['fabricante_produto'] ?? '';
$garantia = $_POST['garantia_produto'] ?? '';

$sql = "UPDATE produto SET nome = ?, descricao_produto = ?, foto_prod = ?, valor_venda_produto = ?, valor_desconto = ?, qt_produto_estocado = ?, fabricante_produto = ?, garantia_produto = ? WHERE cod_produto = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('ssssssssi', $nome, $descricao, $foto, $valorVenda, $valorDesconto, $qtEstoque, $fabricante, $garantia, $id);

if ($stmt->execute()) {
    header("Location: ../dashboard.php?msg=1&tipo_msg=update&page=lista_prod.php");
} else {
    header("Location: ../dashboard.php?msg=2");
}

$stmt->close();

?>

