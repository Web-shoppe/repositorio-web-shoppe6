    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $host = 'localhost';
    $dbname = 'web_shoppe';
    $user = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }

    $query = "SELECT 
                l.cod_loja, 
                l.nome, 
                l.cnpj, 
                l.foto_loja, 
                l.banner
            FROM loja l
            ORDER BY l.nome ASC;";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Lojas</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: url('background.jpg') no-repeat center center fixed; 
                background-size: cover;
                margin: 0;
                padding: 0;
                color: #000; 
            }
            .container {
                width: 90%;
                max-width: 1200px;
                margin: 50px auto;
                background-color: transparent; 
                border-radius: 10px;
                overflow: hidden;
                border: none;
            }
            .header {
                text-align: center;
                font-size: 36px;
                font-weight: bold;
                padding: 20px 0;
                color: #000; 
                text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
            }
            .btn-gerar-relatorio {
                padding: 8px 16px;
                font-size: 14px;
                font-weight: bold;
                background-color: #28a745;  
                color: white;
                border: none;
                border-radius: 25px;  
                cursor: pointer;
                margin-bottom: 20px;
                display: inline-flex;
                align-items: center;
                gap: 8px;  
                transition: background-color 0.3s ease, transform 0.2s ease;
            }
            .btn-gerar-relatorio:hover {
                background-color: #218838; 
                transform: scale(1.05); 
            }
            .btn-gerar-relatorio i {
                font-size: 18px;  
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                padding: 12px 15px;
                text-align: left;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }
            th {
                text-transform: uppercase;
                font-size: 14px;
                color: #000; 
                text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            }
            tr:hover {
                background-color: rgba(0, 0, 0, 0.05);
            }
            td {
                font-size: 16px;
                color: #000; 
            }
            .image-container img {
                max-width: 80px;
                height: auto;
                border-radius: 8px;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            }
            .action-btns {
                display: flex;
                justify-content: center;
                gap: 10px;
            }
            .btn {
                text-decoration: none;
                padding: 8px 12px;
                font-size: 14px;
                font-weight: bold;
                border-radius: 5px;
                display: flex;
                align-items: center;
                gap: 5px;
                transition: transform 0.3s ease, background-color 0.3s ease;
            }
            .btn i {
                margin-right: 5px;
            }
            .btn-edit {
                color: #007bff;
            }
            .btn-edit:hover {
                transform: scale(1.1);
                color: #0056b3;
            }
            .btn-delete {
                color: #dc3545;
            }
            .btn-delete:hover {
                transform: scale(1.1);
                color: #b02a37;
            }
            .btn-view {
                color: #28a745;
            }
            .btn-view:hover {
                transform: scale(1.1);
                color: #218838;
            }
            .no-data {
                padding: 20px;
                text-align: center;
                font-size: 18px;
                color: #000; 
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <i class="fas fa-store"></i> Lista de Lojas
                <a href="base/relatorio/gerar_relatorio.php?tipo=loja" class="btn-gerar-relatorio">
                    <i class="fas fa-download"></i> Gerar Relatório
                </a>
            </div>

            <?php if ($lojas) : ?>
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-barcode"></i> Código</th>
                            <th><i class="fas fa-store-alt"></i> Nome da Loja</th>
                            <th><i class="fas fa-file-alt"></i> CNPJ</th>
                            <th><i class="fas fa-image"></i> Foto</th>
                            <th><i class="fas fa-image"></i> Banner</th>
                            <th><i class="fas fa-tools"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lojas as $loja) : ?>
                            <tr>
                                <td><?= htmlspecialchars($loja['cod_loja']) ?></td>
                                <td><?= htmlspecialchars($loja['nome']) ?></td>
                                <td><?= htmlspecialchars($loja['cnpj']) ?></td>
                                <td class="image-container">
                                    <?php if ($loja['foto_loja']) : ?>
                                        <img src="uploads/<?= htmlspecialchars($loja['foto_loja']) ?>" alt="Foto da Loja">
                                    <?php else : ?>
                                        <em>Foto não disponível</em>
                                    <?php endif; ?>
                                </td>
                                <td class="image-container">
                                    <?php if ($loja['banner']) : ?>
                                        <img src="uploads/<?= htmlspecialchars($loja['banner']) ?>" alt="Banner da Loja">
                                    <?php else : ?>
                                        <em>Banner não disponível</em>
                                    <?php endif; ?>
                                </td>
                                <td class="action-btns">
                                    <a href="base/editar_loja2.php?cod_loja=<?= $loja['cod_loja'] ?>" class="btn btn-edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="base/excluir_loja.php?cod_loja=<?= $loja['cod_loja'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir esta loja?')">
                                        <i class="fas fa-trash-alt"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="no-data"><i class="fas fa-exclamation-circle"></i> Nenhuma loja encontrada.</p>
            <?php endif; ?>
        </div>
    </body>
    </html>
