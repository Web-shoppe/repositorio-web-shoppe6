<?php
session_start();
$usuario_id = $_SESSION['UsuarioID'] ?? null;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/prod.css">
    <link rel="stylesheet" href="../CSS/card-sec.css">
    <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Web Shoppe</title>


    <?php
$con = mysqli_connect("localhost", "root", "", "web_shoppe");

$id_produto = isset($_GET['id_produto']) ? intval($_GET['id_produto']) : 0;

if ($id_produto > 0) {
    $query = "SELECT * FROM produto WHERE cod_produto = $id_produto";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $produto = mysqli_fetch_assoc($result);

        $id_loja = $produto['id_loja'];

        echo "<h1>" . htmlspecialchars($produto['nome']) . "</h1>";
        echo "<p>Descrição: " . htmlspecialchars($produto['descricao_produto']) . "</p>";
        echo "<p>Preço: " . htmlspecialchars($produto['valor_venda_produto']) . "</p>";
        echo "<p>Desconto: " . htmlspecialchars($produto['valor_desconto']) . "</p>";
        echo "<p>Fabricante: " . htmlspecialchars($produto['fabricante_produto']) . "</p>";
        echo "<p>Garantia: " . htmlspecialchars($produto['garantia_produto']) . "</p>";
        echo "<p>Quantidade em Estoque: " . htmlspecialchars($produto['qt_produto_estocado']) . "</p>";

        for ($i = 1; $i <= 5; $i++) {
            $foto = $produto["foto_prod$i"];
            if ($foto) {
                echo "<img src='../caminho/para/imagens/$foto' alt='Foto do produto' style='width:100px;height:100px;'>";
            }
        }

        echo "<p>ID da Loja: $id_loja</p>";
    } else {
        echo "Produto não encontrado.";
    }
} else {
}
?>


    <style>
        .estrela-img {
            width: 50px; 
            height: 50px;
            border: none; 
            background: none; 
            padding: 0; 
        }
        .btn-estrela {
            border: none; 
            background: none; 
            padding: 0; 

        }
        .estrela:focus {
            outline: none; 
        }
    </style>
</head>

