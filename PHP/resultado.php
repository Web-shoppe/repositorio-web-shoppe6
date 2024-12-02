<?php 
session_start();
$usuario_id = isset($_SESSION['UsuarioID']) ? intval($_SESSION['UsuarioID']) : 0;

?>


<?php
$nivel_necessario = 1;
include "../base/testa_nivel.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shoppe</title>
    <link rel="stylesheet" href="../CSS/homeN.css">
    <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <?php
        require_once '../include/header_inicio.php';
    ?>

<?php

function calcularValorFinal($valorVenda, $valorDesconto) {
    return $valorVenda - $valorDesconto;
  }

function calcularPorcentagemDesconto($valorCompra, $valorDesconto) {
    if ($valorCompra <= 0) {
        return 0; 
    }
    if ($valorDesconto > $valorCompra) {
        return 0; 
    }
    $desconto = ($valorCompra - $valorDesconto) / $valorCompra * 100;
    return round($desconto, 0); 
}

function limitarCaracteres($texto, $limite) {
    if (strlen($texto) > $limite) {
        return substr($texto, 0, $limite) . '...'; 
    }
    return $texto; 
}

function calcularValorParcela($valorDesconto, $qtParcelas) {
    if ($qtParcelas <= 0) {
        return 0; 
    }
    return round($valorDesconto / $qtParcelas, 2); 
}

$con = mysqli_connect("localhost", "root", "", "web_shoppe");
if (!$con) {
    die("Erro de conexão: " . mysqli_connect_error());
}

$query = isset($_POST['query']) ? mysqli_real_escape_string($con, $_POST['query']) : '';

$sql = "SELECT p.*, 
                p.foto_prod, 
                l.nome AS nome_loja, 
                l.foto_loja, 
                calc_feed.* 
        FROM produto p 
        LEFT JOIN ( 
            SELECT f.id_produto AS prod, COUNT(*) AS qtd_ocorrencia, 
                       SUM(f.nota) AS soma, AVG(f.nota) AS media 
            FROM feedback f
            GROUP BY f.id_produto
        ) AS calc_feed ON p.cod_produto = calc_feed.prod
        LEFT JOIN loja l ON p.id_loja = l.cod_loja
        WHERE p.nome LIKE '%$query%' 
        AND p.qt_produto_estocado > 0
        AND ( p.id_loja != (SELECT cod_loja FROM loja WHERE id_usu = $usuario_id) OR NOT EXISTS (SELECT 1 FROM loja WHERE id_usu = $usuario_id))";

$rs = mysqli_query($con, $sql);
    echo "<div class='div-card-total'>";
while ($info = mysqli_fetch_assoc($rs)) {
    echo "<div class='div-card-sec2'>";
  
      echo "<div class='div-card-nova'>"; 
      echo "<a href='produto.php?id=" . $info['cod_produto'] . "' class='link-produto'>";
  
      echo "<div>";
    
    $imagemProduto = $info['foto_prod'] ?: 'sem imagem.png'; 
    echo "<img src='../IMAGENS/produto/$imagemProduto' alt='' class='sem'>"; 
    echo "</div>";
    echo "<div>";

    echo "<h4 class='titulo-card'>" . htmlspecialchars($info['nome']) . "</h4>";
    $descricaoLimitada = limitarCaracteres($info['nome_loja'], 100);
    echo "<p class='titulo-card'>" . htmlspecialchars($descricaoLimitada) . "</p>";

    echo "<div class='estrela'>";
    $media = $info['media'] ?: 0; 
    $inteiro = floor($media); 
    $metade = ($media - $inteiro) >= 0.5; 

    for ($i = 1; $i <= $inteiro; $i++) {
        echo "<img src='../IMAGENS/home/estrela-cheia.png' alt='' class='estrela1'>"; 
    }

    if ($metade) {
        echo "<img src='../IMAGENS/home/estrela-metade.png' alt='' class='estrela1'>"; 
    }

    for ($i = $inteiro + ($metade ? 1 : 0); $i < 5; $i++) {
        echo "<img src='../IMAGENS/home/estrela-vazia.png' alt='' class='estrela1'>"; 
    }

    echo "<p class='p-ava'>" . ($info['qtd_ocorrencia'] ?: 0) . "</p>"; 
    echo "</div>"; 

    echo "<div class='div-preco'>";
    $valorCompra = $info['valor_venda_produto'];
    $valorDesconto = $info['valor_desconto'];

    $valorFinal = calcularValorFinal($valorCompra, $valorDesconto); 

    $porcentagemDesconto = 0;

    if ($valorDesconto > 0 && $valorCompra > 0) { 
        $porcentagemDesconto = ($valorDesconto / $valorCompra) * 100; 
    }

    if ($valorDesconto > 0) { 
        echo "<p class='p-preco' style='text-decoration: line-through;'>R$" . number_format($valorCompra, 2, ',', '.') . "</p>";
        echo "<div class='desc2'>";
        echo "<p>-" . number_format($porcentagemDesconto, 0, ',', '.') . "%</p>"; 
        echo "</div>";
    }

    echo "</div>"; 

    echo "<a href='#' class='preco'>R$ " . number_format($valorFinal, 2, ',', '.') . "</a>"; 

    $qtParcelas = $info['qt_parcela'];
    $valorParcela = calcularValorParcela($valorFinal, $qtParcelas); 
    echo "<div class='parcela'>";
    echo "<p class='p-ou'>OU</p>";
    echo "<p class='valor'>" . $qtParcelas . "x de R$ " . number_format($valorParcela, 2, ',', '.') . "</p>";
    echo "</div>";

    echo "<div class='div-a'>";
    echo "<a href='#' class='carrinho'>Adicionar ao Carrinho</a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</a>"; 
    echo "</div>"; 

    echo "<br>";
}
echo "</div>";
if (mysqli_num_rows($rs) == 0) {
    echo "<p>Nenhum produto encontrado para: " . htmlspecialchars($query) . "</p>";
}

mysqli_close($con);
?>
    
    <?php
        include '../include/footer.php';
    ?>
   
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>


