<?php
ob_start(); 

$tipo = $_REQUEST['tipo'] ?? ""; 
$conexao = mysqli_connect("localhost", "root", "", "web_shoppe");

switch ($tipo) {
    case 'usu':
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0; 

        if ($id > 0) {
            $sql_feedback = "DELETE FROM feedback WHERE id_usuario = ?";
            if ($stmt = $conexao->prepare($sql_feedback)) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
            }

            $sql_carrinho = "DELETE FROM carrinho WHERE cod_usuario = ?";
            if ($stmt = $conexao->prepare($sql_carrinho)) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
            }

            $sql_produto = "DELETE FROM produto WHERE id_loja IN (SELECT cod_loja FROM loja WHERE id_usu = ?)";
            if ($stmt = $conexao->prepare($sql_produto)) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
            }

            $sql_loja = "DELETE FROM loja WHERE id_usu = ?";
            if ($stmt = $conexao->prepare($sql_loja)) {
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
            }

            $sql = "DELETE FROM usuario WHERE cod_usuario = ?";
            if ($stmt = $conexao->prepare($sql)) {
                $stmt->bind_param('i', $id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    header("Location: /Web-Shoppe-main/dashboard%20eu/dashboard.php?msg=1&tipo_msg=remove&page=lista_usu.php");
                } else {
                    header("Location: /Web-Shoppe-main/dashboard%20eu/dashboard.php?msg=2&tipo_msg=remove&page=lista_usu.php");
                }

                $stmt->close();
            }
        }
        break;

    case 'prod':
        $id_prod = isset($_REQUEST['cod']) ? intval($_REQUEST['cod']) : 0; 

        if ($id_prod > 0) {
            $sql_destaque = "DELETE FROM produto WHERE cod_produto = ?";
            if ($stmt = $conexao->prepare($sql_destaque)) {
                $stmt->bind_param('i', $id_prod);
                $stmt->execute();
                $stmt->close();
            }

            $sql_feedback = "DELETE FROM feedback WHERE id_produto = ?";
            if ($stmt = $conexao->prepare($sql_feedback)) {
                $stmt->bind_param('i', $id_prod);
                $stmt->execute();
                $stmt->close();
            }

            $sql = "DELETE FROM produto WHERE cod_produto = ?";
            if ($stmt = $conexao->prepare($sql)) {
                $stmt->bind_param('i', $id_prod);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    header("Location: /Web-Shoppe-main/dashboard%20eu/dashboard.php?msg=1&tipo_msg=remove&page=lista_prod.php");
                } else {
                    header("Location: /Web-Shoppe-main/dashboard%20eu/dashboard.php?msg=2&tipo_msg=remove&page=lista_prod.php");
                }

                $stmt->close();
            }
        }
        break;
}

ob_end_flush(); 
