<?php
include "base/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: index.php");
    exit;
}

if (!$conexao) {
    echo "<p>Erro ao conectar ao banco de dados.</p>";
    exit;
}

$sql = "SELECT cod_loja FROM loja WHERE id_usu = " . $_SESSION['UsuarioID'] . ";";
$list = mysqli_query($conexao, $sql);

if ($list && mysqli_num_rows($list) > 0) {
    $loja = mysqli_fetch_assoc($list);
    
    if (isset($loja['cod_loja'])) {
        $sql = "SELECT * FROM produto WHERE id_loja = " . $loja['cod_loja'] . ";";
        $result = mysqli_query($conexao, $sql);
    } else {
        echo "<p>Loja não encontrada.</p>";
        exit;
    }
} else {
    echo "<p>Loja não associada ao usuário.</p>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #E4E9F7;
            color: #000;
        }
        .header-title {
            margin-bottom: 20px;
        }
        .btn {
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 0.9rem;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #6D09A4;
            border-color: #6D09A4;
        }
        .btn-primary:hover {
            background-color: #5a0085;
        }
        .btn-yellow {
            background-color: #ffc107;
            color: #000;
        }
        .btn-yellow:hover {
            background-color: #e0a800;
        }
        .btn-icon {
            display: flex;
            align-items: center;
        }
        .btn-icon i {
            margin-right: 5px;
        }
        .table {
            margin-top: 20px;
        }
        .discount-badge {
            background-color: #dc3545;
            color: white;
            border-radius: 5px;
            padding: 0.2em 0.5em;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="header-title">
            <h1 class="text-primary">Produtos</h1>
            <div class="btn-group">
                <a href="dashboard.php?page=add_prod.php" class="btn btn-primary btn-icon">
                    <i class="fas fa-plus"></i> Adicionar
                </a>
                <a href="base/relatorio/gerar_relatorio.php?tipo=prod2" class="btn btn-primary btn-icon">
                    <i class="fas fa-file-alt"></i> Gerar relatório
                </a>
            </div>
        </div>

        <div class="mb-3">
            <input type="text" class="form-control" id="search" placeholder="Pesquisar produtos...">
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Fabricante</th>
                        <th>Estoque</th>
                        <th>Valor de Venda</th>
                        <th>Desconto</th>
                        <th>Garantia</th>
                        <th>Parcelas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($dados = mysqli_fetch_assoc($result)) {
                            $valor_venda = (float)$dados['valor_venda_produto'];
                            $valor_desconto = (float)$dados['valor_desconto'];

                            $porcentagem_desconto = 0;
                            if ($valor_venda > 0) {
                                $porcentagem_desconto = ($valor_desconto / $valor_venda) * 100;
                            }

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($dados['nome']) . "</td>";
                            echo "<td>" . htmlspecialchars($dados['fabricante_produto']) . "</td>";
                            echo "<td>" . htmlspecialchars($dados['qt_produto_estocado']) . "</td>";
                            echo "<td>R$ " . number_format($valor_venda, 2, ",", ".") . "</td>";
                            echo "<td>" . number_format($porcentagem_desconto, 2, ",", ".") . "%</td>";
                            echo "<td>" . htmlspecialchars($dados['garantia_produto']) . " meses</td>";
                            echo "<td>" . htmlspecialchars($dados['qt_parcela']) . "x</td>";
                           
                            echo "<td>";
                            echo "<a href='dashboard.php?page=fedit_prod.php&id=" . $dados['cod_produto'] . "' class='btn btn-yellow btn-sm'>";
                            echo "<i class='fas fa-edit'></i> Editar</a>";
                            echo "<a href='dashboard.php?page=remove.php&tipo=prod&id=" . $dados['cod_produto'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este produto?\");'>";
                            echo "<i class='fas fa-trash'></i> Excluir</a>";
                            echo "<a href='../PHP/produto.php?id=" . $dados['cod_produto'] . "' class='btn btn-primary btn-sm'>";
                            echo "<i class='fas fa-eye'></i> Visualizar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Nenhum produto encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.querySelectorAll('table tbody tr');

            rows.forEach(function(row) {
                var cells = row.getElementsByTagName('td');
                var match = false;

                for (var i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().indexOf(searchValue) > -1) {
                        match = true;
                        break;
                    }
                }

                row.style.display = match ? '' : 'none';
            });
        });
    </script>

</body>
</html>
