<?php
    $con = mysqli_connect("localhost", "root", "", "web_shoppe");

    $usu = $_POST['usuario'];
    $nom = $_POST['nome'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $senha = $_POST['senha'];
    $cpf = $_POST['cpf'];

    $sql = "INSERT INTO usuario (usuario, nome, email, tel, senha, cpf) values ('$usu', '$nom', '$email', '$tel', '$senha', '$cpf')";

    $result = mysqli_query($con, $sql);

    if($result){
        include "login.php";
    } else {
        echo "erro!";
    }
?>