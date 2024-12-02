<?php

require_once '../base/conex.php';  // Arquivo que contém a conexão com o banco (use seu próprio arquivo de conexão)

session_start();


if (!isset($_SESSION['UsuarioID'])) {
    echo "Você precisa estar logado para ver o seu histórico.";
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=web_shoppe", "root", ""); // Ajuste as credenciais conforme necessário
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usuario_id = $_SESSION['UsuarioID'];

    $sql = "SELECT hv.cod_produto AS produto_id, p.nome AS produto_nome, p.valor_venda_produto AS produto_preco, hv.data_visualizacao
    FROM historico_visualizacao hv
    JOIN produto p ON hv.cod_produto = p.cod_produto
    WHERE hv.cod_usuario = :cod_usuario
    ORDER BY hv.data_visualizacao DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cod_usuario', $usuario_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<h2>Seu Histórico de Produtos</h2>";
        echo "<table class='table'>";
        echo "<thead><tr><th>Produto</th><th>Preço</th><th>Data de Visualização</th></tr></thead>";
        echo "<tbody>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['produto_nome']) . "</td>";
            echo "<td>R$ " . number_format($row['produto_preco'], 2, ',', '.') . "</td>";
            echo "<td>" . $row['data_visualizacao'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>Você não visualizou nenhum produto ainda.</p>";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 40px 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 50px;
    }

    h2 {
        text-align: center;
        color: #6D09A4;
        margin-bottom: 30px;
        font-size: 28px;
        font-weight: bold;
    }

    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    table th {
        background-color: #6D09A4;
        color: white;
        font-size: 16px;
        font-weight: bold;
    }

    table td {
        font-size: 14px;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    table tr:first-child th {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    table tr:last-child td {
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .no-history {
        text-align: center;
        font-size: 18px;
        color: #6D09A4;
    }
</style>
