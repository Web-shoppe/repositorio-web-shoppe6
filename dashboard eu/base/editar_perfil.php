    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['UsuarioID'])) {
        die("Erro: Lojista não está logado.");
    }

    $cod_lojista_logado = $_SESSION['UsuarioID']; 


    if (isset($_GET['cod_loja'])) {
        $cod_loja = $_GET['cod_loja'];
    } else {
        die("Erro: Nenhum ID de loja fornecido.");
    }

    $host = 'localhost'; 
    $dbname = 'web_shoppe'; 
    $user = 'root'; 
    $password = ''; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT cod_loja, nome_lojista, cep, rua, numero, complemento, estado, cidade, bairro, data_nasc, cpf, tel1, tel2, email 
                FROM loja 
                WHERE cod_loja = :cod_loja";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':cod_loja', $cod_loja, PDO::PARAM_INT);
        $stmt->execute();

        $lojista = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$lojista) {
            die("Erro: Nenhum lojista encontrado.");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome_lojista = $_POST['nome_lojista'];
            $cep = $_POST['cep'];
            $rua = $_POST['rua'];
            $numero = $_POST['numero'];
            $complemento = $_POST['complemento'];
            $estado = $_POST['estado'];
            $cidade = $_POST['cidade'];
            $bairro = $_POST['bairro'];
            $data_nasc = $_POST['data_nasc'];
            $cpf = $_POST['cpf'];
            $tel1 = $_POST['tel1'];
            $tel2 = $_POST['tel2'];
            $email = $_POST['email'];

            $query = "UPDATE loja SET 
                        nome_lojista = :nome_lojista, 
                        cep = :cep, 
                        rua = :rua, 
                        numero = :numero, 
                        complemento = :complemento, 
                        estado = :estado, 
                        cidade = :cidade, 
                        bairro = :bairro, 
                        data_nasc = :data_nasc, 
                        cpf = :cpf, 
                        tel1 = :tel1, 
                        tel2 = :tel2, 
                        email = :email
                    WHERE cod_loja = :cod_loja";
            
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nome_lojista', $nome_lojista);
            $stmt->bindParam(':cep', $cep);
            $stmt->bindParam(':rua', $rua);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':complemento', $complemento);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':bairro', $bairro);
            $stmt->bindParam(':data_nasc', $data_nasc);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':tel1', $tel1);
            $stmt->bindParam(':tel2', $tel2);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cod_loja', $cod_loja);

            if ($stmt->execute()) {
                header("Location: perfil.php");
                exit;
            } else {
                $error_message = "Erro ao atualizar o perfil.";
            }
        }

    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }

    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Perfil do Lojista</title>

        <!-- Link do Font Awesome para ícones -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        
        <style>
            /* Reset de CSS */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f1f1f1;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
            }

            .container {
                background-color: #ffffff;
                width: 100%;
                max-width: 900px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                padding: 40px;
                overflow: hidden;
                border-top: 5px solid #007bff;
            }

            .header {
                text-align: center;
                margin-bottom: 40px;
            }

            .header h1 {
                font-size: 32px;
                color: #333;
                font-weight: 700;
                margin-bottom: 10px;
            }

            .header p {
                font-size: 16px;
                color: #777;
                margin-top: 5px;
            }

            .form-group {
                margin-bottom: 25px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: relative;
            }

            .form-group label {
                font-size: 16px;
                font-weight: 600;
                color: #444;
                margin-bottom: 8px;
                width: 30%;
            }

            .form-group input, .form-group select {
                width: 65%;
                padding: 15px 20px;
                border-radius: 8px;
                border: 1px solid #ddd;
                background-color: #fafafa;
                font-size: 16px;
                color: #333;
                outline: none;
                transition: border-color 0.3s ease, background-color 0.3s ease;
            }

            .form-group input:focus, .form-group select:focus {
                border-color: #007bff;
                background-color: #ffffff;
            }

            .form-group input::placeholder,
            .form-group select::placeholder {
                color: #aaa;
            }

            .footer {
                text-align: center;
                margin-top: 30px;
            }

            .footer button {
                background-color: #007bff;
                color: #fff;
                padding: 16px 25px;
                border: none;
                border-radius: 8px;
                font-size: 18px;
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.3s ease;
            }

            .footer button:hover {
                background-color: #0056b3;
                transform: translateY(-2px);
            }

            .footer button:active {
                background-color: #004085;
                transform: translateY(2px);
            }

            .error-message {
                color: #e74c3c;
                text-align: center;
                font-size: 18px;
                margin-top: 20px;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Editar Perfil</h1>
                <p>Atualize as informações do seu perfil</p>
            </div>
            <?php if (isset($error_message)) : ?>
                <div class="error-message"><?= $error_message ?></div>
            <?php endif; ?>
            <form action="editar_perfil.php?cod_loja=<?= $cod_loja ?>" method="POST">
                <div class="form-group">
                    <label for="nome_lojista">Nome</label>
                    <input type="text" name="nome_lojista" id="nome_lojista" value="<?= htmlspecialchars($lojista['nome_lojista']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" name="cep" id="cep" value="<?= htmlspecialchars($lojista['cep']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="rua">Rua</label>
                    <input type="text" name="rua" id="rua" value="<?= htmlspecialchars($lojista['rua']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="numero">Número</label>
                    <input type="text" name="numero" id="numero" value="<?= htmlspecialchars($lojista['numero']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento</label>
                    <input type="text" name="complemento" id="complemento" value="<?= htmlspecialchars($lojista['complemento']) ?>">
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <input type="text" name="estado" id="estado" value="<?= htmlspecialchars($lojista['estado']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($lojista['cidade']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro</label>
                    <input type="text" name="bairro" id="bairro" value="<?= htmlspecialchars($lojista['bairro']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="data_nasc">Data de Nascimento</label>
                    <input type="date" name="data_nasc" id="data_nasc" value="<?= htmlspecialchars($lojista['data_nasc']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" value="<?= htmlspecialchars($lojista['cpf']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tel1">Telefone 1</label>
                    <input type="text" name="tel1" id="tel1" value="<?= htmlspecialchars($lojista['tel1']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tel2">Telefone 2</label>
                    <input type="text" name="tel2" id="tel2" value="<?= htmlspecialchars($lojista['tel2']) ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($lojista['email']) ?>" required>
                </div>
                <div class="footer">
                    <button type="submit">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </body>
    </html>
