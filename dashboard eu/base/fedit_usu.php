<?php 

$id = $_GET['id'] ?? '';

$conex = mysqli_connect("localhost", "root", "", "web_shoppe");

$sql = "SELECT * FROM usuario WHERE cod_usuario = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$dados = $result->fetch_assoc();

if (!$dados) {
    echo "Usuário não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #e9ecef;
        }
        main {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
        }
        h1 {
            color: #6D09A4;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background-color: #6D09A4;
            border: none;
        }
        .btn-primary:hover {
            background-color: #5b0080;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #6D09A4;
            box-shadow: 0 0 5px rgba(109, 9, 164, 0.5);
        }
        .user-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <main class="container mt-4">
        <h1>Editar Usuário</h1>
        <div class="row">
            <div class="col-md-6">
                <form action="base/edit.php" method="post">
                    <input type="hidden" name="tipo" value="usu">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                
                    <div class="row">
                        <div class="col-md-12">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($dados['nome']) ?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($dados['email']) ?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" value="<?= htmlspecialchars($dados['senha']) ?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="tel" class="form-label">Telefone</label>
                            <input type="tel" name="tel" id="tel" class="form-control" placeholder="(XX) XXXXX-XXXX" value="<?= isset($dados['tel']) ? htmlspecialchars($dados['tel']) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" name="cpf" id="cpf" class="form-control" placeholder="000.000.000-00" value="<?= isset($dados['cpf']) ? htmlspecialchars($dados['cpf']) : '' ?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="nivel" class="form-label">Nível</label>
                            <input type="number" name="nivel" id="nivel" class="form-control" value="<?= isset($dados['nivel']) ? htmlspecialchars($dados['nivel']) : '' ?>" min="1" max="2" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ativo" class="form-label">Ativo</label>
                            <input type="number" name="ativo" id="ativo" class="form-control" value="<?= isset($dados['ativo']) ? htmlspecialchars($dados['ativo']) : '' ?>" min="1" max="2" required>
                        </div>
                    </div>
                  
                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block">Salvar Alterações</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <div class="user-card">
                    <h5>Pré-visualização do Usuário</h5>
                    <p><strong>Nome:</strong> <span id="cardNome"><?= htmlspecialchars($dados['nome']) ?></span></p>
                    <p><strong>Email:</strong> <span id="cardEmail"><?= htmlspecialchars($dados['email']) ?></span></p>
                    <p><strong>Telefone:</strong> <span id="cardTelefone"><?= isset($dados['tel']) ? htmlspecialchars($dados['tel']) : 'N/A' ?></span></p>
                    <p><strong>Senha:</strong> <span id="cardSenha">********</span></p>
                    <p><strong>CPF:</strong> <span id="cardCpf"><?= isset($dados['cpf']) ? htmlspecialchars($dados['cpf']) : 'N/A' ?></span></p>
                    <p><strong>Nível:</strong> <span id="cardNivel"><?= isset($dados['nivel']) ? htmlspecialchars($dados['nivel']) : 'N/A' ?></span></p>
                    <p><strong>Ativo:</strong> <span id="cardAtivo"><?= isset($dados['ativo']) ? htmlspecialchars($dados['ativo']) : 'N/A' ?></span></p>
                    
                    <p><strong>Data de Cadastro:</strong> <span id="cardCadastro"><?= date('d/m/Y', strtotime($dados['cadastro'])) ?></span></p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cpf').on('input', function() {
                let cpf = $(this).val().replace(/\D/g, '');
                cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
                cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                $(this).val(cpf);
            });

            $('#tel').on('input', function() {
                let telefone = $(this).val().replace(/\D/g, '');
                if (telefone.length > 10) {
                    telefone = telefone.substring(0, 11); 
                }
                if (telefone.length > 6) {
                    telefone = `(${telefone.slice(0, 2)}) ${telefone.slice(2, 7)}-${telefone.slice(7)}`;
                } else if (telefone.length > 2) {
                    telefone = `(${telefone.slice(0, 2)}) ${telefone.slice(2)}`;
                }
                $(this).val(telefone);
            });
        });
    </script>
</body>
</html>
