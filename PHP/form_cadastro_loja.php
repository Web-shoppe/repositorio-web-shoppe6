<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Loja</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container {
            padding: 40px;
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #6D09A4;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .step-indicator .step {
            width: 48%;
            padding: 10px;
            background-color: #eee;
            border-radius: 25px;
            text-align: center;
            font-weight: bold;
            color: #6D09A4;
        }

        .step-indicator .step.active {
            background-color: #6D09A4;
            color: #fff;
        }

        .form-control {
            border-radius: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control, .btn {
            padding: 15px;
        }

        .hidden {
            display: none;
        }

        .btn-back {
            background-color: #e0e0e0;
            color: #333;
            border: none;
            border-radius: 25px;
            padding: 12px;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .btn-back:hover {
            background-color: #bbb;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #6D09A4;
            border: none;
            color: white;
            border-radius: 25px;
            padding: 12px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #9d26b0;
            cursor: pointer;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 25px;
            padding: 12px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            text-align: center;
            display: inline-block;
            font-size: 16px;
        }

        .file-input {
            padding: 10px;
        }

        .btn:focus, .btn:active {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container">
    <h2>Cadastro de Loja</h2>

    <div class="step-indicator">
        <div class="step active" id="step1">Passo 1: Dados do Lojista</div>
        <div class="step" id="step2">Passo 2: Endereço</div>
        <div class="step" id="step3">Passo 3: Dados da Loja</div>
    </div>

    <form id="formCadastro" method="POST" action="adicionar_loja.php" enctype="multipart/form-data">
        <div id="step1Content">
            <div class="form-group">
                <label for="nome_lojista">Nome do Lojista</label>
                <input type="text" class="form-control" id="nome_lojista" name="nome_lojista" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="tel1" name="tel2" required>
            </div>

            <div class="form-group">
                <label for="celular">Celular</label>
                <input type="text" class="form-control" id="tel2" name="tel2" required>
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
            </div>

            <div class="form-group">
                <label for="cpf">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" required>
            </div>

            <button type="button" class="btn btn-primary" id="btnContinuar1">Continuar</button>
        </div>

        <div id="step2Content" class="hidden">
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" required>
            </div>

            <div class="form-group">
                <label for="endereco">Rua</label>
                <input type="text" class="form-control" id="endereco" name="endereco" >
            </div>

            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
            </div>

            <div class="form-group">
                <label for="complemento">Complemento</label>
                <input type="text" class="form-control" id="complemento" name="complemento">
            </div>

            <button type="button" class="btn btn-back" id="btnVoltar1">Voltar</button>
            <button type="button" class="btn btn-primary" id="btnContinuar2">Continuar</button>
        </div>

        <div id="step3Content" class="hidden">
            <div class="form-group">
                <label for="nome">Nome da Loja</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="cnpj">CNPJ</label>
                <input type="text" class="form-control" id="cnpj" name="cnpj" required>
            </div>

            <div class="form-group">
                <label for="logo">Logo da Loja</label>
                <input type="file" class="form-control-file file-input" id="logo" name="logo" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="banner">Banner da Loja</label>
                <input type="file" class="form-control-file file-input" id="banner" name="banner" accept="image/*" required>
            </div>

            <button type="button" class="btn btn-back" id="btnVoltar2">Voltar</button>
            <button type="submit" class="btn btn-success" id="btnFinalizar">Finalizar Cadastro</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#btnContinuar1').click(function () {
            $('#step1Content').hide();
            $('#step2Content').removeClass('hidden');
            $('#step1').removeClass('active');
            $('#step2').addClass('active');
        });

        $('#btnVoltar1').click(function () {
            $('#step2Content').hide();
            $('#step1Content').show();
            $('#step1').addClass('active');
            $('#step2').removeClass('active');
        });

        $('#btnContinuar2').click(function () {
            $('#step2Content').hide();
            $('#step3Content').removeClass('hidden');
            $('#step2').removeClass('active');
            $('#step3').addClass('active');
        });

        $('#btnVoltar2').click(function () {
            $('#step3Content').hide();
            $('#step2Content').removeClass('hidden');
            $('#step2').addClass('active');
            $('#step3').removeClass('active');
        });
    });
</script>

</body>
</html>
                  
