<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UsuarioID'])) {
    die("Erro: Usuario não está logado.");
}

$cod_usuario = $_SESSION['UsuarioID']; 


$host = 'localhost'; 
$dbname = 'web_shoppe'; 
$user = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$perfil = "SELECT * FROM usuario WHERE cod_usuario = :cod_usuario";
$stmt = $pdo->prepare($perfil);
$stmt->bindParam(':cod_usuario', $cod_usuario, PDO::PARAM_INT);
$stmt->execute();

    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Web Shoppe</title>
  <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
    
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
            margin: 50px;
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
<?php
  require_once '../include/header_inicio.php';
  ?>

    <div class="container">
        <?php if ($perfil) : ?>
            <div class="header">
                <h1><?= htmlspecialchars($perfil['nome']) ?></h1>
                <p>Bem-vindo ao seu perfil!</p>
            </div>
            <div class="body">
                <p><i class="icon fas fa-user"></i><span class="label">Nome:</span> <?=$perfil['nome'] ?></p>
                <p><i class="icon fas fa-map-marker-alt"></i><span class="label">CEP:</span> <?= htmlspecialchars($perfil['cep']) ?></p>
                <p><i class="icon fas fa-road"></i><span class="label">Rua:</span> <?= htmlspecialchars($perfil['endereco']) ?></p>
                <p><i class="icon fas fa-home"></i><span class="label">Número:</span> <?= htmlspecialchars($perfil['numero']) ?></p>
                <p><i class="icon fas fa-building"></i><span class="label">Cidade:</span> <?= htmlspecialchars($perfil['cidade']) ?></p>
                <p><i class="icon fas fa-id-card"></i><span class="label">CPF:</span> <?= htmlspecialchars($perfil['cpf']) ?></p>
                <p><i class="icon fas fa-phone"></i><span class="label">Telefone:</span> <?= htmlspecialchars($perfil['tel']) ?></p>
                <p><i class="icon fas fa-envelope"></i><span class="label">Email:</span> <?= htmlspecialchars($perfil['email']) ?></p>
            </div>
            <div class="footer">
                <a href="editar_perfil.php?id=<?= $perfil['cod_usuario'] ?>" class="button">Editar Perfil</a>
            </div>

        <?php else : ?>
            <div class="no-data">Nenhum dado encontrado para o lojista.</div>
        <?php endif; ?>
    </div>
    <?php
  require_once '../include/footer.php';
  ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