<body>

    <?php
    include '../include/header_inicio.php';
    ?>

    <main>

        <?php
        $con = mysqli_connect("localhost", "root", "", "web_shoppe");
        if (!$con) {
            die("Erro de conexão: " . mysqli_connect_error());
        }

        $idProduto = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($idProduto <= 0) {
            die("Produto não encontrado.");
        }

        $sql = "SELECT p.*, l.nome AS nome_loja, l.foto_loja, calc_feed.* 
        FROM produto p 
        LEFT JOIN loja l ON p.id_loja = l.cod_loja
        LEFT JOIN 
        (SELECT f.id_produto AS prod, COUNT(*) AS qtd_ocorrencia, SUM(f.nota) AS soma, AVG(f.nota) AS media 
        FROM feedback f
        GROUP BY f.id_produto) AS calc_feed
        ON p.cod_produto = calc_feed.prod
        WHERE p.cod_produto = $idProduto;";
        
        $rs = mysqli_query($con, $sql);
        $info = mysqli_fetch_assoc($rs);

        if (!$info) {
            die("Produto não encontrado.");
        }

        function calcularValorParcela($valorDesconto, $qtParcelas)
        {
            if ($qtParcelas <= 0) {
                return 0; 
            }
            return round($valorDesconto / $qtParcelas, 2);
        }
        ?>

        <section class="section-prod">
            <div class="div-img">
                <div class="div-img2">
                    <?php
                    $imagemProduto2 = $info['foto_prod2'] ?: 'sem imagem.png';      
                    echo "<img src='../IMAGENS/produto/$imagemProduto2' alt='' class='sem3' style='width: 100px;'>";
                    $imagemProduto3 = $info['foto_prod3'] ?: 'sem imagem.png';      
                    echo "<img src='../IMAGENS/produto/$imagemProduto3' alt='' class='sem3' style='width: 100px;'>";
                    $imagemProduto4 = $info['foto_prod4'] ?: 'sem imagem.png';      
                    echo "<img src='../IMAGENS/produto/$imagemProduto4' alt='' class='sem3' style='width: 100px;'>";
                    $imagemProduto5 = $info['foto_prod5'] ?: 'sem imagem.png';      
                    echo "<img src='../IMAGENS/produto/$imagemProduto5' alt='' class='sem3' style='width: 100px;'>";
        
                    ?>
                </div>
                <div>

                    <?php
                    $imagemProduto = $info['foto_prod'] ?: 'sem imagem.png';      
                    echo "<img src='../IMAGENS/produto/$imagemProduto' alt='' class='sem3'>"; 
                    
                    ?>
                </div>
            </div>

            <div class="div-preco1">
                <div>
        <?php


        $valorVendaProduto = $info['valor_venda_produto']; 
        $valorDesconto = $info['valor_desconto'];
        

        $valorFinal = $valorVendaProduto - $valorDesconto;


        ?>
        <h1 class="preco_prod"><span>R$ <?php echo number_format($valorFinal, 2, ',', '.'); ?></span> à vista</h1>
        <p class="p-parcela"><span>ou</span> <?php echo $info['qt_parcela']; ?>x
        <span>
            <?php
   
   $qtParcelas = $info['qt_parcela'];
   $valorParcela = calcularValorParcela($valorFinal, $qtParcelas);
   
   echo "R$" . number_format($valorParcela, 2, ',', '.');
   ?>
            </span>
        </p>
    </div>


                <div>
                    <label for="quant">Quantidade:</label>
                    <select name="quant" id="quant">
                        <option value="1">1 unidade</option>
                        <option value="2">2 unidade</option>
                        <option value="3">3 unidade</option>
                        <option value="4">4 unidade</option>
                    </select>
                </div>
                
                <?php
                $con = mysqli_connect("localhost", "root", "", "web_shoppe");
                if (!$con) {
                    die("Erro de conexão: " . mysqli_connect_error());
                }
                
                
                
                ?>
                <div class="botao">
                    <button class="compra"><a href="checkout.php">Compra o produto</a></button>
                    <?php
                    echo "<button class='carrinho'><a href='adicionar_carrinho.php?id=" . $info['cod_produto'] . "'>Adicionar ao Carrinho</a></button>"?>   
                </div>
            </div>
    </section>
            
        <section class="envio">
            <div>
            
                <h1 class="h1-nome"><?php echo $info['nome']; ?></h1>
                <div class="div-estrela">

                    <?php
                    echo "<div class='estrela'>";
                    $media = $info['media'];
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

                    ?>
                    <p class='p-ava'><?php echo $info['qtd_ocorrencia']; ?></p>
                </div>

            </div>
            <?php 
            echo "<a href='loja.php?id=" . $info['id_loja'] . "' class='link-produto'>";
            echo "<div class='motorplus'>";
            $imagemLoja = $info['foto_loja'] ?: 'sem imagem.png';       
            echo "<img src='../IMAGENS/loja/$imagemLoja' alt=''";
            echo "style='max-width: 100px; border-radius: 50px; border: 2px solid gray'>";
            echo " <p style='color: black'>". $info['nome_loja'] ."</p>";
            echo "</div>";
            echo "</div>";
            echo "</a>";
                       
            ?>
            
            <section class="envio2">
                
                <div class="card shadow p-4">
                    <h2 class="h4 mb-4">Calcule o frete e o prazo de entrega</h2>
                    
                    <div class="mb-3">
                        <label for="cep" class="form-label">Digite seu CEP:</label>
                        <div class="input-group">
                            <input type="text" id="cep" name="cep" placeholder="00000-000" class="form-control" required>
                            <button id="calcularFrete" class="btn btn-primary" onclick="calcularFrete()">Calcular Frete</button>
                        </div>
                        <small class="form-text text-muted">Informe seu CEP para ver as opções de entrega disponíveis.</small>
                    </div>
                    
                    <div id="opcoesEntrega" style="display: none;" class="mt-4">
                        <h3 class="h5">Opções de Envio:</h3>
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="frete" id="sedex" value="sedex">
                        <label class="form-check-label" for="sedex">
                            SEDEX - <span id="sedexPrazo">5 dias úteis</span> - R$ <span id="sedexValor">20,00</span>
                        </label>
                    </div>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="frete" id="pac" value="pac">
                        <label class="form-check-label" for="pac">
                            PAC - <span id="pacPrazo">10 dias úteis</span> - R$ <span id="pacValor">10,00</span>
                        </label>
                    </div>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="frete" id="mini-envios" value="mini-envios">
                        <label class="form-check-label" for="mini-envios">
                            Mini Envios - <span id="miniEnviosPrazo">12 dias úteis</span> - R$ <span id="miniEnviosValor">5,00</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</section>

