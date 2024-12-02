<?php
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['cod_produto'] ?? '';
    $nome = $_POST['nome_prod'] ?? '';
    $valorCompra = $_POST['valor_compra_produto'] ?? '';
    $fabricante = $_POST['fabricante_produto'] ?? '';
    $garantia = $_POST['tempo_garantia_produto'] ?? '';
    $estoque = $_POST['qt_produto_estocado'] ?? '';
    $valorVenda = $_POST['valor_venda_produto'] ?? '';

    if (!$id) {
        die("ID do produto não fornecido.");
    }

    $fotoNome = '';
    if (!empty($_FILES['foto_prod']['name'])) {
        $fotoTmpNome = $_FILES['foto_prod']['tmp_name'];
        $fotoNome = $_FILES['foto_prod']['name'];
        $fotoDestino = "../uploads/" . $fotoNome;
        
        if (!move_uploaded_file($fotoTmpNome, $fotoDestino)) {
            die("Erro ao fazer upload da foto.");
        }

        $sql = "SELECT foto FROM produto WHERE cod_produto = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();
        $stmt->close();

        if ($produto && !empty($produto['foto'])) {
            $fotoAntiga = "../uploads/" . $produto['foto'];
            if (file_exists($fotoAntiga)) {
                unlink($fotoAntiga);
            }
        }
    }

    $sql = "UPDATE produto SET nome = ?, valor_compra_produto = ?, fabricante_produto = ?, tempo_garantia_produto = ?, qt_produto_estocado = ?, valor_venda_produto = ?" . (!empty($fotoNome) ? ", foto = ?" : "") . " WHERE cod_produto = ?";
    $stmt = $conexao->prepare($sql);

    if (!empty($fotoNome)) {
        $stmt->bind_param('ssssiisi', $nome, $valorCompra, $fabricante, $garantia, $estoque, $valorVenda, $fotoNome, $id);
    } else {
        $stmt->bind_param('ssssiis', $nome, $valorCompra, $fabricante, $garantia, $estoque, $valorVenda, $id);
    }

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?page=lista_prod.php");
        exit();
    } else {
        echo "Erro ao atualizar o produto: " . $stmt->error;
    }
    $stmt->close();

    $conexao->close();
} else {
    die("Método de solicitação inválido.");
}
?>
