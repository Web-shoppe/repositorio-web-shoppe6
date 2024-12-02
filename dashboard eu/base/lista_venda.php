<?php
include "base/conexao.php";

if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$id_usuario = $_SESSION['UsuarioID'];

$loja = "SELECT cod_loja FROM loja WHERE id_usu = ?"; 

$stmt = $conexao->prepare($loja);
$stmt->bind_param('i', $id_usuario);
$stmt->execute();

$stmt->bind_result($cod_loja);
$stmt->fetch();

$stmt->close();

$query = "SELECT * FROM pedidos WHERE cod_loja = $cod_loja"; 

$list = mysqli_query($conexao, $query);

if (!$list) {
    die("Erro na consulta: " . mysqli_error($conexao));
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Lista de Pedidos</title>
    <style>
        body {
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container {
            padding: 30px;
            max-width: 100%;
            margin: 40px auto;
        }

        h1 {
            color: #6D09A4;
            margin-bottom: 20px;
        }

        .btn {
            border-radius: 20px;
        }

        .btn-primary {
            background-color: #6D09A4;
            border-color: #6D09A4;
        }

        input[type="text"] {
            border-radius: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            border-color: #6D09A4;
            box-shadow: 0 0 5px rgba(109, 9, 164, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .modal-body ul {
            list-style-type: none; 
            padding: 0; 
        }

        .modal-body ul li {
            background-color: #f8f9fa; 
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px; 
        }
    </style>
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
                   
                    
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($list) > 0) {
                    while ($dados = mysqli_fetch_assoc($list)) {
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
                        echo "<p><strong>Endereço:</strong> " . htmlspecialchars($dados['endereco']) . ", " . htmlspecialchars($dados['numero']) . "</p>";
                        echo "<p><strong>Método de Pagamento:</strong> " . htmlspecialchars($dados['metodo_pagamento']) . "</p>";
                      

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

                mysqli_close($conexao);
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('resetSearch').addEventListener('click', function() {
            document.getElementById('search').value = '';
        });
    </script>
</body>
</html>
