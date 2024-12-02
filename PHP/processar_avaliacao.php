<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nota = $_POST['nota'];
    $comentario = $_POST['comentario'];
    $data = $_POST['data'];
    $cod_usuario = $_POST['cod_usuario'];
    $cod_produto = $_POST['cod_produto'];

    $con = new mysqli("localhost", "root", "", "web_shoppe");

    if ($con->connect_error) {
        die("Conexão falhou: " . $con->connect_error);
    }

    $sql = "INSERT INTO feedback (id_produto, id_usuario, nota, comentario, data) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iisss", $cod_produto, $cod_usuario, $nota, $comentario, $data);

    if ($stmt->execute()) {
        header('Location: produto.php?id=' . $cod_produto);
        exit(); 
    } else {
        echo "Erro ao enviar avaliação: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