<section class="info">
    <div>
        <div class="h2">
            <h2>Informações Sobre o Produto</h2>
        </div>
        <div class="conteudo">
            <?php
            $descricao = $info['descricao_produto'];
            $limiteCaracteres = 100; 
            
            if (strlen($descricao) > $limiteCaracteres) {
                $descricaoCortada = substr($descricao, 0, $limiteCaracteres) . '...';
                echo "<h3 id='descricao'>{$descricaoCortada}</h3>";
                echo '<button id="verMaisBtn" onclick="mostrarDescricao()" style="background-color: #007BFF; color: white; border: none; padding: 10px 15px; cursor: pointer;">Ver Mais</button>';
                echo '<div id="descricaoCompleta" style="display:none;">';
                echo "<h3>{$descricao}</h3>";
                echo '</div>';
            } else {
                echo "<h3>{$descricao}</h3>";
            }
            ?>
        </div>
    </div>

            <div class="div-ava">
                <form action="processar_avaliacao.php" method="POST">
                        <div class="avaliacao-input">
                            <div class="rating-container">
                                <h2>Avalie esse produto</h2>
                                <div class="stars">
                                <input type="radio" id="star5" name="rating" value="5" onclick="setRating(5)">
                                <label for="star5" title="5 estrelas">&#9733;</label>
                                
                                <input type="radio" id="star4" name="rating" value="4" onclick="setRating(4)">
                                <label for="star4" title="4 estrelas">&#9733;</label>
                                
                                <input type="radio" id="star3" name="rating" value="3" onclick="setRating(3)">
                                <label for="star3" title="5 estrelas">&#9733;</label>
                                
                                <input type="radio" id="star2" name="rating" value="2" onclick="setRating(2)">
                                <label for="star2" title="4 estrelas">&#9733;</label>
                                
                                <input type="radio" id="star1" name="rating" value="1" onclick="setRating(1)">
                                <label for="star1" title="5 estrela">&#9733;</label>
                                </div>
                            </div>
                        <input type="hidden" name="nota" id="nota" value="">
                    </div>
    
                    <div class="comentario-input">
                            <label for="comment">Comentário:</label>
                            <textarea id="comentario" name="comentario" rows="5" placeholder="Escreva seu comentário aqui..." required></textarea>
                    </div>
                    <input type="hidden" name="cod_usuario" value="<?php echo $_SESSION['UsuarioID']; ?>">
    
                    <input type="hidden" name="data" id="data" value="<?php echo date('Y-m-d'); ?>">
    
                    <?php
                    $idProduto = isset($_GET['id']) ? intval($_GET['id']) : 0;
                    echo "<input type='hidden' name='cod_produto' value='$idProduto'>";
                        ?>
                    <button type="submit" class="botao-bonito">Enviar avaliação</button>
                </form>
            </div>
</section>


        <section>   
            <section>
                <h1 class="ava">Outras Avaliações:</h1>
                <div class="avaliacao">
                <?php
                $con = mysqli_connect("localhost", "root", "", "web_shoppe");
                if (!$con) {
                    die("Erro de conexão: " . mysqli_connect_error());
                }

                $idProduto = isset($_GET['id']) ? intval($_GET['id']) : 0;

                if ($idProduto <= 0) {
                    die("Produto não encontrado.");
                }

                $sql = "SELECT f.comentario, f.nota, f.data 
                        FROM feedback f 
                        WHERE f.id_produto = $idProduto 
                        ORDER BY f.data DESC";

                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        
                        echo "<div class='avaliacao2'>"; 
                        $av = $row['nota'];
                        $inteiro = floor($av); 
                        $metade = ($av - $inteiro) >= 0.5;
                        echo "<small>Data: " . date('d/m/Y', strtotime($row['data'])) . "</small>";
                        
                        echo "<div class='star'>";
                        for ($i = 1; $i <= $inteiro; $i++) {
                            echo "<img src='../IMAGENS/home/estrela-cheia.png' alt='' class='estrela1'>"; 
                        }
                        
                        if ($metade) {
                            echo "<img src='../IMAGENS/home/estrela-metade.png' alt='' class='estrela1'>";
                        }
                        
                        for ($i = $inteiro + ($metade ? 1 : 0); $i < 5; $i++) {
                            echo "<img src='../IMAGENS/home/estrela-vazia.png' alt='' class='estrela1'>"; 
                        }
                        echo "</div>";

                        echo "<p>Comentário: " . htmlspecialchars($row['comentario']) . "</p>"; 
                        echo "</div>"; 
                    }
                } else {
                    echo "<p>Não há avaliações para este produto.</p>";
                }
                
                echo "</div>";

                $con->close();
                ?>
                </div>
            </section>
            

            <h2 class="outros">Outros produtos</h2>
            <section class="section-outros">
                    <?php

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

                    function calcularValorParcela2($valorDesconto, $qtParcelas)
                    {
                        if ($qtParcelas <= 0) {
                            return 0; 
                        }
                        return round($valorDesconto / $qtParcelas, 2); 
                    }

                    $con = mysqli_connect("localhost", "root", "", "web_shoppe");
                    if (!$con) {
                        die("Erro de conexão: " . mysqli_connect_error());
                    }

                    $idProdutoDestaque = isset($_GET['id']) ? intval($_GET['id']) : 0;

                    if ($idProdutoDestaque <= 0) {
                        die("Produto em destaque não encontrado.");
                    }

                    function calcularValorFinal($valorVenda, $valorDesconto) {
                        return $valorVenda - $valorDesconto; 
                      }

                    $sqlDestaque = "SELECT p.*, l.cod_loja FROM produto p 
                INNER JOIN loja l ON p.id_loja = l.cod_loja 
                WHERE p.cod_produto = $idProdutoDestaque;";
                    $rsDestaque = mysqli_query($con, $sqlDestaque);
                    $infoDestaque = mysqli_fetch_assoc($rsDestaque);
                    $idLoja = $infoDestaque['id_loja']; 
                    
                    $sqlOutrosProdutos = "SELECT p.*, 
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
WHERE p.id_loja = $idLoja 
AND p.cod_produto != $idProdutoDestaque
AND p.qt_produto_estocado > 0 
AND ( p.id_loja != (SELECT cod_loja FROM loja WHERE id_usu = $usuario_id ) OR NOT EXISTS (SELECT 1 FROM loja WHERE id_usu = $usuario_id))";

                    $rsOutros = mysqli_query($con, $sqlOutrosProdutos);

                    while ($info = mysqli_fetch_assoc($rsOutros)) {
                        echo "<div class='div-card-nova'>"; 
                        echo "<a href='produto.php?id=" . $info['cod_produto'] . "' class='link-produto'>"; 
                        echo "<div>";
                        $imagemProduto = $info['foto_prod'] ?: 'sem imagem.png'; 
                        echo "<img src='../IMAGENS/produto/$imagemProduto' alt='' class='sem2'>";
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
                    
                        echo "<div class='div-a2'>";
                        echo "<a href='adicionar_carrinho.php?id=" . $info['cod_produto'] . "' class='link-produto' style='color: black'>Adicionar ao Carrinho</a>";
                        echo "</div>";
          echo "</div>";
          echo "</div>";
          
          echo "</a>";
          echo "</div>"; 
                    }
                    ?>
            </section>
    </main>

    <?php
    include '../include/footer.php';
    ?>
