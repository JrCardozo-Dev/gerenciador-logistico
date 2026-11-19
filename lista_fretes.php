
<?php

require_once __DIR__ . "/includes/conexao.php";

// Buscar os fretes e relacionar as tabelas
$sql = "SELECT
            f.id,
            p.nome AS produto,
            o.cidade AS origem,
            d.cidade AS destino,
            t.nome AS transportadora,
            f.data_frete,
            f.valor_frete,
            f.status

        FROM fretes f

        INNER JOIN produtos p
            ON f.produto_id = p.id

        INNER JOIN enderecos o
            ON f.endereco_origem_id = o.id

        INNER JOIN enderecos d
            ON f.endereco_destino_id = d.id

        INNER JOIN transportadoras t
            ON f.transportadora_id = t.id

        ORDER BY f.id DESC";

$fretes = $conexao->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fretes Cadastrados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Fretes Cadastrados</h2>

    <p class="text-muted">
        Consulte todos os transportes registrados no sistema.
    </p>

    <div class="mb-3">
        <a href="fretes.php" class="btn btn-primary">
            Novo Frete
        </a>

        <a href="index.php" class="btn btn-secondary">
            Voltar ao Dashboard
        </a>
    </div>

    <div class="card shadow-sm p-4">

        <div class="table-responsive">

            <table class="table table-striped table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>Transportadora</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($fretes) > 0): ?>

                        <?php foreach ($fretes as $frete): ?>

                            <tr>
                                <td><?= $frete["id"] ?></td>

                                <td>
                                    <?= htmlspecialchars($frete["produto"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($frete["origem"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($frete["destino"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($frete["transportadora"]) ?>
                                </td>

                                <td>
                                    <?= date("d/m/Y", strtotime($frete["data_frete"])) ?>
                                </td>

                                <td>
                                    R$ <?= number_format($frete["valor_frete"], 2, ",", ".") ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($frete["status"]) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center">
                                Nenhum frete cadastrado ainda.
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
