<?php 
    require_once "../base/conex.php";
    require_once "../base/config.php";    
?>

<?php
$conn = new mysqli("localhost", "root", "", "web_shoppe");

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


$id_usuario = $_GET['id']; 


$sql = "
    UPDATE usuario SET nivel = '2' WHERE cod_usuario = '$id_usuario';
    INSERT INTO lojista 
    ";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    echo "Registro atualizado com sucesso.";
} else {
    echo "Erro ao atualizar registro: " . $conn->error;
}

$stmt->close();
$conn->close();
?>