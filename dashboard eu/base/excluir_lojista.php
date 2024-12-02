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

    $query = "DELETE FROM loja WHERE cod_loja = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$cod_loja]);

    header("Location: lista_lojistas.php?msg=1&tipo_msg=delete");
    exit;
} else {
    die("Código da loja não fornecido.");
}
?>
