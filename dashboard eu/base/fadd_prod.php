<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UsuarioID'])) {
    header("Location: index.php");
    exit;
}

require_once '../base/conex.php';

$sql = "SELECT cod_loja FROM loja WHERE id_usu = " . $_SESSION['UsuarioID'] . ";";
$list = mysqli_query($con, $sql);
$lojista = mysqli_fetch_assoc($list);

$list = mysqli_query($con, $sql);
$loja = mysqli_fetch_assoc($list);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #E4E9F7; 
        }
        .form-container {
            display: flex; 
            justify-content: space-between;
        }
        main {
            background-color: #ffffff; 
            padding: 2rem; 
            border-radius: 12px; 
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); 
            flex: 1; 
            margin-right: 20px; 
        }
        h1 {
            color: #6D09A4; 
            margin-bottom: 1.5rem;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #6D09A4;
            border: none; 
        }
        .btn-primary:hover {
            background-color: #5b0080; /
        }
        #previewImage {
            max-width: 100%; 
            height: auto; 
            display: none; 
            margin-top: 1rem; 
            border-radius: 8px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-card {
            background-color: #ffffff; 
            border-radius: 12px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
            padding: 1rem; 
            flex-basis: 400px; 
            position: relative; 
            margin-left: 20px; 
        }
        .product-card img {
            border-radius: 8px; 
        }
        .product-info {
            margin-top: 1rem; 
        }
        .product-info h4 {
            color: #333; 
        }
        .product-value {
            font-weight: bold; 
            color: #28a745; 
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Adicionar Produto</h1>
        <div class="form-container">
            <main>
                <form action="base/add.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tipo" value="prod">
                    <input type="hidden" name="id_loja" value="<?php echo $loja['cod_loja']; ?>">
                    <div class="mb-3">
                        <label for="foto_prod" class="form-label">Foto do Produto</label>
                        <input type="file" name="foto_prod" id="foto_prod" class="form-control" required>
                        <img id="previewImage" src="" alt="Pré-visualização da imagem" />
                    </div>
                    <div class="mb-3">
                        <label for="nome_prod" class="form-label">Nome do Produto</label>
                        <input type="text" name="nome" id="nome_prod" class="form-control" placeholder="Digite o nome do produto" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_produto" class="form-label">Descrição do Produto</label>
                        <textarea name="descricao_produto" id="descricao_produto" class="form-control" rows="4" maxlength="1000" placeholder="Digite a descrição (máx 1000 caracteres)" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="garantia_produto" class="form-label">Garantia do Produto</label>
                        <input type="text" name="garantia_produto" id="garantia_produto" class="form-control" placeholder="Digite a garantia do produto" required>
                    </div>
                    <div class="mb-3">
                        <label for="fabricante_produto" class="form-label">Fabricante do Produto</label>
                        <input type="text" name="fabricante_produto" id="fabricante_produto" class="form-control" placeholder="Digite o fabricante do produto" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor_venda_produto" class="form-label">Valor de Venda (R$)</label>
                        <input type="number" name="valor_venda_produto" id="valor_venda_produto" class="form-control" step="0.01" placeholder="Digite o valor" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor_desconto" class="form-label">Valor do Desconto (R$)</label>
                        <input type="number" name="valor_desconto" id="valor_desconto" class="form-control" step="0.01" placeholder="Digite o desconto">
                    </div>
                    <div class="mb-3">
                        <label for="porcentagem_desconto" class="form-label">Porcentagem do Desconto</label>
                        <input type="text" id="porcentagemDisplay" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="qt_produto_estocado" class="form-label">Quantidade em Estoque</label>
                        <input type="number" name="qt_produto_estocado" id="qt_produto_estocado" class="form-control" placeholder="Digite a quantidade" required>
                    </div>
                    <div class="mb-3">
                        <label for="qt_parcela" class="form-label">Quantidade de Parcelas Permitidas</label>
                        <input type="number" name="qt_parcela" id="qt_parcela" class="form-control" placeholder="Digite a quantidade de parcelas" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar Produto</button>
                </form>
            </main>

            <div class="product-card">
                <h4>Pré-visualização do Produto</h4>
                <img id="cardImage" src="" alt="Imagem do Produto" style="display:none;">
                <div class="product-info">
                    <h4 id="cardNome">Nome do Produto</h4>
                    <p id="cardDescricao">Descrição do Produto: <span id="cardDescricaoTexto">Nenhuma descrição fornecida.</span></p>
                    <p>Garantia do Produto: <span id="cardGarantia">Nenhuma garantia fornecida.</span></p>
                    <p>Fabricante do Produto: <span id="cardFabricante">Nenhum fabricante fornecido.</span></p>
                    <p>Valor de Venda: <span class="product-value" id="cardValorVenda">R$ 0,00</span></p>
                    <p>Valor de Desconto: <span class="product-value" id="cardValorDesconto">R$ 0,00</span></p>
                    <p>Porcentagem do Desconto: <span id="cardPorcentagemDisplay">0%</span></p>
                    <p>Quantidade em Estoque: <span class="product-value" id="cardEstoque">0</span></p>
                    <p>Parcelas Permitidas: <span id="cardParcelas">0</span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("foto_prod").addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById("previewImage");
                    preview.src = event.target.result;
                    preview.style.display = "block"; 
                    document.getElementById("cardImage").src = event.target.result; 
                    document.getElementById("cardImage").style.display = "block"; 
                }
                reader.readAsDataURL(file);
            }
        });

        const updateCard = () => {
            const nome = document.getElementById("nome_prod").value || "Nome do Produto";
            const descricao = document.getElementById("descricao_produto").value || "Nenhuma descrição fornecida.";
            const garantia = document.getElementById("garantia_produto").value || "Nenhuma garantia fornecida.";
            const fabricante = document.getElementById("fabricante_produto").value || "Nenhum fabricante fornecido.";
            const valorVenda = document.getElementById("valor_venda_produto").value || "0.00";
            const valorDesconto = document.getElementById("valor_desconto").value || "0.00";
            const porcentagemDisplay = document.getElementById("porcentagemDisplay").value || "0%";
            const estoque = document.getElementById("qt_produto_estocado").value || "0";
            const parcelas = document.getElementById("qt_parcela").value || "0";

            document.getElementById("cardNome").innerText = nome;
            document.getElementById("cardDescricaoTexto").innerHTML = descricao.replace(/\n/g, "<br>"); 
            document.getElementById("cardGarantia").innerText = garantia;
            document.getElementById("cardFabricante").innerText = fabricante; 
            document.getElementById("cardValorVenda").innerText = `R$ ${parseFloat(valorVenda).toFixed(2)}`;
            document.getElementById("cardValorDesconto").innerText = `R$ ${parseFloat(valorDesconto).toFixed(2)}`;
            document.getElementById("cardPorcentagemDisplay").innerText = porcentagemDisplay;
            document.getElementById("cardEstoque").innerText = estoque;
            document.getElementById("cardParcelas").innerText = parcelas; 
        };

        document.getElementById("nome_prod").addEventListener("input", updateCard);
        document.getElementById("descricao_produto").addEventListener("input", updateCard);
        document.getElementById("garantia_produto").addEventListener("input", updateCard);
        document.getElementById("fabricante_produto").addEventListener("input", updateCard);
        document.getElementById("valor_venda_produto").addEventListener("input", updateCard);
        document.getElementById("valor_desconto").addEventListener("input", function() {
            const valorVenda = parseFloat(document.getElementById("valor_venda_produto").value) || 0;
            const desconto = parseFloat(this.value) || 0;
            const porcentagem = (desconto / valorVenda) * 100;
            document.getElementById("porcentagemDisplay").value = isNaN(porcentagem) ? "0%" : porcentagem.toFixed(0) + "%";
            updateCard(); 
        });
        document.getElementById("qt_produto_estocado").addEventListener("input", updateCard);
        document.getElementById("qt_parcela").addEventListener("input", updateCard);
    </script>
</body>
</html>
