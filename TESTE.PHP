<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Avaliação de Estrelas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="rating">
        <form action="submit_rating.php" method="POST">
            <label>
                <input type="radio" name="rating" value="1" required>
                <span class="star">&#9733;</span>
            </label>
            <label>
                <input type="radio" name="rating" value="2">
                <span class="star">&#9733;</span>
            </label>
            <label>
                <input type="radio" name="rating" value="3">
                <span class="star">&#9733;</span>
            </label>
            <label>
                <input type="radio" name="rating" value="4">
                <span class="star">&#9733;</span>
            </label>
            <label>
                <input type="radio" name="rating" value="5">
                <span class="star">&#9733;</span>
            </label>
            <button type="submit">Enviar Avaliação</button>
        </form>
    </div>

    <?php
    
    
    
    ?>
</body>
</html>