</body>
<script src="../JAVASCRIPT/produto.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</html>
<style>
.card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.card h2 {
    color: #333;
}

.card .form-label {
    font-size: 16px;
    color: #495057;
}

.card .input-group {
    max-width: 400px;
}

.card .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    transition: background-color 0.3s ease;
}

.card .btn-primary:hover {
    background-color: #0056b3;
}

.card .list-group-item {
    border: 1px solid #e9ecef;
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
    transition: background-color 0.2s ease;
}

.card .list-group-item:hover {
    background-color: #f8f9fa;
}

.card .form-check-input:checked + .form-check-label {
    font-weight: bold;
    color: #007bff;
}

.card .form-check-label span {
    font-weight: bold;
    color: #495057;
}

.comentario-input label{
    font-size: 23px;
}

.rating-container {
  background: #fff;
  padding: 20px 40px 0px 0px;
}

.rating-container h2{
    font-size: 35px;
    width: 320px;
}

.stars {
  display: flex;
  justify-content: start;
  gap: 5px;
  flex-direction: row-reverse;
}

.stars input {
  display: none;
}

.stars label {
  font-size: 3.5rem;
  color: #ccc;
  cursor: pointer;
  transition: color 0.2s;
}

.stars input:checked ~ label {
  color: #ffc107;
}

.stars label:hover,
.stars label:hover ~ label {
  color: #ffda44;
}

.comment-container {
  width: 100%;
  max-width: 500px;
  background: #fff;
  padding: 20px 30px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.comment-container h2 {
  margin-bottom: 15px;
  font-size: 1.8rem;
  color: #333;
  text-align: center;
}

.comment-form {
  display: flex;
  flex-direction: column;
}

.comment-form label {
  margin-top: 10px;
  font-size: 1rem;
  color: #555;
}

.comment-form input,
.comment-form textarea {
  margin-top: 5px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
  resize: none;
}

.comment-form input:focus,
.comment-form textarea:focus {
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.comment-form button {
  margin-top: 15px;
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.comment-form button:hover {
  background-color: #0056b3;
}

.botao-bonito {
            background-color: #6D09A4; 
            color: white; 
            font-size: 16px; 
            padding: 12px 24px; 
            border: none;
            border-radius: 8px; 
            cursor: pointer; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
            transition: background-color 0.3s, transform 0.3s;
            margin-left: 65px;
        }

        .botao-bonito:hover {
            background-color: #6D09A4; 
            transform: scale(1.05); 
        }

        .botao-bonito:active {
            transform: scale(0.98);
        }
</style>