
<?php

require_once __DIR__ . "/includes/conexao.php";

$sql = "SELECT * FROM produtos ORDER BY id DESC";

$produtos = $conexao->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produtos cadastrados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Produtos cadastrados</h2>

    <p class="text-muted">Consulte e edite os produtos do sistema.</p>

    <a href="produtos.php" class="btn btn-primary mb-3">
        Novo produto
    </a>

    <a href="index.php" class="btn btn-secondary mb-3">
        Voltar ao Dashboard
    </a>

    <div class="card shadow-sm p-4">

        <div class="table-responsive">

            <table class="table table-striped table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Peso</th>
                        <th>Valor</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($produtos) > 0): ?>

                        <?php foreach ($produtos as $produto): ?>

                            <tr>
                                <td><?= $produto["id"] ?></td>

                                <td><?= htmlspecialchars($produto["nome"]) ?></td>

                                <td><?= number_format($produto["peso"], 2, ",", ".") ?> kg</td>

                                <td>R$ <?= number_format($produto["valor"], 2, ",", ".") ?></td>

                                <td>
                                    <a href="editar_produto.php?id=<?= $produto["id"] ?>"
                                       class="btn btn-warning btn-sm">
                                        Editar
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="text-center">
                                Nenhum produto cadastrado.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>
