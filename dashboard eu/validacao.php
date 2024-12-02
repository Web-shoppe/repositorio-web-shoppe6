<?php
if (!empty($_POST) and (empty($_POST['email']) or empty($_POST['senha']))) {
	header("Location: index.php"); exit;
}
$con = mysqli_connect('localhost', 'root', '', 'web_shoppe') or trigger_error(mysqli_error($con));

$email = mysqli_real_escape_string($con, $_GET['usuario']);
$senha = mysqli_real_escape_string($con, $_GET['senha']);

$sql  = "select cod_usuario,	nivel from usuario where (email = '". $email ."') ";
$sql .= "and (senha = '". ($senha) ."') and (ativo = 1) limit 1";


$query = mysqli_query($con, $sql);

if (mysqli_num_rows($query) != 1) {
	header('Content-Type: text/html; charset=utf-8');
	echo "Login invalido!"; exit;
} else {
	$resultado = mysqli_fetch_assoc($query);
	

	if (!isset($_SESSION)) session_start();
	var_dump($_SESSION);
	$_SESSION['UsuarioID'] = $resultado['cod_usuario'];
	$_SESSION['UsuarioNome'] = $resultado['nome'];
	$_SESSION['UsuarioNivel'] = $resultado['nivel'];

	header("Location: dashboard.php");
}

?>