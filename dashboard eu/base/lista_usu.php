<?php
include "base/conexao.php";
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Usuários</h1>
        <div class="btn-group">
            <a href="dashboard.php?page=add_usu.php" class="btn btn-success">
                <i class="bx bx-plus"></i> Adicionar
            </a>
            <a href="base/relatorio/gerar_relatorio.php?tipo=usu" class="btn btn-primary">
                <i class="bx bx-download"></i> Gerar Relatório
            </a>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" class="form-control" id="search" placeholder="Pesquisar usuários...">
    </div>

    <table class="table table-hover table-bordered">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>CPF</th>
                <th>CEP</th>
                <th>Nível</th>
                <th>Ativo</th>
                <th>Data de Inserção</th>
                <th colspan="2" class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM usuario ORDER BY cod_usuario";
            $result = mysqli_query($conexao, $sql);

            if ($result) {
                while ($dados = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($dados['cod_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($dados['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($dados['email']) . "</td>";

                    $telefone = htmlspecialchars($dados['tel']);
                    if (strlen($telefone) >= 11) {
                        $ddd = substr($telefone, 0, 2);
                        $numero = substr($telefone, 2);
                        $telefoneFormatado = "($ddd) $numero";
                    } else {
                        $telefoneFormatado = $telefone; 
                    }
                    echo "<td>" . $telefoneFormatado . "</td>";

                    $cpf = htmlspecialchars($dados['cpf']);
                    $cpfFormatado = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
                    echo "<td>" . $cpfFormatado . "</td>";

                    echo "<td>" . htmlspecialchars($dados['cep']) . "</td>";
                    echo "<td>" . htmlspecialchars($dados['nivel']) . "</td>";
                    echo "<td>" . ($dados['ativo'] ? 'Sim' : 'Não') . "</td>";
                    echo "<td>" . htmlspecialchars($dados['cadastro']) . "</td>";
                    echo "<td><div class='btn-group'>";
                    echo "<a href='dashboard.php?page=fedit_usu.php&id=" . urlencode($dados['cod_usuario']) . "' class='btn btn-primary btn-sm'><i class='bx bx-edit'></i> Editar</a>";
                    echo "<a href='dashboard.php?page=remove.php&tipo=usu&id=" . urlencode($dados['cod_usuario']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este usuário?\");'><i class='bx bx-trash'></i> Excluir</a>";
                    echo "</div></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='12' class='text-center'>Nenhum usuário encontrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('search').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>
