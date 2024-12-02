<?php
session_start();


$dbHost = "localhost";      
$dbUsername = "root";      
$dbPassword = "";          
$dbName = "web_shoppe";    

$conexao = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);


if (!$conexao) {
    die("Erro de conexão: " . mysqli_connect_error());
}


$usuario_id = isset($_SESSION['UsuarioID']) ? intval($_SESSION['UsuarioID']) : 0;


if ($usuario_id <= 0) {
    echo "<p class='alert alert-danger text-center'>Erro: Usuário não está autenticado ou ID inválido.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shoppe - Lista de Compras</title>
    <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
            color: #495057;
        }

        .container {
            margin-top: 50px;
            height: 100%;
        }

        h1 {
            text-align: center;
            color: #6a0dad;
            margin-bottom: 30px;
        }

        .table {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .table th, .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #6a0dad;
            color: #fff;
            font-weight: bold;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        .table-hover tbody tr:hover {
            background-color: #f9e6ff;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-end;
            border: none !important;
        }

        .search-bar input {
            border-radius: 30px;
            padding: 10px 20px;
            border: 1px solid #6a0dad;
            width: 300px;
        }

        .search-bar button {
            background-color: #6a0dad;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-left: 10px;
            border-radius: 30px;
        }

        .search-bar button:hover {
            background-color: #4e0a8d;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .icon {
            font-size: 1.2rem;
            color: #6a0dad;
        }
    </style>
</head>

<body>

<?php require_once '../include/header_inicio.php'; ?>

<div class="container">
    <h1><i class="fas fa-shopping-cart icon"></i> Lista de Compras</h1>

    <div class="search-bar">
        <input type="text" id="search" placeholder="Pesquisar compra...">
        <button id="resetSearch"><i class="fas fa-sync-alt"></i> Resetar</button>
    </div>

    <?php
    $sql = "SELECT cod_compra, cod_usuario, data_reserva, subtotal, frete, total, produtos FROM compras WHERE cod_usuario = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $usuario_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='table table-striped table-hover'>";
            echo "<thead>
                    <tr>
                        <th><i class='fas fa-receipt'></i> Código da Compra</th>
                        <th><i class='fas fa-calendar-alt'></i> Data da Compra</th>
                        <th><i class='fas fa-dollar-sign'></i> Subtotal</th>
                        <th><i class='fas fa-shipping-fast'></i> Frete</th>
                        <th><i class='fas fa-coins'></i> Total</th>
                        <th><i class='fas fa-box'></i> Produtos</th>
                    </tr>
                  </thead>
                  <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['cod_compra']) . "</td>";
                echo "<td>" . htmlspecialchars($row['data_reserva']) . "</td>";
                echo "<td>R$ " . htmlspecialchars(number_format($row['subtotal'], 2, ',', '.')) . "</td>";
                echo "<td>R$ " . htmlspecialchars(number_format($row['frete'], 2, ',', '.')) . "</td>";
                echo "<td>R$ " . htmlspecialchars(number_format($row['total'], 2, ',', '.')) . "</td>";

                $produtos = json_decode($row['produtos'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($produtos)) {
                    echo "<td>";
                    foreach ($produtos as $produto) {
                        echo "Nome: " . htmlspecialchars($produto['nome']) . "<br>";
                        echo "Quantidade: " . htmlspecialchars($produto['quantidade']) . "<br><br>";
                    }
                    echo "</td>";
                } else {
                    echo "<td>Erro ao processar produtos.</td>";
                }

                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p class='alert alert-warning text-center'>Nenhuma compra encontrada.</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<p class='alert alert-danger text-center'>Erro ao preparar consulta: " . mysqli_error($conexao) . "</p>";
    }

    mysqli_close($conexao);
    ?>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#resetSearch').click(function() {
            $('#search').val('');
            $('table tbody tr').show();
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    
</body>
</html>
