<?php
$nivel_necessario = 2;
include "../base/testa_nivel.php";

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/loja.css">
    <link rel="stylesheet" href="../CSS/homeN.css">
    <title>Web Shoppe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header>
    <?php
        require_once '../include/header_inicio.php';
    ?>
    </header>

    <main>
        <?php 
        $con = mysqli_connect("localhost", "root", "", "web_shoppe");
        if (!$con) {
            die("Erro de conexão: " . mysqli_connect_error());
        }

        $idLoja = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($idLoja <= 0) {
            die("Loja não encontrado.");
        }

        function calcularValorFinal($valorVenda, $valorDesconto) {
            return $valorVenda - $valorDesconto; 
          }   
  
          function calcularPorcentagemDesconto($valorCompra, $valorDesconto)
          {
              if ($valorCompra <= 0) {
                  return 0; 
              }
              if ($valorDesconto > $valorCompra) {
                  return 0; 
              }
              $desconto = ($valorCompra - $valorDesconto) / $valorCompra * 100;
              return round($desconto, 0); 
          }
  
          function limitarCaracteres($texto, $limite)
          {
              if (strlen($texto) > $limite) {
                  return substr($texto, 0, $limite) . '...'; 
              }
              return $texto; 
          }
  
          function calcularValorParcela($valorDesconto, $qtParcelas)
          {
              if ($qtParcelas <= 0) {
                  return 0; 
              }
              return round($valorDesconto / $qtParcelas, 2); 
          }

        $sql_destaque = "SELECT p.*, p.foto_prod, l.nome AS nome_loja, l.banner, l.foto_loja, calc_feed.* 
                 FROM produto p 
                 LEFT JOIN loja l ON p.id_loja = l.cod_loja
                 LEFT JOIN 
                 (SELECT f.id_produto AS prod, COUNT(*) AS qtd_ocorrencia, SUM(f.nota) AS soma, AVG(f.nota) AS media 
                  FROM feedback f
                  GROUP BY f.id_produto) AS calc_feed 
                 ON p.cod_produto = calc_feed.prod
                 WHERE l.cod_loja = $idLoja AND p.qt_produto_estocado > 0
                 ORDER BY calc_feed.media DESC
                 LIMIT 2";




$sql = "SELECT p.*, l.nome AS nome_loja, l.banner, l.foto_loja, calc_feed.* 
FROM produto p 
LEFT JOIN loja l ON p.id_loja = l.cod_loja
LEFT JOIN 
(SELECT f.id_produto AS prod, COUNT(*) AS qtd_ocorrencia, SUM(f.nota) AS soma, AVG(f.nota) AS media 
FROM feedback f
GROUP BY f.id_produto) AS calc_feed
ON p.cod_produto = calc_feed.prod
WHERE l.cod_loja = $idLoja AND p.qt_produto_estocado > 0;";

$rs = mysqli_query($con, $sql);
$info = mysqli_fetch_assoc($rs);


                    $imagemLoja = $info['foto_loja'] ?? 'sem imagem.png';
                    $imagemBanner = $info['banner'] ?? 'sem imagem.png';
                    $nome = $info['nome_loja'] ?? 'sem nome'; 
                    
?>

            <figure class="bannerloja">
            <?= "<img src='../IMAGENS/loja/$imagemBanner' alt='' class='sem3'>"?>
            </figure>
            <div class="imgloja">
                <?= "<img src='../IMAGENS/loja/$imagemLoja' alt='' class='sem3'>"?>
                <h4 class="nomeloja"><?= $nome?></h4>
            </div>
        </div>

        <div class="produtosdest">
            <h3 class="destc">Produtos em destaque</h3>

            <div class="produtos">
                <?php
                    $rs_destaque = mysqli_query($con, $sql_destaque);

                    while ($info_destaque = mysqli_fetch_assoc($rs_destaque)) {
                        echo "<div class='div-dest-nova1'>"; 
                    
                        echo "<div class='div-dest-nova'>"; 
                        echo "<a href='produto.php?id=" . $info_destaque['cod_produto'] . "' class='link-produto'>";
                    
                        echo "<div>";
                        $imagemProduto = $info_destaque['foto_prod'] ?: 'sem imagem.png';
                        echo "<img src='../IMAGENS/produto/$imagemProduto' alt='' class='sem2'>";
                        echo "</div>";
                    
                        echo "<div>";
                        echo "<h4 class='titulo-card'>" . $info_destaque['nome'] . "</h4>"; 
                        $descricaoLimitada = limitarCaracteres($info_destaque['nome_loja'], 100); 
                        echo "<p class='titulo-card'>" . $descricaoLimitada . "</p>";
                    
                        echo "<div class='estrela'>";
                        $media = $info_destaque['media'] ?: 0; 
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
                    
                        echo "<p class='p-ava'>(" . ($info_destaque['qtd_ocorrencia'] ?: 0) . ")</p>";
                        echo "</div>"; 
                    
                        echo "<div class='div-preco'>";
    $valorCompra = $info_destaque['valor_venda_produto'];
    $valorDesconto = $info_destaque['valor_desconto'];

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
                    
                        $qtParcelas = $info_destaque['qt_parcela'];
                        $valorParcela = calcularValorParcela($valorFinal, $qtParcelas); 
                        echo "<div class='parcela2'>";
                        echo "<p>" . $qtParcelas . "x de R$ " . number_format($valorParcela, 2, ',', '.') . " OU</p>";
                        echo "</div>";
                    
                        echo "<div class='div-a'>";
                        echo "<a href='#' class='preco'>R$ " . number_format($valorFinal, 2, ',', '.') . "</a>";
                        echo "<a href='adicionar_carrinho.php?id=" . $info_destaque['cod_produto'] . "' class='link-produto' style='color: black'>Adicionar ao Carrinho</a>";
                        echo "</div>";
                    
                        echo "</div>"; 
                        echo "</a>"; 
                        echo "</div>"; 
                        echo "</div>"; 
                    }
                
                ?>

            </div>
        </div>

        <section>
            <h3 class="destc2">Produtos</h3>

            <div class="produtos">
              <?php
              $rs = mysqli_query($con, $sql);

              while ($info = mysqli_fetch_assoc($rs)) {
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
            
                echo "<p class='p-ava'>(" . ($info['qtd_ocorrencia'] ?: 0) . ")</p>"; 
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
            
                echo "<div class='div-a2'>";
                echo "<a href='adicionar_carrinho.php?id=" . $info['cod_produto'] . "' class='link-produto' style='color: black'>Adicionar ao Carrinho</a>";
                echo "</div>";
                echo "</div>"; 
            
                echo "</a>"; 
                echo "</div>"; 
            }
              
              
              ?>
            </div>
        </section>
    
    </main>

    <footer>
    <?php
  include '../include/footer.php';
  ?>
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>