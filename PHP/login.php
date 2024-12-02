<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.css">
    <link rel="shortcut icon" href="../IMAGENS/logo.png" type="image/x-icon">
    <title>Web Shoppe</title>
</head>

<main>
<div class="div-main">
        <div class="div-web">
            <h1>Web Shoppe</h1>
            <p>Não importa o tamanho do seu negócio, com nossa hospedagem de lojas, você tem a oportunidade de oferecer uma experiência de compra online excelente e de forma segura para seus clientes.</p>
        </div>
        <div class="div-cont">
            <div class="div3">
                <h3>Faça Login</h3>
                <div class="btns">
                    <button class="bt1"><img src="../IMAGENS/login/facebook 1.png" alt="">Facebook</button>
                    <button class="bt2"><img src="../IMAGENS/login/google 1.png" alt="">Google</button>   
                </div>
                <form action="validacao.php" method="post">
                    <div class="form">
                        <label for="email">Email</label>
                        <br>
                        <input type="text" name="email" id="usu">
                        <br>
                        <label for="senha">Senha</label>
                        <br>
                        <input type="password" name="senha" id="senha">
                    </div>
                <div class="login">
                    <input type="submit" value="Logar" class="bonner">
                    <a href="#">Esqueceu sua senha?</a>
                </div>
            </form>
            <div class="membro">
                Não é membro?<a href="fcadastro.php">Criar uma conta</a>
            </div>
        </div>
    </div>
</main>
