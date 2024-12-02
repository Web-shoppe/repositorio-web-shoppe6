<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    $loja = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$loja) {
        die("Loja não encontrada.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $cnpj = $_POST['cnpj'];
        $foto_loja = $_FILES['foto_loja']['name'];
        $banner = $_FILES['banner']['name'];

        if ($foto_loja) {
            move_uploaded_file($_FILES['foto_loja']['tmp_name'], "uploads/$foto_loja");
        } else {
            $foto_loja = $loja['foto_loja'];
        }

        if ($banner) {
            move_uploaded_file($_FILES['banner']['tmp_name'], "uploads/$banner");
        } else {
            $banner = $loja['banner'];
        }

        $updateQuery = "UPDATE loja SET nome = ?, cnpj = ?, foto_loja = ?, banner = ? WHERE cod_loja = ?";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([$nome, $cnpj, $foto_loja, $banner, $cod_loja]);

        header("Location: lista_lojas.php?msg=1&tipo_msg=update");
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
    <title>Editar Loja</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-size: 16px;
            color: #333;
        }
        input[type="text"],
        input[type="file"],
        button {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .image-preview {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .image-preview img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-store"></i> Editar Loja
        </div>

        <form method="POST" enctype="multipart/form-data">
            <label for="nome">Nome da Loja:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($loja['nome']) ?>" required>

            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" value="<?= htmlspecialchars($loja['cnpj']) ?>" required>

            <label for="foto_loja">Foto da Loja:</label>
            <input type="file" id="foto_loja" name="foto_loja">

            <label for="banner">Banner:</label>
            <input type="file" id="banner" name="banner">

            <div class="image-preview">
                <?php if ($loja['foto_loja']) : ?>
                    <div>
                        <strong>Foto Atual:</strong><br>
                        <img src="uploads/<?= htmlspecialchars($loja['foto_loja']) ?>" alt="Foto da Loja">
                    </div>
                <?php endif; ?>
                <?php if ($loja['banner']) : ?>
                    <div>
                        <strong>Banner Atual:</strong><br>
                        <img src="uploads/<?= htmlspecialchars($loja['banner']) ?>" alt="Banner da Loja">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit">Atualizar Loja</button>
        </form>
    </div>
</body>
</html>
