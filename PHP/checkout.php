<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "web_shoppe";

$conn = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_POST['lojas'])) {
    $lojas = json_decode($_POST['lojas'], true); // Decodifica o JSON
    print_r($lojas); // Exemplo: [1, 2, 3]
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Checkout - Web Shoppe</title>
    <style>
        body {
            background-color: #e9ecef;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            transition: transform 0.3s ease;
        }

        h1 {
            text-align: center;
            color: #6D09A4;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .section-title {
            color: #6D09A4;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .form-section {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group label {
            color: #333;
            font-weight: 500;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #6D09A4;
            box-shadow: 0 0 5px rgba(109, 9, 164, 0.2);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn-custom {
            background-color: #6D09A4;
            color: white;
            width: 48%;
            font-weight: bold;
            padding: 10px;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #5b08a0;
        }

        .icon {
            color: #6D09A4;
            margin-right: 10px;
        }

        #qrcode {
            margin-top: 15px;
            display: none;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-shopping-cart"></i> Checkout</h1>

        <form id="checkoutForm" action="finalizar_pedido.php" method="POST">
            <div class="form-section" id="formCliente">
                <h3 class="section-title"><i class="fas fa-user"></i> Informações do Cliente</h3>
                <div class="form-group">
                    <label for="nome-completo">Nome Completo:</label>
                    <input type="text" class="form-control" id="nome-completo" name="nome-completo" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                </div>
                <div class="button-group">
                    <button class="btn btn-custom" type="button" onclick="proximo('formEntrega')">Próximo <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <div class="form-section" id="formEntrega" style="display: none;">
                <h3 class="section-title"><i class="fas fa-truck"></i> Endereço de Entrega</h3>
                <div class="form-group">
                    <label for="cep">CEP:</label>
                    <input type="text" class="form-control" id="cep" name="cep" required onblur="buscarCep()">
                </div>
                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" class="form-control" id="estado" name="estado" required>
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" class="form-control" id="bairro" name="bairro" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" class="form-control" id="endereco" name="endereco" required>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" class="form-control" id="numero" name="numero" required>
                </div>
                <div class="button-group">
                    <button class="btn btn-custom" type="button" onclick="anterior('formCliente')"><i class="fas fa-arrow-left"></i> Voltar</button>
                    <button class="btn btn-custom" type="button" onclick="proximo('formPagamento')">Próximo <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <div class="form-section" id="formPagamento" style="display: none;">
                <h3 class="section-title"><i class="fas fa-credit-card"></i> Pagamento</h3>
                <div class="form-group">
                    <label for="metodo-pagamento">Método de Pagamento:</label>
                    <select class="form-control" id="metodo-pagamento" name="metodo-pagamento" required onchange="mostrarDadosCartao()">
                        <option value="">Selecione uma opção</option>
                        <option value="cartao">Cartão de Crédito</option>
                        <option value="pix">Pix</option>
                        <option value="boleto">Boleto</option>
                    </select>
                </div>
                <div id="dadosCartao" style="display: none;">
                    <div class="form-group">
                        <label for="numero-cartao">Número do Cartão:</label>
                        <input type="text" class="form-control" id="numero-cartao" name="numero-cartao" required>
                    </div>
                    <div class="form-group">
                        <label for="nome-titular">Nome do Titular:</label>
                        <input type="text" class="form-control" id="nome-titular" name="nome-titular" required>
                    </div>
                    <div class="form-group">
                        <label for="validade-cartao">Validade:</label>
                        <input type="text" class="form-control" id="validade-cartao" name="validade-cartao" placeholder="MM/AA" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" required>
                    </div>
                </div>
                <div id="dadosPix" style="display: none;">
                    <div class="form-group">
                        <label for="chave-pix">Chave do Pix:</label>
                        <input type="text" class="form-control" id="chave-pix" name="chave-pix" required readonly>
                    </div>
                    <div id="qrcode"></div>
                </div>
                <div class="button-group">
                    <button class="btn btn-custom" type="button" onclick="anterior('formEntrega')"><i class="fas fa-arrow-left"></i> Voltar</button>
                    <input class="btn btn-custom" type="submit"></input>
                </div>
            </div>
        </form>
    </div>



    
    <!-- jQuery e jQuery Mask Plugin -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <!-- QR Code Generator -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-qrcode/1.0/jquery.qrcode.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#cep').mask('00000-000');
            $('#telefone').mask('(00) 00000-0000');
            $('#cpf').mask('000.000.000-00');
            $('#numero-cartao').mask('0000 0000 0000 0000');
            $('#validade-cartao').mask('00/00');
            $('#cvv').mask('000');

            $('#metodo-pagamento').change(function() {
                if ($(this).val() === 'pix') {
                    $('#dadosPix').show();
                    $('#qrcode').show();
                    $('#dadosCartao').hide();
                } else if ($(this).val() === 'cartao') {
                    $('#dadosCartao').show();
                    $('#dadosPix').hide();
                } else {
                    $('#dadosCartao').hide();
                    $('#dadosPix').hide();
                }
            });
        });

        function mostrarDadosCartao() {
            $('#dadosCartao').toggle($('#metodo-pagamento').val() === 'cartao');
            $('#dadosPix').toggle($('#metodo-pagamento').val() === 'pix');
        }

        function proximo(formId) {
            $('.form-section').hide();
            $('#' + formId).show();
        }

        function anterior(formId) {
            $('.form-section').hide();
            $('#' + formId).show();
        }

        function buscarCep() {
            const cep = $('#cep').val().replace(/\D/g, '');
            if (cep.length === 8) {
                $.getJSON(`https://viacep.com.br/ws/${cep}/json/?callback=?`, function(dados) {
                    if (!("erro" in dados)) {
                        $('#estado').val(dados.uf);
                        $('#bairro').val(dados.bairro);
                        $('#endereco').val(dados.logradouro);
                    } else {
                        alert("CEP não encontrado.");
                        $('#estado').val('');
                        $('#bairro').val('');
                        $('#endereco').val('');
                    }
                });
            } else {
                alert("Formato de CEP inválido.");
                $('#estado').val('');
                $('#bairro').val('');
                $('#endereco').val('');
            }
        }
    </script>
</body>
</html>