<?php
session_start();

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $cnpj = $_POST['cnpj'];
        $foto_loja = $_FILES['foto_loja'];
        $banner = $_FILES['banner'];
        $cod_loja = $_GET['cod_loja']; 

        if ($foto_loja['error'] == 0) {
            $foto_loja_nome = uniqid() . '-' . $foto_loja['name'];
            move_uploaded_file($foto_loja['tmp_name'], 'uploads/' . $foto_loja_nome);
        } else {
            $foto_loja_nome = $_POST['foto_atual'];
        }

        if ($banner['error'] == 0) {
            $banner_nome = uniqid() . '-' . $banner['name'];
            move_uploaded_file($banner['tmp_name'], 'uploads/' . $banner_nome);
        } else {
            $banner_nome = $_POST['banner_atual'];
        }

        $query = "UPDATE loja SET nome = :nome, cnpj = :cnpj, foto_loja = :foto_loja, banner = :banner WHERE cod_loja = :cod_loja";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':foto_loja', $foto_loja_nome);
        $stmt->bindParam(':banner', $banner_nome);
        $stmt->bindParam(':cod_loja', $cod_loja);

        if ($stmt->execute()) {
            echo "<p>Loja atualizada com sucesso!</p>";
        } else {
            echo "<p>Erro ao atualizar a loja.</p>";
        }
    }

    $cod_loja = $_GET['cod_loja'];
    $query = "SELECT cod_loja, nome, cnpj, foto_loja, banner FROM loja WHERE cod_loja = :cod_loja";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cod_loja', $cod_loja);
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
    <title>Editar Loja</title>
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
            max-width: 900px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            font-size: 36px;
            color: #6f42c1;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .input, .textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .input[type="file"] {
            padding: 8px;
        }

        .input:focus, .textarea:focus {
            border-color: #6f42c1;
        }

        .button, .cancel {
            text-decoration: none;
            color: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .button {
            background-color: #6f42c1;
        }

        .button:hover {
            background-color: #5a32a3;
        }

        .cancel {
            background-color: #f44336;
            margin-left: 10px;
        }

        .cancel:hover {
            background-color: #e53935;
        }

        .image-preview {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview img {
            max-width: 150px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group i {
            color: #6f42c1;
            margin-right: 10px;
        }

        .input, .textarea {
            font-size: 16px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header"><i class="fas fa-store"></i> Editar Loja</div>

        <form action="editar_loja.php?cod_loja=<?= $loja['cod_loja'] ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="label" for="nome"><i class="fas fa-store"></i> Nome da Loja</label>
                <input class="input" type="text" id="nome" name="nome" value="<?= htmlspecialchars($loja['nome']) ?>" required>
            </div>

            <div class="form-group">
                <label class="label" for="cnpj"><i class="fas fa-id-card"></i> CNPJ</label>
                <input class="input" type="text" id="cnpj" name="cnpj" value="<?= htmlspecialchars($loja['cnpj']) ?>" required>
            </div>

            <div class="form-group">
                <label class="label" for="foto_loja"><i class="fas fa-camera"></i> Foto da Loja</label>
                <input class="input" type="file" id="foto_loja" name="foto_loja">
                <input type="hidden" name="foto_atual" value="<?= $loja['foto_loja'] ?>">
                <?php if ($loja['foto_loja']) : ?>
                    <div class="image-preview">
                        <img src="uploads/<?= htmlspecialchars($loja['foto_loja']) ?>" alt="Foto da Loja">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="label" for="banner"><i class="fas fa-image"></i> Banner</label>
                <input class="input" type="file" id="banner" name="banner">
                <input type="hidden" name="banner_atual" value="<?= $loja['banner'] ?>">
                <?php if ($loja['banner']) : ?>
                    <div class="image-preview">
                        <img src="uploads/<?= htmlspecialchars($loja['banner']) ?>" alt="Banner da Loja">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="button"><i class="fas fa-save"></i> Salvar Alterações</button>
            <a href="minha_loja.php" class="cancel"><i class="fas fa-times"></i> Cancelar</a>
        </form>
    </div>
</body>
</html>
