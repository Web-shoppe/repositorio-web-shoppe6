<?php

// Supondo que o ID do usuário esteja na sessão
$usuario_id = $_SESSION['UsuarioID'] ?? ""; 

// Verifique se o usuário_id não está vazio antes de executar a consulta
if (!empty($usuario_id)) {
    // Preparando a consulta para buscar o ID da loja
    $sql = "SELECT l.cod_loja FROM loja l WHERE l.id_usu = $usuario_id";

    // Preparar a consulta
    if ($stmt = mysqli_prepare($con, $sql)) {
        // Vincula o parâmetro (o ? na consulta)
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);

        // Executa a consulta
        mysqli_stmt_execute($stmt);

        // Armazena o resultado
        mysqli_stmt_bind_result($stmt, $id_loja);

        // Recupera o valor do id_loja, se encontrado
        if (mysqli_stmt_fetch($stmt)) {
            // Agora você tem o $id_loja
            // Realiza a consulta de produtos para essa loja
            $produto_sql = "SELECT * FROM produto WHERE id_loja = $id_loja";

            // Preparar a consulta de produtos
            if ($produto_stmt = mysqli_prepare($con, $produto_sql)) {
                // Vincula o parâmetro (id_loja)
                mysqli_stmt_bind_param($produto_stmt, "i", $id_loja);

                // Executa a consulta
                mysqli_stmt_execute($produto_stmt);

                // Obtém o resultado dos produtos
                $produto_result = mysqli_stmt_get_result($produto_stmt);

                // Exemplo: Imprimir os produtos
                while ($produto = mysqli_fetch_assoc($produto_result)) {
                    // Aqui você pode processar os dados dos produtos
                    echo "Produto: " . $produto['nome_produto'] . "<br>";
                }

                // Fecha a declaração de produtos
                mysqli_stmt_close($produto_stmt);
            }
        } else {
            echo "Loja não encontrada para o usuário com ID: $usuario_id";
        }

        // Fecha a declaração
        mysqli_stmt_close($stmt);
    } else {
        echo "Erro ao preparar a consulta.";
    }
} else {
    echo "ID do usuário não encontrado.";
}

// Exibir erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php'; // Carrega o autoload do Composer
require_once '../conexao.php'; // Conexão com o banco de dados

// Determina o tipo de relatório a ser gerado
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'usuario';

// Obtenha o 'id_loja' da URL, se disponível
$id_loja = isset($_GET['id_loja']) ? (int)$_GET['id_loja'] : 0;

switch ($tipo) {
    case 'prod':
        $sql = "SELECT cod_produto, nome, valor_venda_produto, valor_desconto, garantia_produto, qt_produto_estocado, qt_parcela FROM produto"; 
        $titulo = 'Produtos';
        break;
        case 'prod2':  // Modificação apenas neste case para filtrar por id_loja
            if ($id_loja > 0) {
                // Verifique se o valor de id_loja está sendo passado corretamente
                // echo "ID Loja: $id_loja"; // Para debug
                
                // Aplique o filtro no SQL para a loja específica
                $sql = "SELECT cod_produto, nome, valor_venda_produto, valor_desconto, garantia_produto, 
                               qt_produto_estocado, qt_parcela, foto_prod, foto_prod2, foto_prod3, foto_prod4, foto_prod5, categoria 
                        FROM produto WHERE id_loja = $id_loja";
                $titulo = 'Produtos Detalhados da Loja';
                
                // Prepare a consulta e execute de forma segura
                if ($stmt = mysqli_prepare($conexao, $sql)) {
                    mysqli_stmt_bind_param($stmt, 'i', $id_loja); // 'i' para inteiro
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                } else {
                    // Caso não consiga preparar a consulta
                    die('Erro ao preparar a consulta: ' . mysqli_error($conexao));
                }
                
            } else {
                // Caso não tenha id_loja, retorna todos os produtos
                $sql = "SELECT cod_produto, nome, valor_venda_produto, valor_desconto, garantia_produto, 
                               qt_produto_estocado, qt_parcela, foto_prod, foto_prod2, foto_prod3, foto_prod4, foto_prod5
                        FROM produto";
                $titulo = 'Produtos Detalhados';
                
                // Executa a consulta sem filtro
                $result = mysqli_query($conexao, $sql);
            }
            break;
        
    case 'usu':
        $sql = "SELECT cod_usuario, nome, email, senha, nivel, ativo, cadastro FROM usuario";
        $titulo = 'Usuários';
        break;
    case 'ven':
        $sql = "SELECT nome_completo, email, cpf, telefone, cep, metodo_pagamento FROM pedidos";
        $titulo = 'Pedidos';
        break;
    case 'lojistas':
        $sql = "SELECT cod_loja, nome_lojista, cpf, tel1, tel2, email, cep FROM loja";
        $titulo = 'Lojistas';
        break;
    case 'loja':
        $sql = "SELECT cod_loja, cnpj, nome, foto_loja, banner FROM loja";
        $titulo = 'Loja';
        break;
}

