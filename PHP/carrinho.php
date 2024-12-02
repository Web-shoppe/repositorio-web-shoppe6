<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuario_id = $_SESSION['UsuarioID'] ?? null;
if (!$usuario_id) {
    die("Você precisa estar logado para finalizar a compra.");
}

$con = mysqli_connect("localhost", "root", "", "web_shoppe");
if (!$con) {
    die("Erro de conexão: " . mysqli_connect_error());
}

if (isset($_POST['finalizar_compra'])) {
    $subtotal = floatval($_POST['subtotal']);
    $total = floatval($_POST['total']);
    $frete = 0; 
    $data_reserva = date('Y-m-d H:i:s');

    $lojas = json_decode($_POST['lojas'], true);

    $lojas_json = json_encode($lojas);

    $sql = "SELECT p.nome, c.quantidade
            FROM produto p
            JOIN carrinho c ON p.cod_produto = c.cod_prod 
            WHERE c.cod_usuario = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = [
            'nome' => $row['nome'],
            'quantidade' => $row['quantidade']
        ];
    }
    $stmt->close();

    $produtos_json = json_encode($produtos);

    $produtos_json = json_encode($produtos);

    $sql = "INSERT INTO compras (cod_usuario, data_reserva, subtotal, frete, total, produtos, lojas) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("issddss", $usuario_id, $data_reserva, $subtotal, $frete, $total, $produtos_json, $lojas_json);

    if ($stmt->execute()) {
        $sql = "DELETE FROM carrinho WHERE cod_usuario = ?";
        $stmtDelete = $con->prepare($sql);
        $stmtDelete->bind_param("i", $usuario_id);
        $stmtDelete->execute();
        $stmtDelete->close();

        $_SESSION['mensagem'] = "Compra registrada com sucesso!";
        header("Location: checkout.php");
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao finalizar a compra: " . $stmt->error;
        header("Location: checkout.php");
        exit();
    }
}



if (isset($_POST['remover_produto'])) {
    $cod_produto = $_POST['cod_produto'];

    // Remove o produto do carrinho
    // $sql = "
    // // DELETE FROM carrinho WHERE cod_usuario = ?
    //  DELETE FROM carrinho c
    //             INNER JOIN usuario u ON u.cod_usuario = c.cod_usuario                
    //             WHERE l.id_usu = ?"
    // ";
    $sql = "DELETE c FROM carrinho c
    INNER JOIN usuario u ON u.cod_usuario = c.cod_usuario
    WHERE c.cod_usuario = ?";


    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $usuario_id);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Produto removido com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao remover o produto: " . $stmt->error;
    }

    header("Location: carrinho.php");
    exit();
}

mysqli_close($con);
?>


<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuario_id = $_SESSION['UsuarioID'] ?? null;
if (!$usuario_id) {
    die("Você precisa estar logado para finalizar a compra.");
}

$con = mysqli_connect("localhost", "root", "", "web_shoppe");
if (!$con) {
    die("Erro de conexão: " . mysqli_connect_error());
}



mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="../CSS/carrinho.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/homeN.css">
</head>
<body>

    <?php require_once '../include/header_inicio.php'; ?>

    <div class="container">
        <h1>Carrinho de Compras</h1>

        <?php
        if (isset($_SESSION['mensagem'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['mensagem'] . "</div>";
            unset($_SESSION['mensagem']); // Limpa a mensagem após exibir
        }
        ?>
    </div>
        
        <div class="produtos">
        <?php
$subtotalGeral = 0; 
$descontoGeral = 0; 
$lojas = []; 

$sql = "SELECT p.*, l.cod_loja AS id_loja, l.nome AS nome_loja, c.quantidade
        FROM produto p
        JOIN carrinho c ON p.cod_produto = c.cod_prod 
        JOIN loja l ON p.id_loja = l.cod_loja 
        WHERE c.cod_usuario = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<div class='amostra-produto'>";

if ($result->num_rows > 0) {
    while ($produto = $result->fetch_assoc()) {
        $desconto = $produto['valor_desconto'];
        $valorVenda = $produto['valor_venda_produto'];
        $valorUnit = $valorVenda - $desconto;
        $quantidade = $produto['quantidade'];
        $estoque = $produto['qt_produto_estocado'];
        $precoTotal = $valorVenda * $quantidade;
        $descontoTotal = $desconto * $quantidade;

        $subtotalGeral += $precoTotal; 
        $descontoGeral += $descontoTotal; 

        $lojas[] = $produto['id_loja'];

        echo "<div class='produto'>";

        echo "<div class='info-produto'>";
        echo "<img src='../IMAGENS/produto/" . ($produto['foto_prod'] ?: 'sem_imagem.png') . "' alt='Produto'>";
        echo "<span class='titulo-card'>" . htmlspecialchars($produto['nome']) . "</span>";
        echo "</div>";

        echo "<div class='detalhes'>";
        echo "<h3 style='color:#6D09A4'>R$ <span class='valor' id='valorVenda_" . $produto['cod_produto'] . "'>" . number_format($valorVenda, 2, ',', '.') . "</span></h3>";
        echo "<br>";

        if ($desconto > 0) {
            echo "<p style='color:#00b14a'>R$ - <span class='valor' id='valorDesc_" . $produto['cod_produto'] . "'>" . number_format($desconto, 2, ',', '.') . "</span></p>";
        }

        echo "<div class='valor-quant'>";
        echo "<span>Quantidade: <input type='number' 
                    style='width: 70px; margin-bottom: 10px'
                    class='form-control input-quantidade mx-2' 
                    value='$quantidade' 
                    max='$estoque' 
                    min='1' 
                    onchange='atualizarSubtotal(this)' 
                    data-valor-unit='$valorVenda' 
                    data-desconto-unit='$desconto'> 
              </span>";
        echo "</div>";

        echo "<form method='POST' class='acao'>";
        echo "<input type='hidden' name='cod_produto' value='" . $produto['cod_produto'] . "'>";
        echo "<button type='submit' name='remover_produto' class='btn btn-danger'>Remover</button>";
        echo "</form>";
        echo "</div>";

        echo "</div>";
    }
    

    $totalGeral = $subtotalGeral - $descontoGeral; 

    echo "</div>";
    echo "<div class='resumo-compra' style='padding: 20px'>";

    echo "<h3>Subtotal:<span class='valor' id='subtotal' style='margin-left: 45%'> R$ " . number_format($subtotalGeral, 2, ',', '.') . "</span></h3>";
    echo "<br>";

    if ($descontoGeral > 0) {
        echo "<h3>Descontos:<span class='valor' id='desconto_total' style='margin-left: 38%; color:#00b14a'> R$ " . number_format($descontoGeral, 2, ',', '.') . "</span></h3>";
        echo "<br>";
    }

    echo "<h3>Total:<span class='valor' id='total' style='margin-left: 55%; color:#6D09A4'> R$ " . number_format($totalGeral, 2, ',', '.') . "</span></h3>";
    echo "</div>";

    $lojasUnicas = array_unique($lojas);

    echo "<form action='' method='POST'>";  
    echo "<input type='hidden' name='subtotal' value='" . number_format($subtotalGeral, 2, '.', '') . "'>";
    echo "<input type='hidden' name='desconto' value='" . number_format($descontoGeral, 2, '.', '') . "'>";
    echo "<input type='hidden' name='total' value='" . number_format($totalGeral, 2, '.', '') . "'>";
    echo "<input type='hidden' name='lojas' value='" . htmlspecialchars(json_encode($lojasUnicas)) . "'>";
    echo "<button type='submit' name='finalizar_compra' class='btn btn-success' style='margin-top: 20px;'>Finalizar Compra</button>";
    echo "</form>";
    echo "</div>";
} else {
    echo "<p>Nenhum produto encontrado no carrinho.</p>";
}

$stmt->close();
?>
</div>


<script>
  function atualizarSubtotal(input) {
    const inputs = document.querySelectorAll('.input-quantidade');

    let subtotalGeral = 0;
    let descontoGeral = 0;

    inputs.forEach(input => {
        const quantidade = parseInt(input.value) || 0;
        const valorUnit = parseFloat(input.getAttribute('data-valor-unit')) || 0;
        const descontoUnit = parseFloat(input.getAttribute('data-desconto-unit')) || 0;

        subtotalGeral += valorUnit * quantidade;
        if (descontoUnit > 0) { 
            descontoGeral += descontoUnit * quantidade;
        }
    });

    const totalGeral = subtotalGeral - descontoGeral;

document.querySelector('#subtotal').textContent = subtotalGeral.toLocaleString('pt-BR', { style: 'decimal', minimumFractionDigits: 2 });
document.querySelector('#desconto_total').textContent = descontoGeral > 0 ? descontoGeral.toLocaleString('pt-BR', { style: 'decimal', minimumFractionDigits: 2 }) : '0,00';
document.querySelector('#total').textContent = totalGeral.toLocaleString('pt-BR', { style: 'decimal', minimumFractionDigits: 2 });

}
</script>
<?php
    // require_once "../include/footer.php"
?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>

<?php
mysqli_close($con);
?>
