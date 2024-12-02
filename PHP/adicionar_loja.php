<?php
session_start();


if (isset($_SESSION['UsuarioID'])) {
    global $lojistaId;
    $lojistaId = $_SESSION['UsuarioID']; 
} else {
    echo "Você precisa estar logado para acessar esta página.";
    exit();
}

require_once "../base/conex.php"; 
require_once "../base/config.php";   

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "web_shoppe");
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if (isset($_FILES['logo']) && isset($_FILES['banner'])) {
    $uploads_dir = '';
    
    $logoPath = $uploads_dir . basename($_FILES['logo']['name']);
    $bannerPath = $uploads_dir . basename($_FILES['banner']['name']);

    if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath) && move_uploaded_file($_FILES['banner']['tmp_name'], $bannerPath)) {
        try {
            $conn->begin_transaction();

            $stmt = $conn->prepare("INSERT INTO loja 
                                    (nome_lojista, email, tel1, tel2, data_nasc, cpf, cep, rua, numero, complemento, nome, cnpj, foto_loja, banner, id_usu) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssssssssssssi", 
                $_POST['nome_lojista'], 
                $_POST['email'], 
                $_POST['tel1'], 
                $_POST['tel2'], 
                $_POST['data_nasc'], 
                $_POST['cpf'], 
                $_POST['cep'], 
                $_POST['rua'], 
                $_POST['numero'], 
                $_POST['complemento'], 
                $_POST['nome'], 
                $_POST['cnpj'], 
                $logoPath, 
                $bannerPath,
                $lojistaId
            );

            $stmt->execute();

            $conn->commit();

            $updateStmt = $conn->prepare("
                UPDATE usuario u
                INNER JOIN loja l ON u.cod_usuario = l.id_usu
                SET u.nivel = 2
                WHERE l.id_usu = ?");
            
            $updateStmt->bind_param("i", $lojistaId); 
            $updateStmt->execute();

            header("Location: loja.php");
            exit(); 

        } catch (Exception $e) {
            $conn->rollback();
            echo "Erro ao realizar cadastro: " . $e->getMessage();
        }
    } else {
        echo "Erro ao mover os arquivos de logo ou banner.";
    }
} else {
    echo "Erro: arquivos não enviados corretamente!";
}

$conn->close();
?>

