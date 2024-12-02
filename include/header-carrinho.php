<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shoppe</title>
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">
</head>
    <?php 
        require_once "../base/conex.php";
    ?>
<body>
    <header class="header-purple">

        <div class="logo">
            <a href="../PHP/Home.php">
                <img src="../IMAGENS/Logo.png" alt="Logo">
                <h1>Web Shoppe</h1>
            </a>
        </div>


        <div id="container-search">
    <form action="resultado.php" method="POST" class="search-bar">
        <input type="search" name="query" placeholder="Pesquisar produto" required>
        <input type="image" src="../IMAGENS/Header/lupa.png" alt="Buscar">
    </form>
</div>

        <div class="user">

            <?php
            include "../base/config.php";
            ?>

            

                <?php 
                     $UsuarioID = $_SESSION['UsuarioID'] ?? "";

                     $sql = mysqli_query($con, "SELECT * FROM usuario WHERE cod_usuario = '$UsuarioID'");
                     $RC = mysqli_num_rows($sql);
                     $info = mysqli_fetch_assoc($sql);

                     if($info['nivel'] == 1){
                        ?>
                     <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><?php echo $_SESSION['UsuarioNome']; ?></button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
<div class="offcanvas-header">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
        <div class="offcanvas-body">
            <div>
                <figure class="sem-img">
                    <a href="#">
                        <img src="../IMAGENS/produto/sem imagem.png" alt="" class="perfil">
                    </a>
                </figure>
                <h2 class="nperfil">
                <?php 
                     echo $_SESSION['UsuarioNome'];
             ?>
                </h2>
            </div>

            <div class="div-opcoes">
                <figure class="opcoes">
                <img src="../IMAGENS/perfil/perfil.png" alt="" class="img-perfil">
                    <a href="#"><h5 class="a-opc">Perfil</h5></a>
                </figure>
                <figure class="opcoes">
                    <img src="../IMAGENS/perfil/carrinho-de-compras.png" alt="" class="img-perfil">
                    <?php
                    echo "<a href='../PHP/form_cadastro_loja.php?'><h5 class='a-opc'>Começar a Vender</h5></a>";
                    ?>
                </figure>
                
                <hr class="dropdown-divider">
                <figure class="opcoes">
                    <img src="../IMAGENS/perfil/sair.png" alt="" class="img-perfil">
                    <a href="../PHP/logout.php"><h5 class="a-opc">Logout</h5></a>
                </figure>
            </div>
        </div>
    </div>

                        <?php
                     }
                     if($info['nivel'] == 2){
                        ?>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item active" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Favoritados</a></li>
                        <li><a class="dropdown-item" href="../PHP/loja.php">Minha Loja</a></li>
                        <li><a class="dropdown-item" href="../dashboard eu/dashboard.php">Dashboard</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../PHP/logout.php">Logout</a></li>
                    </ul>
                        <?php
                     }
                     if($info['nivel'] == 3){
                        ?>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item active" href="#">Perfil</a></li>
                        <li><a class="dropdown-item" href="#">Favoritados</a></li>
                        <li><a class="dropdown-item" href="../dashboard eu/dashboard.php">Dashboard</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../PHP/logout.php">Logout</a></li>
                    </ul>
                        <?php
                     }
                ?>
            </div>
        </div>
        </div>
        </div>
        </div>

    </header>
    <nav>
        <ul>
            <li><a href="../PHP/home.php">Início</a></li>
            <li><a href="../PHP/categoria.php">Categorias</a></li>
            <li><a href="#">Promoções</a></li>
            <li><a href="#">Histórico</a></li>
            <li><a href="../PHP/suporte.php">Suporte</a></li>
        </ul>
    </nav>
    <hr>


</body>

</html>