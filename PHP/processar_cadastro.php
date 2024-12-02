<?php
$con = mysqli_connect('localhost', 'root', '', 'web_shoppe');

if (!$con) {
    die("Erro: Não foi possível conectar ao banco de dados.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    var_dump($_POST);

    if (isset($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['telefone'], $_POST['data_nascimento'], $_POST['cep'], $_POST['endereco'], $_POST['cidade'], $_POST['numero'])) {

        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']); 
        $telefone = trim($_POST['telefone']);
        $data_nascimento = trim($_POST['data_nascimento']);
        $cep = trim($_POST['cep']);
        $endereco = trim($_POST['endereco']);
        $cidade = trim($_POST['cidade']);
        $numero = trim($_POST['numero']);

        $sql = "INSERT INTO usuario (nome, email, senha, tel, cadastro, cep, endereco, cidade, numero) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($con, $sql)) {

            mysqli_stmt_bind_param($stmt, 'sssssssss', $nome, $email, $senha, $telefone, $data_nascimento, $cep, $endereco, $cidade, $numero);

            if (mysqli_stmt_execute($stmt)) {
                echo "Cadastro realizado com sucesso!";
            } else {
                echo "Erro ao cadastrar. Tente novamente.";
            }

            mysqli_stmt_close($stmt);

        } else {
            echo "Erro ao preparar a query.";
        }
    } else {
        echo "Erro: Dados do formulário incompletos.";
    }
}

mysqli_close($con);
?>
