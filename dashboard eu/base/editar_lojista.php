<?php
session_start();

$host = 'localhost';
$dbname = 'web_shoppe';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

if (isset($_GET['cod_loja'])) {
    $cod_loja = $_GET['cod_loja'];

    $query = "SELECT * FROM loja WHERE cod_loja = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$cod_loja]);
    $lojista = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$lojista) {
        die("Lojista não encontrado.");
    }

    if (isset($_POST['update'])) {
        $nome_lojista = $_POST['nome_lojista'];
        $cpf = $_POST['cpf'];
        $tel1 = $_POST['tel1'];
        $tel2 = $_POST['tel2'];
        $email = $_POST['email'];
        $cep = $_POST['cep'];

        $updateQuery = "UPDATE loja SET nome_lojista = ?, cpf = ?, tel1 = ?, tel2 = ?, email = ?, cep = ? WHERE cod_loja = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$nome_lojista, $cpf, $tel1, $tel2, $email, $cep, $cod_loja]);

        header("Location: lista_lojistas.php?msg=1&tipo_msg=update");
        exit;
    }
} else {
    die("Código da loja não fornecido.");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Lojista</title>
    <!-- Link para o Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .header i {
            color: #6c63ff;
            margin-right: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            color: #333;
        }

        input[type="text"],
        input[type="email"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #6c63ff;
        }

        .btn {
            background-color: #6c63ff;
            color: white;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #4b47d9;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }

        .footer a {
            color: #6c63ff;
            text-decoration: none;
            font-weight: bold;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <i class="fas fa-store"></i> Editar Lojista
        </div>
        
        <form method="POST">
            <label for="nome_lojista"><i class="fas fa-user"></i> Nome do Lojista:</label>
            <input type="text" id="nome_lojista" name="nome_lojista" value="<?= htmlspecialchars($lojista['nome_lojista']) ?>" required>

            <label for="cpf"><i class="fas fa-id-card"></i> CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($lojista['cpf']) ?>" required>

            <label for="tel1"><i class="fas fa-phone"></i> Telefone 1:</label>
            <input type="text" id="tel1" name="tel1" value="<?= htmlspecialchars($lojista['tel1']) ?>" required>

            <label for="tel2"><i class="fas fa-phone-alt"></i> Telefone 2:</label>
            <input type="text" id="tel2" name="tel2" value="<?= htmlspecialchars($lojista['tel2']) ?>">

            <label for="email"><i class="fas fa-envelope"></i> Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($lojista['email']) ?>" required>

            <label for="cep"><i class="fas fa-map-marker-alt"></i> CEP:</label>
            <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($lojista['cep']) ?>" required>

            <button type="submit" name="update" class="btn">
                <i class="fas fa-save"></i> Atualizar
            </button>
        </form>

        <div class="footer">
            <p>Voltar para a <a href="lista_lojistas.php">lista de lojistas</a></p>
        </div>
    </div>

</body>
</html>
