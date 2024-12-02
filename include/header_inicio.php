<?php 
if (!isset($_SESSION)) session_start();
require_once "../base/conex.php";

// Obtém o usuário logado, se existir
$UsuarioID = $_SESSION['UsuarioID'] ?? null;

$info = null;
if ($UsuarioID) {
    $sql = mysqli_query($con, "SELECT * FROM usuario WHERE cod_usuario = '$UsuarioID'");
    $info = mysqli_fetch_assoc($sql);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shoppe</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
    <header class="header-purple">
        <div class="logo">
            <a href="../PHP/home.php">
                <img src="../IMAGENS/logo.png" alt="Logo">
                <h1>Web Shoppe</h1>
            </a>
        </div>

        <div id="container-search">
            <form action="resultado.php" method="POST" class="search-bar">
                <input type="search" name="query" placeholder="Pesquisar produto" required>
                <input type="image" src="../IMAGENS/Header/lupa.png" alt="Buscar">
            </form>
            <a href="carrinho.php">
                <img id="img-carrinho" src="../IMAGENS/Header/carrinho.png" alt="Carrinho">
                <span>Carrinho</span>
            </a>
        </div>

        <div class="user">
            <?php if ($info): ?>
                <!-- Se o usuário estiver logado -->
                <button class="btn btn-login" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                    <?= htmlspecialchars($info['nome']); ?>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                    <div class="offcanvas-header">
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div>
                            <figure class="sem-img">
                                <a href="#"><img src="../IMAGENS/produto/sem imagem.png" alt="" class="perfil"></a>
                            </figure>
                            <h2 class="nperfil"><?= htmlspecialchars($info['nome']); ?></h2>
                        </div>
                        <div class="div-opcoes">
                            <figure class="opcoes">
                                <img src="../IMAGENS/perfil/perfil.png" alt="" class="img-perfil">
                                <a href="../PHP/perfil.php"><h5 class="a-opc">Perfil</h5></a>
                            </figure>
                            <?php if ($info['nivel'] == 1): ?>
                                <figure class="opcoes">
                                    <img src="../IMAGENS/perfil/carrinho-de-compras.png" alt="" class="img-perfil">
                                    <a href="../PHP/form_cadastro_loja.php"><h5 class="a-opc">Começar a Vender</h5></a>
                                </figure>
                            <?php elseif ($info['nivel'] == 2): ?>
                                <figure class="opcoes">
                                    <img src="../IMAGENS/perfil/carrinho-de-compras.png" alt="" class="img-perfil">
                                    <a href="../PHP/minha_loja.php?id=<?php echo $_SESSION['UsuarioID']; ?>"><h5 class="a-opc">Minha Loja</h5></a>                                </figure>
                                <figure class="opcoes">
                                    <img src="../IMAGENS/perfil/pasta-aberta.png" alt="" class="img-perfil">    
                                    <a href="../dashboard eu/dashboard.php"><h5 class="a-opc">Dashboard</h5></a>
                                </figure>
                            <?php elseif ($info['nivel'] == 3): ?>
                                <figure class="opcoes">
                                    <img src="../IMAGENS/perfil/pasta-aberta.png" alt="" class="img-perfil">    
                                    <a href="../dashboard eu/dashboard.php"><h5 class="a-opc">Dashboard</h5></a>
                                </figure>
                            <?php endif; ?>
                            <hr class="dropdown-divider">
                            <figure class="opcoes">
                                <img src="../IMAGENS/perfil/sair.png" alt="" class="img-perfil">
                                <a href="../PHP/logout.php"><h5 class="a-opc">Logout</h5></a>
                            </figure>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Se o usuário NÃO estiver logado -->
                 <div>
                     <a href="../PHP/fcadastro.php" class="btn btn-cadastro">Cadastre-se</a>   
                     <a href="../PHP/login.php" class="btn btn-login">Login</a>
                 </div>
            <?php endif; ?>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="../PHP/home.php">Início</a></li>
            <li><a href="../PHP/categoria.php">Categorias</a></li>
            <li><a href="../PHP/promocoes.php">Promoções</a></li>
            <li><a href="../PHP/compras.php">Minhas Compras</a></li>
            <li><a href="../PHP/suporte.php">Suporte</a></li>
        </ul>
    </nav>
    <hr>
</body>
</html>