<?php
if (!empty($_POST) and (empty($_POST['email']) or empty($_POST['senha']))) {
	header("Location: login.php"); exit;
}
$con = mysqli_connect('localhost', 'root', '', 'web_shoppe') or trigger_error(mysqli_error());

$email = mysqli_real_escape_string($con, $_POST['email']);
$senha = mysqli_real_escape_string($con, $_POST['senha']);

$sql  = "select cod_usuario, email, nome, nivel, cep from usuario where (email = '". $email ."') ";
$sql .= "and (senha = '".($senha) ."')";


$query = mysqli_query($con, $sql);

if (mysqli_num_rows($query) != 1) {
	header('Content-Type: text/html; charset=utf-8');
	echo "Login invalido!"; exit;
} else {
	$resultado = mysqli_fetch_assoc($query);
	

	if (!isset($_SESSION)) session_start();

	$_SESSION['UsuarioID'] = $resultado['cod_usuario'];
	$_SESSION['UsuarioNome'] = $resultado['nome'];
	$_SESSION['UsuarioNivel'] = $resultado['nivel'];
	$_SESSION['UsuarioCep'] = $resultado['cep'];

	switch($_SESSION['UsuarioNivel']){
		case 1: header("Location: home.php"); exit;break;
		case 2: header("Location: home.php"); exit;break;
		case 3: header("Location: home.php"); exit;break;
	}
}

?>