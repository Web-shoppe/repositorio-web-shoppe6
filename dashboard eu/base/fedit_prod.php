<?php
require_once "conexao.php";

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$conex = mysqli_connect("localhost", "root", "", "web_shoppe");
if ($id > 0) {
    $stmt = $conex->prepare("SELECT * FROM produto WHERE cod_produto = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "ID inválido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #e9ecef;
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
            background-color: #5b0080; 
        }
        .product-card {
            background-color: #ffffff; 
            border-radius: 12px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
            padding: 1rem; 
            flex-basis: 400px; 
            position: relative; 
            max-height: 600px; 
            overflow: auto; 
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
        .product-info p {
            margin: 0.5rem 0; 
            white-space: normal; 
            word-wrap: break-word; 
        }
        .product-value {
            font-weight: bold; 
            color: #28a745; 
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Editar Produto</h1>
        <div class="form-container">
            <main>
                <form action="base/edit.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tipo" value="prod">
                    <input type="hidden" name="cod_produto" value="<?= htmlspecialchars($dados['cod_produto']) ?>" readonly>

                    <div class="mb-3">
                        <label for="foto_prod" class="form-label">Foto do Produto</label>
                        <input type="file" name="foto_prod" id="foto_prod" class="form-control">
                        <img id="previewImage" src="<?= htmlspecialchars($dados['foto_prod']) ?>" alt="Pré-visualização da imagem" style="max-width: 100%; margin-top: 1rem; border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label for="nome_prod" class="form-label">Nome do Produto</label>
                        <input type="text" name="nome" id="nome_prod" class="form-control" value="<?= htmlspecialchars($dados['nome']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_produto" class="form-label">Descrição do Produto</label>
                        <textarea name="descricao_produto" id="descricao_produto" class="form-control" rows="4" maxlength="1000" required><?= htmlspecialchars($dados['descricao_produto']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="garantia_produto" class="form-label">Garantia do Produto</label>
                        <input type="text" name="garantia_produto" id="garantia_produto" class="form-control" value="<?= htmlspecialchars($dados['garantia_produto']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fabricante_produto" class="form-label">Fabricante do Produto</label>
                        <input type="text" name="fabricante_produto" id="fabricante_produto" class="form-control" value="<?= htmlspecialchars($dados['fabricante_produto']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor_venda" class="form-label">Valor de Venda (R$)</label>
                        <input type="number" name="valor_venda" id="valor_venda" class="form-control" value="<?= htmlspecialchars($dados['valor_venda_produto']) ?>" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="valor_desconto" class="form-label">Valor do Desconto (R$)</label>
                        <input type="number" name="valor_desconto" id="valor_desconto" class="form-control" value="<?= htmlspecialchars($dados['valor_desconto']) ?>" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="qt_produto_estocado" class="form-label">Quantidade em Estoque</label>
                        <input type="number" name="qt_produto_estocado" id="qt_produto_estocado" class="form-control" value="<?= htmlspecialchars($dados['qt_produto_estocado']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col"><input type="submit" value="Atualizar" class="btn btn-primary"></div>
                    </div>
                </form>
            </main>

            <div class="product-card">
                <h4>Pré-visualização do Produto</h4>
                <img id="cardImage" src="<?= htmlspecialchars($dados['foto_prod']) ?>" alt="Imagem do Produto" style="display:block; max-width: 100%;">
                <div class="product-info">
                    <h4 id="cardNome"><?= htmlspecialchars($dados['nome']) ?></h4>
                    <p id="cardDescricao">Descrição do Produto: <span id="cardDescricaoTexto"><?= nl2br(htmlspecialchars($dados['descricao_produto'])) ?></span></p>
                    <p>Garantia do Produto: <span id="cardGarantia"><?= htmlspecialchars($dados['garantia_produto']) ?></span></p>
                    <p>Fabricante do Produto: <span id="cardFabricante"><?= htmlspecialchars($dados['fabricante_produto']) ?></span></p>
                    <p>Valor de Venda: <span class="product-value" id="cardValorVenda">R$ <?= number_format($dados['valor_venda_produto'], 2, ',', '.') ?></span></p>
                    <p>Valor de Desconto: <span class="product-value" id="cardValorDesconto">R$ <?= number_format($dados['valor_desconto'], 2, ',', '.') ?></span></p>
                    <p>Quantidade em Estoque: <span class="product-value" id="cardEstoque"><?= htmlspecialchars($dados['qt_produto_estocado']) ?></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('foto_prod').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('cardImage').src = e.target.result; // Atualiza a imagem do card
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
