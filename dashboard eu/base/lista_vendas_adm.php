<?php
$dbHost = "localhost";      
$dbUsername = "root";     
$dbPassword = "";          
$dbName = "Web_shoppe";     

$conexao = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$conexao) {
    die("Erro de conexão: " . mysqli_connect_error());
}

$sql_pedidos = "SELECT p.*, c.cod_compra, c.subtotal, c.frete, c.total AS total_compra, c.produtos
                FROM pedidos p
                LEFT JOIN compras c ON p.cod_compra = c.cod_compra
                ORDER BY p.data_pedido DESC"; 

$stmt = $conexao->prepare($sql_pedidos);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Lista de Pedidos</title>
</head>
<body>
    <div class="container mt-5">
        <h1><i class="fas fa-box"></i> Pedidos</h1>

        <div class="search-container">
            <input type="text" class="form-control" id="search" placeholder="Pesquisar pedidos...">
            <button class="btn btn-primary" id="resetSearch"><i class="fas fa-times"></i> Limpar</button>
            <a href="base/relatorio/gerar_relatorio.php?tipo=ven">
                <button class="btn btn-primary" id="generateReport"><i class="fas fa-file-alt"></i> Gerar Relatório</button>
            </a>
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome Completo</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>CEP</th>
                    <th>Método de Pagamento</th>
                    <th>Data do Pedido</th>
                    <th>Total da Compra</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($dados = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($dados['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['nome_completo']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['telefone']) . "</td>";

                        $cpf = htmlspecialchars($dados['cpf']);
                        $cpfFormatado = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
                        echo "<td>" . $cpfFormatado . "</td>";

                        echo "<td>" . htmlspecialchars($dados['cep']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['metodo_pagamento']) . "</td>";
                        echo "<td>" . htmlspecialchars($dados['data_pedido']) . "</td>";
                        echo "<td>R$ " . number_format($dados['total_compra'], 2, ',', '.') . "</td>";
                        echo "<td><button class='btn btn-primary' data-toggle='modal' data-target='#pedidoModal-" . htmlspecialchars($dados['id']) . "'>Ver Detalhes</button></td>";
                        echo "</tr>";

                        echo "<div class='modal fade' id='pedidoModal-" . htmlspecialchars($dados['id']) . "' tabindex='-1' role='dialog' aria-labelledby='pedidoModalLabel-" . htmlspecialchars($dados['id']) . "' aria-hidden='true'>";
                        echo "<div class='modal-dialog' role='document'>";
                        echo "<div class='modal-content'>";
                        echo "<div class='modal-header'>";
                        echo "<h5 class='modal-title' id='pedidoModalLabel-" . htmlspecialchars($dados['id']) . "'>Detalhes do Pedido #" . htmlspecialchars($dados['id']) . "</h5>";
                        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Fechar'>";
                        echo "<span aria-hidden='true'>&times;</span>";
                        echo "</button>";
                        echo "</div>";
                        echo "<div class='modal-body'>";
                        echo "<p><strong>Nome Completo:</strong> " . htmlspecialchars($dados['nome_completo']) . "</p>";
                        echo "<p><strong>Email:</strong> " . htmlspecialchars($dados['email']) . "</p>";
                        echo "<p><strong>Telefone:</strong> " . htmlspecialchars($dados['telefone']) . "</p>";
                        echo "<p><strong>CPF:</strong> " . $cpfFormatado . "</p>";
                        echo "<p><strong>CEP:</strong> " . htmlspecialchars($dados['cep']) . "</p>";
                        echo "<p><strong>Estado:</strong> " . htmlspecialchars($dados['estado']) . "</p>";
                        echo "<p><strong>Bairro:</strong> " . htmlspecialchars($dados['bairro']) . "</p>";
                        echo "<p><strong>Endereço:</strong> " . htmlspecialchars($dados['endereco']) . "</p>";
                        echo "<p><strong>Número:</strong> " . htmlspecialchars($dados['numero']) . "</p>";
                        echo "<p><strong>Método de Pagamento:</strong> " . htmlspecialchars($dados['metodo_pagamento']) . "</p>";
                        echo "<p><strong>Data do Pedido:</strong> " . htmlspecialchars($dados['data_pedido']) . "</p>";
                        echo "<p><strong>Código da Loja:</strong> " . htmlspecialchars($dados['cod_loja']) . "</p>";
                        echo "<p><strong>Código da Compra:</strong> " . htmlspecialchars($dados['cod_compra']) . "</p>";
                        echo "<p><strong>Dados do Pagamento:</strong> " . htmlspecialchars($dados['dados_pagamento']) . "</p>";
                        echo "<p><strong>Total da Compra:</strong> R$ " . number_format($dados['total_compra'], 2, ',', '.') . "</p>";
                        echo "<hr>";

                        echo "<h6><strong>Detalhes da Compra:</strong></h6>";
                        echo "<p><strong>Subtotal:</strong> R$ " . number_format($dados['subtotal'], 2, ',', '.') . "</p>";
                        echo "<p><strong>Frete:</strong> R$ " . number_format($dados['frete'], 2, ',', '.') . "</p>";
                        echo "<p><strong>Total:</strong> R$ " . number_format($dados['total_compra'], 2, ',', '.') . "</p>";
                        echo "<p><strong>Produtos:</strong> " . htmlspecialchars($dados['produtos']) . "</p>";
                        echo "</div>";
                        echo "<div class='modal-footer'>";
                        echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<tr><td colspan='10'>Nenhum pedido encontrado.</td></tr>";
                }

                $stmt->close();
                $conexao->close();
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('table tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('#resetSearch').click(function() {
                $('#search').val('');
                $('table tr').show();
            });
        });
    </script>
</body>
</html>
