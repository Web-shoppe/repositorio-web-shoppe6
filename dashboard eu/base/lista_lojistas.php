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

    $query = "SELECT l.cod_loja, l.nome_lojista, l.cpf, l.tel1, l.tel2, l.email, l.cep
            FROM loja l
            ORDER BY l.nome_lojista ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $lojistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['generate_report'])) {

        $header = ["Código", "Nome", "CPF", "Telefone", "Email", "CEP"];
        
        $filename = "relatorio_lojistas.csv";
        $file = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        fputcsv($file, $header);
        
        foreach ($lojistas as $lojista) {
            fputcsv($file, $lojista);
        }
        
        fclose($file);
        exit;
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Lojistas</title>
        <!-- Link para o Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: transparent; 
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                max-width: 1400px;
                margin: 50px auto;
                padding: 20px;
                background-color: transparent;
                box-shadow: none; 
            }

            .header {
                font-size: 28px;
                font-weight: bold;
                color: #333333;
                margin-bottom: 20px;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .header i {
                margin-right: 10px;
                color: #6c63ff;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background-color: transparent; 
            }

            table th, table td {
                padding: 16px 20px;
                text-align: left;
                font-size: 16px;
                color: #333;
                border-bottom: 1px solid #ddd;
                background-color: transparent; 
            }

            table th {
                color: #000; 
            }

            table tr:hover td {
                background-color: transparent; 
            }

            .btn {
                text-decoration: none;
                padding: 8px 12px;
                font-size: 14px;
                font-weight: bold;
                border-radius: 5px;
                transition: background-color 0.3s ease;
                color: white;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .btn i {
                font-size: 14px;
            }

            .btn-edit {
                background-color: #28a745;
            }

            .btn-edit:hover {
                background-color: #218838;
            }

            .btn-delete {
                background-color: #dc3545;
            }

            .btn-delete:hover {
                background-color: #b02a37;
            }

            .btn-view {
                background-color: #6c63ff;
            }

            .btn-view:hover {
                background-color: #4b47d9;
            }

            .action-btns {
                display: flex;
                justify-content: space-evenly;
            }

            .no-data {
                text-align: center;
                font-size: 18px;
                color: #999;
            }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="header">
                <i class="fas fa-store"></i>
                Lista de Lojistas
            </div>

            <form method="POST">
                <button type="submit" name="generate_report" class="btn btn-view">
                    <a href="base/relatorio/gerar_relatorio.php?tipo=lojistas">
                    <i class="fas fa-download"></i> Gerar Relatório</a>
                </button>
            </form>

            <?php if ($lojistas) : ?>
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> Código</th>
                            <th><i class="fas fa-user"></i> Nome</th>
                            <th><i class="fas fa-id-card"></i> CPF</th>
                            <th><i class="fas fa-phone"></i> Telefone</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-map-marker-alt"></i> CEP</th>
                            <th><i class="fas fa-tools"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lojistas as $lojista) : ?>
                            <tr>
                                <td><?= htmlspecialchars($lojista['cod_loja']) ?></td>
                                <td><?= htmlspecialchars($lojista['nome_lojista']) ?></td>
                                <td><?= htmlspecialchars($lojista['cpf']) ?></td>
                                <td><?= htmlspecialchars($lojista['tel1']) ?> / <?= htmlspecialchars($lojista['tel2']) ?></td>
                                <td><?= htmlspecialchars($lojista['email']) ?></td>
                                <td><?= htmlspecialchars($lojista['cep']) ?></td>
                                <td class="action-btns">
                                    <<a href="base/editar_lojista.php?cod_loja=<?= $lojista['cod_loja'] ?>" class="btn btn-edit">
    <i class="fas fa-edit"></i> Editar
</a>
<a href="base/excluir_lojista.php?cod_loja=<?= $lojista['cod_loja'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este lojista?')">
    <i class="fas fa-trash-alt"></i> Excluir
</a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="no-data"><i class="fas fa-exclamation-circle"></i> Nenhum lojista encontrado.</p>
            <?php endif; ?>
        </div>

    </body>
    </html>
