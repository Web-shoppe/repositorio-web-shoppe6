<?php
$categorias = [
    "celulares_e_smartphones",
    "tv_e_video",
    "console_e_games",
    "audio",
    "cameras_e_drones",
    "telefonia_fixa",
    "informatica",
    "acessorio_e_perifericos",
    "pc_gamer",
    "agro_industria_e_comercio",
    "sinalizacao_e_seguranca",
    "eletrodomestico",
    "eletroportaveis",
    "ar_e_ventilacao",
    "moveis_e_decoracao",
    "casa_e_construcao",
    "utilidade_domestica",
    "cama_mesa_e_banho",
    "instrumentos_musicais",
    "musica",
    "filme_e_series",
    "artigos_e_festas",
    "artesanato",
    "mercado",
    "automotivo",
    "brinquedos",
    "bebes",
    "gift_cards",
    "pet_shop",
    "malas_mochilas_e_acessorios",
    "papelaria",
    "vale_presente",
    "esporte_fitness_e_lazer",
    "saude_e_bem_estar",
    "suplementos_e_vitaminas",
    "roupas_e_aces_esportivos",
    "beleza_e_perfumaria",
    "moda",
    "relogios",
    "empresas_americanas",
    "cotacoes_online",
    "solucoes_corporativas",
    "receba_em_3_horas",
    "produtos_internacionais",
    "sustentabilidade",
    "outlet_americanas"
];

$diretorio = "categorias_html";

if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
}

