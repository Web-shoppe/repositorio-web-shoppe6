<?php 
session_start(); 
require_once "../base/conex.php"; 
require_once "../base/config.php";   

$conexao = new mysqli("localhost", "root", "", "web_shoppe");

$usuario_id = $_SESSION['UsuarioID'] ?? ""; 

$cod_lojista = $_POST['cod_lojista'] ?? '';
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$celular = $_POST['celular'] ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$cep = $_POST['cidade'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao->begin_transaction();

    try {
        $queryLojista = "INSERT INTO lojista (cod_lojista, nome, email, tel1, tel2, data_nasc, cpf ) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
        if ($stmtLojista = $conexao->prepare($queryLojista)) {
            $stmtLojista->bind_param('sssssss', $cod_lojista, $nome, $email, $telefone, $celular, $data_nascimento, $cpf );
            $stmtLojista->execute();
            $_SESSION['cod_lojista'] = $cod_lojista; 
            $stmtLojista->close();
        }

        
        $queryUsuario = "UPDATE usuario SET nivel = 2 WHERE cod_usuario = ?"; 
        if ($stmtUsuario = $conexao->prepare($queryUsuario)) {
            $stmtUsuario->bind_param('s', $usuario_id); 
            $stmtUsuario->execute();
            $stmtUsuario->close();
        }

        $conexao->commit();
        header("Location: form_cadastro_imagens.php"); 
        exit();
    } catch (Exception $e) {
        $conexao->rollback();
        echo "Erro: " . $e->getMessage(); 
    }
}
?>
