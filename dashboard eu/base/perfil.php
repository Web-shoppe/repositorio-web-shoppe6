<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UsuarioID'])) {
    die("Erro: Lojista não está logado.");
}

$cod_lojista_logado = $_SESSION['UsuarioID']; 

$host = 'localhost'; 
$dbname = 'web_shoppe'; 
$user = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT cod_loja, nome_lojista, cep, rua, numero, complemento, estado, cidade, bairro, data_nasc, cpf, tel1, tel2, email 
              FROM loja 
              WHERE id_usu = :cod_lojista";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cod_lojista', $cod_lojista_logado, PDO::PARAM_INT);
    $stmt->execute();

    $lojista = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Lojista</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background: #fff;
            width: 100%;
            max-width: 900px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header h1 {
            font-size: 36px;
            color: #6f42c1; 
            margin-bottom: 10px;
        }

        .header p {
            font-size: 18px;
            color: #777;
        }

        .body {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }

        .body p {
            font-size: 16px;
            display: flex;
            align-items: center;
        }

        .body .label {
            font-weight: bold;
            color: #555;
            margin-right: 10px;
        }

        .body .icon {
            font-size: 20px;
            margin-right: 10px;
            color: #6f42c1; 
        }

        .footer {
            text-align: center;
        }

        .footer .button {
            background-color: #6f42c1; 
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        .footer .button:hover {
            background-color: #5a33a5; 
            transform: translateY(-2px);
        }

        .footer .button:active {
            transform: translateY(1px);
        }

        .no-data {
            text-align: center;
            font-size: 20px;
            color: #e74c3c;
        }

    </style>
</head>
<body>
    <div class="container">
        <?php if ($lojista) : ?>
            <div class="header">
                <h1><?= htmlspecialchars($lojista['nome_lojista']) ?></h1>
                <p>Bem-vindo ao seu perfil!</p>
            </div>
            <div class="body">
                <p><i class="icon fas fa-id-badge"></i><span class="label">ID:</span> <?= htmlspecialchars($lojista['cod_loja']) ?></p>
                <p><i class="icon fas fa-user"></i><span class="label">Nome:</span> <?= htmlspecialchars($lojista['nome_lojista']) ?></p>
                <p><i class="icon fas fa-map-marker-alt"></i><span class="label">CEP:</span> <?= htmlspecialchars($lojista['cep']) ?></p>
                <p><i class="icon fas fa-road"></i><span class="label">Rua:</span> <?= htmlspecialchars($lojista['rua']) ?></p>
                <p><i class="icon fas fa-home"></i><span class="label">Número:</span> <?= htmlspecialchars($lojista['numero']) ?></p>
                <p><i class="icon fas fa-cogs"></i><span class="label">Complemento:</span> <?= htmlspecialchars($lojista['complemento']) ?></p>
                <p><i class="icon fas fa-city"></i><span class="label">Estado:</span> <?= htmlspecialchars($lojista['estado']) ?></p>
                <p><i class="icon fas fa-building"></i><span class="label">Cidade:</span> <?= htmlspecialchars($lojista['cidade']) ?></p>
                <p><i class="icon fas fa-map-marker-alt"></i><span class="label">Bairro:</span> <?= htmlspecialchars($lojista['bairro']) ?></p>
                <p><i class="icon fas fa-birthday-cake"></i><span class="label">Data de Nascimento:</span> <?= htmlspecialchars($lojista['data_nasc']) ?></p>
                <p><i class="icon fas fa-id-card"></i><span class="label">CPF:</span> <?= htmlspecialchars($lojista['cpf']) ?></p>
                <p><i class="icon fas fa-phone"></i><span class="label">Telefone 1:</span> <?= htmlspecialchars($lojista['tel1']) ?></p>
                <p><i class="icon fas fa-phone-alt"></i><span class="label">Telefone 2:</span> <?= htmlspecialchars($lojista['tel2']) ?></p>
                <p><i class="icon fas fa-envelope"></i><span class="label">Email:</span> <?= htmlspecialchars($lojista['email']) ?></p>
            </div>
            <div class="footer">
                <a href="base/editar_perfil.php?cod_loja=<?= $lojista['cod_loja'] ?>" class="button">Editar Perfil</a>
            </div>

        <?php else : ?>
            <div class="no-data">Nenhum dado encontrado para o lojista.</div>
        <?php endif; ?>
    </div>
</body>
</html>
