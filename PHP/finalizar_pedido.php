<?php
$conn = mysqli_connect("localhost", "root", "", "web_shoppe");

if (!$conn) {
    die("Erro de conexão: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeCompleto = $_POST['nome-completo'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $bairro = $_POST['bairro'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $metodoPagamento = $_POST['metodo-pagamento'];

    $dadosPagamento = '';
    if ($metodoPagamento == 'cartao') {
        $numeroCartao = $_POST['numero-cartao'];
        $nomeTitular = $_POST['nome-titular'];
        $validadeCartao = $_POST['validade-cartao'];
        $cvv = $_POST['cvv'];
        $dadosPagamento = "Cartão: $numeroCartao, Titular: $nomeTitular, Validade: $validadeCartao, CVV: $cvv";
    } elseif ($metodoPagamento == 'pix') {
        $chavePix = $_POST['chave-pix'];
        $dadosPagamento = "Pix: $chavePix";
    } elseif ($metodoPagamento == 'boleto') {
        $dadosPagamento = "Boleto: método selecionado";
    }

    session_start();
    $usuario_id = $_SESSION['UsuarioID'] ?? null;
    if (!$usuario_id) {
        die("Você precisa estar logado para finalizar a compra.");
    }

    $sqlUltimaCompra = "
        SELECT cod_compra, lojas
        FROM compras 
        WHERE cod_usuario = ? 
        ORDER BY cod_compra DESC 
        LIMIT 1
    ";
    $stmt = $conn->prepare($sqlUltimaCompra);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ultimaCompra = $result->fetch_assoc();
    $cod_compra = $ultimaCompra['cod_compra'] ?? null;
    $cod_loja = $ultimaCompra['lojas'] ?? null;

    if (!$cod_compra || !$cod_loja) {
        die("Nenhuma compra encontrada para este usuário.");
    }

    $cod_loja_array = json_decode($cod_loja, true); 

    if (is_array($cod_loja_array)) {
        foreach ($cod_loja_array as $loja) {
            $sqlInserirPedido = "
                INSERT INTO pedidos (cod_compra, nome_completo, email, cpf, telefone, cep, estado, bairro, endereco, numero, metodo_pagamento, dados_pagamento, cod_loja) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
    
            if ($stmtInserir = $conn->prepare($sqlInserirPedido)) {
                $stmtInserir->bind_param(
                    "iisssssssssss",
                    $cod_compra,
                    $nomeCompleto,
                    $email,
                    $cpf,
                    $telefone,
                    $cep,
                    $estado,
                    $bairro,
                    $endereco,
                    $numero,
                    $metodoPagamento,
                    $dadosPagamento,
                    $loja 
                );
    
                if ($stmtInserir->execute()) {
                } else {
                    $_SESSION['mensagem'] = "Erro ao registrar pedido para a loja $loja: " . $stmtInserir->error;
                    break; 
                }
    
                $stmtInserir->close();
            } else {
                $_SESSION['mensagem'] = "Erro ao preparar a consulta para a loja $loja: " . $conn->error;
                break;
            }
        }
    } else {
        $_SESSION['mensagem'] = "Erro: 'cod_loja' não pode ser convertido em um array.";
    }
    
    header("Location: carrinho.php");
    exit();
} else {
    echo "Método de requisição inválido.";
}
?>
