<?php

$dbHost = "localhost";      
$dbUsername = "root";       
$dbPassword = "";           
$dbName = "Web_shoppe";        


$conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);


if ($conexao->connect_errno) {
    echo "Falha na conexão: " . $conexao->connect_error;
} else {
    echo "Conexão efetuada com sucesso";
}

$conexao->close();
?>
