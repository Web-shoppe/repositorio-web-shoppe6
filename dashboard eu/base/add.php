<?php 
require_once "conexao.php";

$tipo = $_POST['tipo'] ?? '';

switch($tipo) {
    case 'usu':
        $nome = $_POST['nome'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $cpf = $_POST['cpf'] ?? '';
        $cep = $_POST['cep'] ?? '';
        $ativo = $_POST['ativo'] ?? 1;
        $nivel = $_POST['nivel'] ?? 1;
        $cadastro = $_POST['cadastro'] ?? date('Y-m-d');
        $tipo_usuario = $_POST['tipo_usuario'] ?? 'usuario'; 

        try {
            $select = "SELECT * FROM logradouro WHERE cep = ?";
            $stmt = $conexao->prepare($select);
            $stmt->bind_param('s', $cep);
            $stmt->execute();
            $result = $stmt->get_result();
            $rc = $result->num_rows;

            if ($rc == 0) {
                echo "CEP não encontrado.<br>";
            } else {
                $query = "INSERT INTO usuario (nome, senha, email, tel, cpf, cep, ativo, nivel, cadastro, tipo_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = $conexao->prepare($query)) {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt->bind_param('ssssssssss', $nome, $senha_hash, $email, $telefone, $cpf, $cep, $ativo, $nivel, $cadastro, $tipo_usuario);

                    if ($stmt->execute()) {
                        header("Location: ../dashboard.php?msg=1&tipo_msg=insert&page=lista_usu.php");
                        exit();
                    } else {
                        echo "Erro ao inserir: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Erro ao preparar consulta: " . $conexao->error;
                }
            }

            $stmt->close();
        } catch (Exception $i) {
            echo "Erro: " . $i->getMessage();
        }
        break;

    case 'prod':
        $nome = $_POST['nome'] ?? '';
        $descricao_produto = $_POST['descricao_produto'] ?? '';
        $foto_prod = $_FILES['foto_prod']['name'] ?? '';
        $valor_venda_produto = $_POST['valor_venda_produto'] ?? '';
        $valor_desconto = $_POST['valor_desconto'] ?? '';
        $qt_produto_estocado = $_POST['qt_produto_estocado'] ?? '';
        $fabricante_produto = $_POST['fabricante_produto'] ?? '';
        $garantia_produto = $_POST['garantia_produto'] ?? '';
        $qt_parcela = $_POST['qt_parcela'] ?? '';
        $id_loja = $_POST['id_loja'] ?? '';

        $target_dir = "caminho/para/imagens/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); 
        }

        if ($foto_prod) {
            move_uploaded_file($_FILES['foto_prod']['tmp_name'], $target_dir . basename($foto_prod));
        } else {
            $foto_prod = null; 
        }

        $select_loja = "SELECT cod_loja FROM loja WHERE cod_loja = ?";
        $stmt_loja = $conexao->prepare($select_loja);
        $stmt_loja->bind_param('s', $id_loja);
        $stmt_loja->execute();
        $stmt_loja->store_result();

        if ($stmt_loja->num_rows == 0) {
            echo "ID da loja não encontrado.";
        } else {
            $query = "INSERT INTO produto (nome, descricao_produto, foto_prod, valor_venda_produto, valor_desconto, qt_produto_estocado, fabricante_produto, garantia_produto, qt_parcela, id_loja) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = $conexao->prepare($query)) {
                $stmt->bind_param('ssssssssss', $nome, $descricao_produto, $foto_prod, $valor_venda_produto, $valor_desconto, $qt_produto_estocado, $fabricante_produto, $garantia_produto, $qt_parcela, $id_loja);

                if ($stmt->execute()) {
                    header("Location: ../dashboard.php?page=lista_prod.php&msg=1&tipo_msg=insert");
                    exit();
                } else {
                    echo "Erro ao inserir produto: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Erro ao preparar consulta: " . $conexao->error;
            }
        }

        $stmt_loja->close();
        break;

    default:
        header("Location: ../dashboard.php?msg=2");
        break;
}
?>
