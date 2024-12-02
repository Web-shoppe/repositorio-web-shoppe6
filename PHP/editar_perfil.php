<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['UsuarioID'])) {
        die("Erro: Lojista não está logado.");
    }

    $cod_usuario = $_SESSION['UsuarioID']; 

    if (isset($_GET['id'])) {
        $id_usuario = $_GET['id'];
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

        $query = "SELECT *
                FROM usuario 
                WHERE cod_usuario = :cod_usuario";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':cod_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        $edit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$edit) {
            die("Erro: Nenhum lojista encontrado.");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = $_POST['nome'];
            $cep = $_POST['cep'];
            $endereco = $_POST['endereco'];
            $numero = $_POST['numero'];
            $cidade = $_POST['cidade'];
            $cpf = $_POST['cpf'];
            $tel = $_POST['tel'];
            $email = $_POST['email'];

            $query = "UPDATE usuario SET 
                        nome = :nome, 
                        cep = :cep, 
                        endereco = :endereco, 
                        numero = :numero, 
                        cidade = :cidade, 
                        cpf = :cpf, 
                        tel = :tel, 
                        email = :email
                    WHERE cod_usuario = :cod_usuario";
            
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cep', $cep);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':numero', $numero);
            $stmt->bindParam(':cidade', $cidade);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':tel', $tel);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cod_usuario', $cod_usuario);

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

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        
        <style>
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
                border-top: 5px solid #6D09A4;
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
                border-color: #6D09A4;
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
                background-color: #6D09A4;
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
            <form action="editar_perfil.php?id=<?= $cod_usuario ?>" method="POST">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($edit['nome']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="cep">CEP</label>
                    <input type="text" name="cep" id="cep" value="<?= htmlspecialchars($edit['cep']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="rua">Rua</label>
                    <input type="text" name="endereco" id="endereco" value="<?= htmlspecialchars($edit['endereco']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="numero">Número</label>
                    <input type="text" name="numero" id="numero" value="<?= htmlspecialchars($edit['numero']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" name="cidade" id="cidade" value="<?= htmlspecialchars($edit['cidade']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" value="<?= htmlspecialchars($edit['cpf']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tel">Telefone</label>
                    <input type="text" name="tel" id="tel" value="<?= htmlspecialchars($edit['tel']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($edit['email']) ?>" required>
                </div>
                <div class="footer">
                    <button type="submit">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </body>
    </html>
