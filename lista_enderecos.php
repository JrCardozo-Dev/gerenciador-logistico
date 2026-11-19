
<?php

require_once __DIR__ . "/includes/conexao.php";

$sql = "SELECT * FROM enderecos ORDER BY id DESC";
$enderecos = $conexao->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endereços cadastrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Endereços cadastrados</h2>
    <p class="text-muted">Consulte e edite os endereços registrados.</p>

    <a href="enderecos.php" class="btn btn-primary mb-3">Novo endereço</a>
    <a href="index.php" class="btn btn-secondary mb-3">Voltar ao Dashboard</a>

    <div class="card shadow-sm p-4">
        <div class="table-responsive">

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Destinatário</th>
                        <th>Rua</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (count($enderecos) > 0): ?>

                    <?php foreach ($enderecos as $endereco): ?>
                        <tr>
                            <td><?= $endereco["id"] ?></td>
                            <td><?= htmlspecialchars($endereco["nome_destinatario"]) ?></td>
                            <td><?= htmlspecialchars($endereco["rua"]) ?></td>
                            <td><?= htmlspecialchars($endereco["cidade"]) ?></td>
                            <td><?= htmlspecialchars($endereco["estado"]) ?></td>
                            <td>
                                <a href="editar_endereco.php?id=<?= $endereco["id"] ?>"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="text-center">
                            Nenhum endereço cadastrado.
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
