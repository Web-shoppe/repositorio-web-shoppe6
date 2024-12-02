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

    $query = "SELECT foto_loja, banner FROM loja WHERE cod_loja = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$cod_loja]);
    $loja = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($loja) {
        if ($loja['foto_loja'] && file_exists("uploads/{$loja['foto_loja']}")) {
            unlink("uploads/{$loja['foto_loja']}");
        }
        if ($loja['banner'] && file_exists("uploads/{$loja['banner']}")) {
            unlink("uploads/{$loja['banner']}");
        }

        $deleteQuery = "DELETE FROM loja WHERE cod_loja = ?";
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->execute([$cod_loja]);

        header("Location: lista_lojas.php?msg=1&tipo_msg=delete");
        exit;
    } else {
        die("Loja não encontrada.");
    }
} else {
    die("Código da loja não fornecido.");
}
