<?php
session_start(); 
require_once "../base/conex.php"; 

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];
    $usuario_id = $_SESSION['UsuarioID'] ?? ""; 

    if (!empty($usuario_id)) {
        $conn = new mysqli("localhost", "root", "", "web_shoppe");

        
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        
        $sql = "SELECT quantidade FROM carrinho WHERE cod_prod = ? AND cod_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_produto, $usuario_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($quantidade_atual);
            $stmt->fetch();
            $nova_quantidade = $quantidade_atual + 1;

            $sql_update = "UPDATE carrinho SET quantidade = ? WHERE cod_prod = ? AND cod_usuario = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("iii", $nova_quantidade, $id_produto, $usuario_id);

            if ($stmt_update->execute()) {
                header("Location: carrinho.php");
                exit();
            } else {
                echo "Erro ao atualizar a quantidade do produto: " . $stmt_update->error;
            }

            $stmt_update->close();
        } else {
            $sql_insert = "INSERT INTO carrinho (cod_prod, cod_usuario, quantidade) VALUES (?, ?, 1)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ii", $id_produto, $usuario_id);

            if ($stmt_insert->execute()) {
                header("Location: carrinho.php");
                exit();
            } else {
                echo "Erro ao adicionar produto ao carrinho: " . $stmt_insert->error;
            }

            $stmt_insert->close();
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Você precisa estar logado para adicionar produtos ao carrinho.";
    }
} else {
    echo "ID do produto não fornecido.";
}
?>