foreach ($categorias as $categoria) {
    $nomeArquivo = "cat_" . $categoria . ".php";

    $caminhoArquivo = $diretorio . "/" . $nomeArquivo;

    $categoriaExibida = str_replace('_', ' ', $categoria);
    $categoriaExibida = htmlspecialchars(ucwords($categoriaExibida)); 

    $conteudo = <<<HTML
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Automotivo - Web Shoppe</title>
  <link rel="stylesheet" href="../CSS/categoria_card.css">
  <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

  <?php
  require_once '../include/header_inicio.php';

  function calcularValorFinal(\$valorVenda, \$valorDesconto) {
    return \$valorVenda - \$valorDesconto; // Subtrai o desconto do valor de venda
  }   

  // Função para calcular porcentagem de desconto
  function calcularPorcentagemDesconto(\$valorCompra, \$valorDesconto) {
      if (\$valorCompra <= 0) {
          return 0; // Evitar divisão por zero
      }
      if (\$valorDesconto > \$valorCompra) {
          return 0; // Desconto não pode ser maior que o valor da compra
      }
      \$desconto = (\$valorCompra - \$valorDesconto) / \$valorCompra * 100;
      return round(\$desconto, 0); // Retorna o valor arredondado
  }

  // Função para limitar caracteres da descrição
  function limitarCaracteres(\$texto, \$limite) {
      if (strlen(\$texto) > \$limite) {
          return substr(\$texto, 0, \$limite) . '...'; // Adiciona reticências
      }
      return \$texto; // Retorna o texto original se estiver dentro do limite
  }

  // Função para calcular valor da parcela
  function calcularValorParcela(\$valorDesconto, \$qtParcelas) {
      if (\$qtParcelas <= 0) {
          return 0; // Evita divisão por zero
      }
      return round(\$valorDesconto / \$qtParcelas, 2); // Retorna o valor da parcela arredondado
  }
  ?>

  <main>
    <hr class="divisa2">
    <h3 class="cat-titulo">Automotivo</h3>
    
    <div class="div-card">
        <?php

        // Conexão com o banco de dados
        \$con = mysqli_connect("localhost", "root", "", "web_shoppe");
        if (!\$con) {
            die("Erro de conexão: " . mysqli_connect_error());
        }

        \$sql = "SELECT p.*, 
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
                WHERE p.categoria = 'automotivo'";

        \$rs = mysqli_query(\$con, \$sql);

        while (\$info = mysqli_fetch_assoc(\$rs)) {
            echo "<div class='card-cat'>"; // Início do card de cada produto
            echo ""; 
            echo "<a href='produto.php?id=' . \$info['cod_produto'] . class='link-produto'>"; // Link para a página do produto
            echo "<div>";
            \$imagemProduto = \$info['foto_prod'] ?: 'sem imagem.png'; // Usa imagem padrão se não houver imagem
            echo "<img src='../IMAGENS/produto/\$imagemProduto' alt='' class='sem'>"; // Imagem do produto
            echo "</div>";
            echo "<div>";
        
            // Limitar a descrição do produto
            echo "<h4 class='titulo-card'>" . htmlspecialchars(\$info['nome']) . "</h4>";
            \$descricaoLimitada = limitarCaracteres(\$info['nome_loja'], 100);
            echo "<p class='titulo-card'>" . htmlspecialchars(\$descricaoLimitada) . "</p>";
        
            // Exibir Estrelas com Base na Média
            echo "<div class='estrela'>";
            \$media = \$info['media'] ?: 0; // Se não houver média, coloca 0
            \$inteiro = floor(\$media); // Parte inteira da média
            \$metade = (\$media - \$inteiro) >= 0.5; // Verifica se há uma metade
        
            // Exibir estrelas cheias
            for (\$i = 1; \$i <= \$inteiro; \$i++) {
                echo "<img src='../IMAGENS/home/estrela-cheia.png' alt='' class='estrela1'>"; // Estrela preenchida
            }
        
            // Exibir metade da estrela, se necessário
            if (\$metade) {
                echo "<img src='../IMAGENS/home/estrela-metade.png' alt='' class='estrela1'>"; // Metade da estrela
            }
        
            // Exibir estrelas vazias
            for (\$i = \$inteiro + (\$metade ? 1 : 0); \$i < 5; \$i++) {
                echo "<img src='../IMAGENS/home/estrela-vazia.png' alt='' class='estrela1'>"; // Estrela vazia
            }
        
            echo "<p class='p-ava'>" . \$info['qtd_ocorrencia'] ?: 0 . "</p>"; // Número de avaliações
            echo "</div>"; // Fecha div.estrela
        
            // Exibição do preço e desconto
            \$valorCompra = \$info['valor_venda_produto'];
            \$valorDesconto = \$info['valor_desconto'];
            
            // Calculando o valor final com desconto
            \$valorFinal = calcularValorFinal(\$valorCompra, \$valorDesconto); // Chama a função que subtrai o valor do desconto
            
            // Inicializa a variável para a porcentagem de desconto
            \$porcentagemDesconto = 0;
            
            // Se o valor do desconto não for zero, calcula a porcentagem
            if (\$valorDesconto > 0 && \$valorCompra > 0) { // Verifica se o desconto é maior que zero
                \$porcentagemDesconto = (\$valorDesconto / \$valorCompra) * 100; // Calcula corretamente a porcentagem
            }
            
            // Exibe o preço original e o valor com desconto
            if (\$valorDesconto > 0) { // Se o valor de desconto for maior que zero, exibe o preço riscado
                echo "<div class='preco'>";
                echo "<p class='p-preco' style='text-decoration: line-through;'>R$" . number_format(\$valorCompra, 2, ',', '.') . "</p>";
                echo "<div class='desc'>";
                echo "<p>-" . number_format(\$porcentagemDesconto, 0, ',', '.') . "%</p>"; // Exibe a porcentagem de desconto
                echo "</div>";
            }
        
            echo "</div>"; // Fecha div-preco
        
            // Exibe o preço final (com desconto) ou o preço normal, dependendo do valor do desconto
            echo "<a href='#' class='preco'>R$ " . number_format(\$valorFinal, 2, ',', '.') . "</a>"; // Exibe o preço final
        
            // Cálculo do valor da parcela
            \$qtParcelas = \$info['qt_parcela'];
            \$valorParcela = calcularValorParcela(\$valorFinal, \$qtParcelas); // Usando o valor final calculado no parcelamento
            echo "<div class='parcela'>";
            echo "<p>OU</p>";
            echo "<p>" . \$qtParcelas . "x de R$ " . number_format(\$valorParcela, 2, ',', '.') . "</p>";
            echo "</div>";
        
            // Link para adicionar ao carrinho
            echo "<div class='div-a2'>";
            echo "<a href='adicionar_carrinho.php?id=" . \$info['cod_produto'] . "' class='link-produto' style='color: black'>Adicionar ao Carrinho</a>";
            echo "</div>";
            echo "</div>"; // Fecha div-card-nova
        
            echo "</a>"; // Fecha o link do produto
            echo "</div>"; // Fecha div-card-nova1
        }
        ?>
    </div> 
  </main>
  
  <?php
  include '../include/footer.php';
  ?>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
HTML;

    file_put_contents($caminhoArquivo, $conteudo);
}

echo "Páginas HTML criadas no diretório '$diretorio'.\n";
