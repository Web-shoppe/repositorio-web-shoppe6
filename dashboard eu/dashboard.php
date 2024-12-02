<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .col,
        .col-md-2,
        .col-md-4,
        .col-md-5,
        .col-md-6 {
            margin: 0.5rem 0;
        }

        .logout {
            width: 100px;
            color: white;
            margin: 0 2rem 0 0;
        }

        .home-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .sidebar {
            background-color: #6D09A4; 
        }

        .home-section .home-content .text {
            color: #6D09A4; 
        }

    </style>
</head>
<body>
    <?php
    session_start();

    if (!isset($_SESSION['UsuarioID'])) {
        header("Location: index.php");
        exit;
    }

    function view_itens() {
        require_once "base/conexao.php";
        $query = "SELECT * FROM usuario WHERE cod_usuario = " . $_SESSION['UsuarioID'] . ";";
        $busca = mysqli_query($conexao, $query);
        $info = mysqli_fetch_assoc($busca);

        switch ($info['nivel']) {
            case 2:
                $itens = [
                    ['link' => 'dashboard.php?page=lista_prod.php', 'icon' => 'bx-box', 'name' => 'Produtos'],
                    ['link' => 'dashboard.php?page=lista_venda.php', 'icon' => 'bx-cart', 'name' => 'Vendas'],
                    ['link' => 'dashboard.php?page=perfil.php', 'icon' => 'bx-user', 'name' => 'Perfil'],
                    ['link' => 'dashboard.php?page=edit_loja.php', 'icon' => 'bx-store-alt', 'name' => 'Editar Loja'],
                    ['link' => '../PHP/home.php', 'icon' => 'bx-home-alt', 'name' => 'Home']
                ];
                break;
            case 3:
                $itens = [
                    ['link' => 'dashboard.php', 'icon' => 'bx-home', 'name' => 'Explorar'],
                    ['link' => 'dashboard.php?page=lista_usu.php', 'icon' => 'bx-group', 'name' => 'Usuários'],
                    ['link' => 'dashboard.php?page=lista_prodadm.php', 'icon' => 'bx-box', 'name' => 'Produtos'],
                    ['link' => 'dashboard.php?page=lista_vendas_adm.php', 'icon' => 'bx-cart', 'name' => 'Vendas'],
                    ['link' => 'dashboard.php?page=lista_lojistas.php', 'icon' => 'bx-store', 'name' => 'Lojistas'],
                    ['link' => 'dashboard.php?page=lista_lojas.php', 'icon' => 'bx-store-alt', 'name' => 'Lojas'],
                    ['link' => '../PHP/home.php', 'icon' => 'bx-home-alt', 'name' => 'Home']
                ];
                break;
            default:
                $itens = [
                    ['link' => 'dashboard.php?page=lista_prod.php', 'icon' => 'bx-box', 'name' => 'Produtos'],
                    ['link' => '../PHP/home.php', 'icon' => 'bx-home-alt', 'name' => 'Home']
                ];
                break;
        }
    ?>

    <div class="sidebar close">
        <div class="logo-details">
            <figure><img src="../IMAGENS/footer/logo-branca.png" alt="" style="width:40px; margin: 30px 15px 0px 15px"></figure>
            <span class="logo_name">Web Shoppe</span>
        </div>
        <ul class="nav-links">
            <?php foreach ($itens as $item): ?>
                <li>
                    <a href="<?= $item['link'] ?>">
                        <i class="bx <?= $item['icon'] ?>"></i>
                        <span class="link_name"><?= $item['name'] ?></span>
                    </a>
                    <ul class="sub-menu blank">
                        <li><a class="link_name" href="<?= $item['link'] ?>"><?= $item['name'] ?></a></li>
                    </ul>
                </li>
            <?php endforeach; ?>
            <li>
                <div class="profile-details">
                    <div class="profile-content"></div>
                    <div class="name-job">
                        <div class="profile_name"><?= htmlspecialchars($_SESSION['UsuarioNome']) ?></div>
                        <div class="job">Tabelas: <?= count($itens) ?></div>
                    </div>
                    <a href="logout.php" class="logout"><i class="bx bx-log-out"></i></a>
                </div>
            </li>
        </ul>
    </div>
    <?php } view_itens(); ?>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text"></span>
        </div>
        <div class="container">
            <?php if (!isset($_GET['page']) || ($_GET['page'] != 'add_prod.php' && $_GET['page'] != 'add_usu.php')) { ?>
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <?php require_once "base/chPages.php"; ?>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let arrow = document.querySelectorAll(".arrow");
            arrow.forEach(arrowItem => {
                arrowItem.addEventListener("click", (e) => {
                    let arrowParent = e.target.closest(".nav-links");
                    arrowParent.classList.toggle("showMenu");
                });
            });

            let sidebar = document.querySelector(".sidebar");
            let sidebarBtn = document.querySelector(".bx-menu");
            sidebarBtn.addEventListener("click", () => {
                sidebar.classList.toggle("close");
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
