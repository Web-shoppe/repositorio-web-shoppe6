<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
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
        .user-card {
            background-color: #ffffff; 
            border-radius: 12px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
            padding: 1rem; 
            margin-left: 20px; 
            transition: transform 0.2s; 
        }
        .user-card:hover {
            transform: scale(1.02); 
        }
        .input-group-text {
            background-color: #f1f1f1;
            border: 1px solid #ced4da; 
            border-radius: 0 0.25rem 0.25rem 0; 
        }
        .form-control:focus {
            border-color: #6D09A4;
            box-shadow: 0 0 5px rgba(109, 9, 164, 0.5); 
        }
        .form-label {
            font-weight: bold; 
        }
    </style>
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

            $('#telefone').on('input', function() {
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
</head>
<body>
    <main class="container mt-4">
        <h1>Adicionar Usuário</h1>
        <div class="row">
            <div class="col-md-6">
                <form action="base/add.php" method="post">
                    <input type="hidden" name="tipo" value="usu">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite o nome completo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Digite o e-mail" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="senha" class="form-label">Senha</label>
                            <div class="input-group">
                                <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite a senha" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" name="telefone" id="telefone" class="form-control" placeholder="(XX) XXXXX-XXXX" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" name="cpf" id="cpf" class="form-control" placeholder="000.000.000-00" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" name="cep" id="cep" class="form-control" placeholder="Digite o CEP" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="nivel" class="form-label">Nível</label>
                            <input type="number" name="nivel" id="nivel" class="form-control" min="1" max="2" placeholder="Digite o nível do usuário" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ativo" class="form-label">Ativo</label>
                            <input type="number" name="ativo" id="ativo" class="form-control" min="1" max="2" placeholder="1 - Ativo, 2 - Inativo" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="tipo_usuario" class="form-label">Tipo de Usuário</label>
                            <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                                <option value="usuario">Usuário</option>
                                <option value="admin">Admin</option>
                                <option value="lojista">Lojista</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cadastro" class="form-label">Data de Cadastro</label>
                            <input type="date" name="cadastro" id="cadastro" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <div class="user-card">
                    <h4>Pré-visualização do Usuário</h4>
                    <p><strong>Nome:</strong> <span id="cardNome">Nome do Usuário</span></p>
                    <p><strong>Email:</strong> <span id="cardEmail">email@example.com</span></p>
                    <p><strong>Telefone:</strong> <span id="cardTelefone">+55 12 34567-8901</span></p>
                    <p><strong>Senha:</strong> <span id="cardSenha">********</span> <span id="toggleCardPassword" style="cursor: pointer;"><i class="fas fa-eye" id="cardEyeIcon"></i></span></p>
                    <p><strong>CPF:</strong> <span id="cardCpf">000.000.000-00</span></p>
                    <p><strong>CEP:</strong> <span id="cardCep">00000-000</span></p>
                    <p><strong>Nível:</strong> <span id="cardNivel">1</span></p>
                    <p><strong>Ativo:</strong> <span id="cardAtivo">1</span></p>
                    <p><strong>Tipo de Usuário:</strong> <span id="cardTipoUsuario">Usuário</span></p>
                    <p><strong>Data de Cadastro:</strong> <span id="cardCadastro"><?php echo date('d/m/Y'); ?></span></p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const formFields = [
            { input: 'nome', card: 'cardNome' },
            { input: 'email', card: 'cardEmail' },
            { input: 'telefone', card: 'cardTelefone' },
            { input: 'senha', card: 'cardSenha' },
            { input: 'cpf', card: 'cardCpf' },
            { input: 'cep', card: 'cardCep' },
            { input: 'nivel', card: 'cardNivel' },
            { input: 'ativo', card: 'cardAtivo' },
            { input: 'tipo_usuario', card: 'cardTipoUsuario' },
            { input: 'cadastro', card: 'cardCadastro' }
        ];

        formFields.forEach(field => {
            const inputElement = document.getElementById(field.input);
            const cardElement = document.getElementById(field.card);

            inputElement.addEventListener('input', function() {
                if (field.input === 'cadastro') {
                    const date = new Date(this.value);
                    cardElement.textContent = `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()}`;
                } else if (field.input === 'senha') {
                    cardElement.textContent = '********';
                } else {
                    cardElement.textContent = this.value || `Preencha este campo`;
                }
            });
        });

        const toggleCardPassword = document.getElementById('toggleCardPassword');
        const cardSenha = document.getElementById('cardSenha');
        const senhaInput = document.getElementById('senha');

        toggleCardPassword.addEventListener('click', function() {
            const isPasswordVisible = cardSenha.textContent !== '********';
            if (isPasswordVisible) {
                cardSenha.textContent = '********';
                this.querySelector('#cardEyeIcon').classList.remove('fa-eye-slash');
                this.querySelector('#cardEyeIcon').classList.add('fa-eye');
            } else {
                cardSenha.textContent = senhaInput.value;
                this.querySelector('#cardEyeIcon').classList.remove('fa-eye');
                this.querySelector('#cardEyeIcon').classList.add('fa-eye-slash');
            }
        });

        const togglePassword = document.getElementById('togglePassword');
        const senhaField = document.getElementById('senha');

        togglePassword.addEventListener('click', function() {
            const isPasswordVisible = senhaField.type === 'text';
            senhaField.type = isPasswordVisible ? 'password' : 'text';
            this.querySelector('#eyeIcon').classList.toggle('fa-eye');
            this.querySelector('#eyeIcon').classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
