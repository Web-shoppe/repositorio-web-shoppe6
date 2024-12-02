<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuario_id = $_POST['usuario_id'] ?? null;
$cod_produto = $_POST['cod_produto'] ?? null;

if ($usuario_id && $cod_produto) {
    $con = mysqli_connect("localhost", "root", "", "web_shoppe");
    if (!$con) {
        die(json_encode(["success" => false, "error" => "Erro de conexão: " . mysqli_connect_error()]));
    }

    $sql = "DELETE FROM carrinho WHERE cod_prod = ? AND cod_usuario = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $cod_produto, $usuario_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    mysqli_close($con);
} else {
    echo json_encode(["success" => false, "error" => "Dados inválidos."]);
}
?>
