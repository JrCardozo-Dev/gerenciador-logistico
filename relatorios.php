
<?php

require_once __DIR__ . "/includes/conexao.php";

$dataSelecionada = $_GET["data"] ?? "";
$fretes = [];
$mensagem = "";

// Verificar se a data foi informada corretamente
$dataValida = DateTime::createFromFormat("!Y-m-d", $dataSelecionada);

if (
    $dataSelecionada != "" &&
    $dataValida &&
    $dataValida->format("Y-m-d") == $dataSelecionada
) {

    // Buscar fretes da data selecionada
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

            WHERE f.data_frete = :data

            ORDER BY f.id DESC";

    $comando = $conexao->prepare($sql);

    $comando->execute([
        ":data" => $dataSelecionada
    ]);

    $fretes = $comando->fetchAll(PDO::FETCH_ASSOC);

} elseif ($dataSelecionada != "") {

    $mensagem = "Informe uma data válida!";

}

// Exportar relatório para CSV
if (
    isset($_GET["baixar"]) &&
    $_GET["baixar"] == "1" &&
    $dataValida &&
    $dataValida->format("Y-m-d") == $dataSelecionada
) {

    header("Content-Type: text/csv; charset=UTF-8");
    header("Content-Disposition: attachment; filename=relatorio_fretes.csv");

    $arquivo = fopen("php://output", "w");

    // BOM para facilitar a leitura de caracteres no Excel
    fwrite($arquivo, "\xEF\xBB\xBF");

    fputcsv($arquivo, [
        "ID",
        "Produto",
        "Origem",
        "Destino",
        "Transportadora",
        "Data",
        "Valor",
        "Status"
    ], ";");

    foreach ($fretes as $frete) {

        fputcsv($arquivo, [
            $frete["id"],
            $frete["produto"],
            $frete["origem"],
            $frete["destino"],
            $frete["transportadora"],
            date("d/m/Y", strtotime($frete["data_frete"])),
            number_format($frete["valor_frete"], 2, ",", "."),
            $frete["status"]
        ], ";");

    }

    fclose($arquivo);
    exit;
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relatórios de Fretes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Relatórios de Fretes</h2>

    <p class="text-muted">
        Consulte e exporte os fretes registrados em uma data específica.
    </p>

    <div class="card shadow-sm p-4 mt-4">

        <form method="GET">

            <div class="mb-3">
                <label class="form-label">Selecione a data</label>

                <input
                    type="date"
                    name="data"
                    class="form-control"
                    value="<?= htmlspecialchars($dataSelecionada) ?>"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Consultar Fretes
            </button>

            <?php if ($dataValida && count($fretes) > 0): ?>

                <button
                    type="submit"
                    name="baixar"
                    value="1"
                    class="btn btn-success">
                    Baixar Relatório CSV
                </button>

            <?php endif; ?>

            <a href="index.php" class="btn btn-secondary">
                Voltar ao Dashboard
            </a>

        </form>

    </div>

    <?php if ($mensagem != ""): ?>

        <div class="alert alert-warning mt-4">
            <?= htmlspecialchars($mensagem) ?>
        </div>

    <?php elseif ($dataValida): ?>

        <div class="card shadow-sm p-4 mt-4">

            <h4>Resultado da consulta</h4>

            <p>
                Fretes encontrados:
                <strong><?= count($fretes) ?></strong>
            </p>

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Transportadora</th>
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
                                        R$ <?= number_format($frete["valor_frete"], 2, ",", ".") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($frete["status"]) ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center">
                                    Nenhum frete encontrado nesta data.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

</div>

</body>
</html>
