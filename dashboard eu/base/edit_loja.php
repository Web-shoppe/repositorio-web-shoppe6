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

    $query = "SELECT cod_loja, nome, cnpj, foto_loja, banner 
              FROM loja 
              WHERE id_usu = :cod_lojista";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cod_lojista', $cod_lojista_logado, PDO::PARAM_INT);
    $stmt->execute();

    $loja = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($loja['nome']) ?> - Minha Loja</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9; 
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .header {
            background-color: #6f42c1; 
            color: white;
            text-align: center;
            padding: 40px;
            border-radius: 15px 15px 0 0;
            font-size: 36px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .body {
            padding: 30px;
            color: #555;
        }

        .body .label {
            font-weight: 700;
            color: #333;
        }

        .body p {
            margin-bottom: 25px;
            line-height: 1.8;
        }

        .footer {
            padding: 25px;
            text-align: center;
            background-color: #f1f1f1;
            border-top: 1px solid #ddd;
            border-radius: 0 0 15px 15px;
        }

        .button {
            text-decoration: none;
            color: #fff;
            background-color: #6f42c1; 
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #5a31a3;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .no-data {
            text-align: center;
            font-size: 22px;
            color: #888;
            padding: 30px;
        }

        /* Imagens */
        .image-container {
            text-align: center;
            margin-top: 40px;
            transition: transform 0.3s ease-in-out;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .image-container img:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
        }

        .banner-container {
            margin-top: 40px;
            text-align: center;
        }

        .banner-container img {
            max-width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                margin: 20px;
            }

            .header {
                font-size: 28px;
            }

            .body p {
                font-size: 16px;
            }

            .button {
                padding: 12px 18px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($loja) : ?>
            <div class="header"><?= htmlspecialchars($loja['nome']) ?></div> 
            <div class="body">
                <p><i class="fas fa-building icon"></i><span class="label">CNPJ:</span> <?= htmlspecialchars($loja['cnpj']) ?></p>
                <div class="image-container">
                    <?php if ($loja['foto_loja']) : ?>
                        <img src="uploads/<?= htmlspecialchars($loja['foto_loja']) ?>" alt="Foto da Loja">
                    <?php else : ?>
                        <p><em>Foto da loja não disponível.</em></p>
                    <?php endif; ?>
                </div>
                <div class="banner-container">
                    <?php if ($loja['banner']) : ?>
                        <img src="uploads/<?= htmlspecialchars($loja['banner']) ?>" alt="Banner da Loja">
                    <?php else : ?>
                        <p><em>Banner da loja não disponível.</em></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer">
                <a href="base/editar_loja.php?cod_loja=<?= $loja['cod_loja'] ?>" class="button"><i class="fas fa-edit icon"></i>Editar Loja</a>
            </div>
        <?php else : ?>
            <div class="no-data">Nenhuma loja encontrada para o lojista.</div>
        <?php endif; ?>
    </div>
</body>
</html>