// Executa a consulta ao banco de dados
$result = mysqli_query($conexao, $sql);

if (!$result) {
    die("Erro na consulta ao banco de dados: " . mysqli_error($conexao));
}

// Estilo CSS para a tabela com a cor roxa
$css = '
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 20px;
        }
        .container {
            width: 90%;
            margin: auto;
            background-color: #fff;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            border-radius: 12px;
        }
        h1 {
            text-align: center;
            font-size: 28px;
            color: #6a1b9a; /* Tom roxo para o título */
            margin-bottom: 20px;
        }
        hr {
            border: 0;
            height: 2px;
            background: #6a1b9a; /* Linha roxa */
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #6a1b9a; /* Fundo roxo para o cabeçalho */
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(odd) {
            background-color: #f0e6f6; /* Tom claro de roxo */
        }
        tr:hover {
            background-color: #e1bee7; /* Hover com tom intermediário de roxo */
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
';

$html = '
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Relatório de ' . $titulo . '</title>
        ' . $css . '
    </head>
    <body>
        <div class="container">
            <h1>Relatório de ' . $titulo . '</h1>
            <hr>
            <table>
                <thead>
                    <tr>';

// Adiciona cabeçalho da tabela baseado no tipo
switch ($tipo) {
    case 'prod':
        $html .= '<th>ID</th><th>Nome</th><th>Preço</th><th>Valor Desconto</th><th>Garantia</th><th>Quantidade em Estoque</th><th>Parcelas</th>';
        break;
    case 'prod2':  // Cabeçalho específico para os produtos detalhados
        $html .= '<th>ID</th><th>Nome</th><th>Preço</th><th>Valor Desconto</th><th>Garantia</th><th>Quantidade em Estoque</th><th>Parcelas</th><th>Categoria</th><th>Foto 1</th><th>Foto 2</th><th>Foto 3</th><th>Foto 4</th><th>Foto 5</th>';
        break;
    case 'usu':
        $html .= '<th>ID</th><th>Nome</th><th>Email</th><th>Senha</th><th>Nível</th><th>Ativo</th><th>Data de Inserção</th>';
        break;
    case 'ven':
        $html .= '<th>Nome</th><th>Email</th><th>CPF</th><th>Telefone</th><th>CEP</th><th>Método de Pagamento</th>';
        break;
    case 'lojistas':
        $html .= '<th>Código da Loja</th><th>Nome do Lojista</th><th>CPF</th><th>Telefone</th><th>Email</th><th>CEP</th>';
        break;
    case 'loja':  // Cabeçalho específico para a tabela loja
        $html .= '<th>Código da Loja</th><th>CNPJ</th><th>Nome</th><th>Foto da Loja</th><th>Banner</th>';
        break;
}

$html .= '
                    </tr>
                </thead>
                <tbody>';

while ($dados = mysqli_fetch_assoc($result)) {
    $html .= '<tr>';
    switch ($tipo) {
        case 'prod':
            $valor_prod = !empty($dados['valor_venda_produto']) ? (float)$dados['valor_venda_produto'] : 0.00;
            $valor_desconto = !empty($dados['valor_desconto']) ? (float)$dados['valor_desconto'] : 0.00;
            $garantia = !empty($dados['garantia_produto']) ? $dados['garantia_produto'] : 'N/A';
            $qt_produto_estocado = !empty($dados['qt_produto_estocado']) ? (int)$dados['qt_produto_estocado'] : 0;
            $qt_parcela = !empty($dados['qt_parcela']) ? (int)$dados['qt_parcela'] : 1;
            $html .= '<td>' . $dados['cod_produto'] . '</td><td>' . $dados['nome'] . '</td><td>R$ ' . number_format($valor_prod, 2, ",", ".") . '</td><td>R$ ' . number_format($valor_desconto, 2, ",", ".") . '</td><td>' . $garantia . '</td><td>' . $qt_produto_estocado . '</td><td>' . $qt_parcela . '</td>';
            break;
        case 'prod2':
            $valor_prod = !empty($dados['valor_venda_produto']) ? (float)$dados['valor_venda_produto'] : 0.00;
            $valor_desconto = !empty($dados['valor_desconto']) ? (float)$dados['valor_desconto'] : 0.00;
            $garantia = !empty($dados['garantia_produto']) ? $dados['garantia_produto'] : 'N/A';
            $qt_produto_estocado = !empty($dados['qt_produto_estocado']) ? (int)$dados['qt_produto_estocado'] : 0;
            $qt_parcela = !empty($dados['qt_parcela']) ? (int)$dados['qt_parcela'] : 1;
            $categoria = !empty($dados['categoria']) ? $dados['categoria'] : 'N/A';
            $foto_prod1 = !empty($dados['foto_prod']) ? '<img src="' . $dados['foto_prod'] . '" width="100">' : 'N/A';
            $foto_prod2 = !empty($dados['foto_prod2']) ? '<img src="' . $dados['foto_prod2'] . '" width="100">' : 'N/A';
            $foto_prod3 = !empty($dados['foto_prod3']) ? '<img src="' . $dados['foto_prod3'] . '" width="100">' : 'N/A';
            $foto_prod4 = !empty($dados['foto_prod4']) ? '<img src="' . $dados['foto_prod4'] . '" width="100">' : 'N/A';
            $foto_prod5 = !empty($dados['foto_prod5']) ? '<img src="' . $dados['foto_prod5'] . '" width="100">' : 'N/A';
            $html .= '<td>' . $dados['cod_produto'] . '</td><td>' . $dados['nome'] . '</td><td>R$ ' . number_format($valor_prod, 2, ",", ".") . '</td><td>R$ ' . number_format($valor_desconto, 2, ",", ".") . '</td><td>' . $garantia . '</td><td>' . $qt_produto_estocado . '</td><td>' . $qt_parcela . '</td><td>' . $categoria . '</td><td>' . $foto_prod1 . '</td><td>' . $foto_prod2 . '</td><td>' . $foto_prod3 . '</td><td>' . $foto_prod4 . '</td><td>' . $foto_prod5 . '</td>';
            break;
        case 'usu':
            $html .= '<td>' . $dados['cod_usuario'] . '</td><td>' . $dados['nome'] . '</td><td>' . $dados['email'] . '</td><td>' . $dados['senha'] . '</td><td>' . $dados['nivel'] . '</td><td>' . $dados['ativo'] . '</td><td>' . $dados['cadastro'] . '</td>';
            break;
        case 'ven':
            $html .= '<td>' . $dados['nome_completo'] . '</td><td>' . $dados['email'] . '</td><td>' . $dados['cpf'] . '</td><td>' . $dados['telefone'] . '</td><td>' . $dados['cep'] . '</td><td>' . $dados['metodo_pagamento'] . '</td>';
            break;
        case 'lojistas':
            $html .= '<td>' . $dados['cod_loja'] . '</td><td>' . $dados['nome_lojista'] . '</td><td>' . $dados['cpf'] . '</td><td>' . $dados['tel1'] . ' / ' . $dados['tel2'] . '</td><td>' . $dados['email'] . '</td><td>' . $dados['cep'] . '</td>';
            break;
        case 'loja':
            $html .= '<td>' . $dados['cod_loja'] . '</td><td>' . $dados['cnpj'] . '</td><td>' . $dados['nome'] . '</td><td>' . (!empty($dados['foto_loja']) ? '<img src="' . $dados['foto_loja'] . '" width="100">' : 'N/A') . '</td><td>' . (!empty($dados['banner']) ? '<img src="' . $dados['banner'] . '" width="100">' : 'N/A') . '</td>';
            break;
    }
    $html .= '</tr>';
}

$html .= '
                </tbody>
            </table>
        </div>
    </body>
</html>
';

$htmlfooter = "
    <div class='footer'>Emissão: " . date('d/m/Y - H:i:s') . "</div>
"; 

// Geração do PDF
$mpdf = new \Mpdf\Mpdf();
$nomeArquivo = 'relatorio_' . $tipo . '.pdf';
$mpdf->WriteHTML($html);
$mpdf->SetHTMLFooter($htmlfooter);
$mpdf->Output($nomeArquivo, \Mpdf\Output\Destination::INLINE); // Mude para FILE se necessário
?>
