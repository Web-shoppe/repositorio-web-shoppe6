<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  
  
    <?php

$servername = "localhost";
$username = "root";
$password = ""; 
$database = "web_shoppe";

try {
    
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    
    echo "Conexão falhou: " . $e->getMessage();
    exit; 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? $_POST['nome'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $senha = isset($_POST['senha']) ? ($_POST['senha']) : null;
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : null;
    $data_nascimento = isset($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null;
    $cep = isset($_POST['cep']) ? $_POST['cep'] : null;
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : null;
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : null;
    $numero = isset($_POST['numero']) ? $_POST['numero'] : null;

    $cod_not = NULL;
    $nivel = 1;
    $ativo = 1;
    $cadastro = date("Y-m-d");
    $foto = NULL;

    $sql = "INSERT INTO usuario (cod_not, nome, email, senha, tel, cpf, cep, nivel, ativo, cadastro, foto)
            VALUES (:cod_not, :nome, :email, :senha, :telefone, NULL, :cep, :nivel, :ativo, :cadastro, :foto)";
    
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':cod_not', $cod_not);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':nivel', $nivel);
    $stmt->bindParam(':ativo', $ativo);
    $stmt->bindParam(':cadastro', $cadastro);
    $stmt->bindParam(':foto', $foto);

    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o usuário.";
    }
} else {
    echo "";
}
?>


























<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Cadastro de Usuário</h2>

        <!-- Indicador de Passos -->
        <div class="step-indicator">
            <div class="step active" id="step1">Dados Pessoais</div>
        </div>

        <!-- Formulário Unificado -->
        <form id="formCadastro" method="POST" action="processar_cadastro.php">
            <!-- Dados Pessoais -->
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" required>
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
            </div>

            <!-- Endereço -->
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" required>
            </div>

            <div class="form-group">
                <label for="endereco">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" required readonly>
            </div>

            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" required readonly>
            </div>

            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
            </div>

            <!-- Botões de Navegação -->
            <button type="submit" class="btn btn-success" id="btnFinalizar">Finalizar Cadastro</button>
        </form>
    </div>

    <!-- Script de Preenchimento de Endereço pelo CEP -->
    <script>
        $(document).ready(function () {
            // Preencher endereço automaticamente quando o CEP for digitado
            $('#cep').on('blur', function () {
                var cep = $(this).val().replace(/\D/g, '');

                if (cep.length == 8) {
                    var url = `https://viacep.com.br/ws/${cep}/json/`;

                    $.get(url, function (data) {
                        if (!data.erro) {
                            $('#endereco').val(data.logradouro);
                            $('#cidade').val(data.localidade);
                        } else {
                            alert("CEP não encontrado.");
                        }
                    });
                } else {
                    alert("Por favor, insira um CEP válido.");
                }
            });
        });
    </script>
</body>

<style>
        body {
            background-color: #f7f7f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .container {
            padding: 40px;
            max-width: 600px;
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
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #6D09A4;
            box-shadow: 0 0 5px rgba(109, 9, 164, 0.5);
        }

        .btn {
            border-radius: 20px;
            width: 100%;
        }

        .hidden {
            display: none;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control, .btn {
            padding: 15px;
        }

        .btn-back {
            background-color: #ddd;
            color: #333;
        }

        .btn-back:hover {
            background-color: #bbb;
        }
    </style>
</body>
</html>
