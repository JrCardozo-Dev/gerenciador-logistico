
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

    <title>Relatórios - Gerenciador Logístico</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f5f9;
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #8529a7, #641b82);
            padding: 25px 15px;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h4 {
            font-size: 20px;
            font-weight: bold;
            padding: 0 10px;
            margin-bottom: 30px;
            color: white;
        }

        .sidebar .nav-link {
            color: white;
            padding: 11px 12px;
            margin-bottom: 7px;
            border: 1px solid rgba(255,255,255,0.20);
            border-radius: 8px;
            transition: 0.2s;
            font-size: 14px;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.18);
            border-color: white;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.22);
            border-color: white;
            font-weight: bold;
        }

        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
            padding: 35px;
        }

        .page-title {
            font-weight: 700;
            color: #30243a;
        }

        .report-card {
            border: none;
            border-radius: 12px;
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #41364a;
        }

        .form-control {
            padding: 11px;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #8529a7;
            box-shadow: 0 0 0 0.2rem rgba(133,41,167,0.15);
        }

        .btn-primary {
            background-color: #8529a7;
            border-color: #8529a7;
            padding: 11px 22px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #641b82;
            border-color: #641b82;
        }

        .btn-success {
            padding: 11px 22px;
            border-radius: 8px;
        }

        .table thead th {
            background-color: #f0e6f5;
            color: #41364a;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
        }

        @media(max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<nav class="sidebar text-white">

    <h4>📦 Gerenciador Logístico</h4>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link" href="index.php">🏠 Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="produtos.php">➕ Cadastrar produto</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="lista_produtos.php">📦 Produtos cadastrados</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="enderecos.php">➕ Cadastrar endereço</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="lista_enderecos.php">📍 Endereços cadastrados</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="transportadoras.php">➕ Cadastrar transportadora</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="lista_transportadoras.php">🚚 Transportadoras cadastradas</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="fretes.php">📝 Gerar frete</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="lista_fretes.php">📋 Fretes cadastrados</a>
        </li>

        <li class="nav-item">
            <a class="nav-link active" href="relatorios.php">📊 Relatórios</a>
        </li>

    </ul>

</nav>

<main class="main-content">

    <div class="mb-4">

        <h2 class="page-title">📊 Relatórios de fretes</h2>

        <p class="text-muted">
            Consulte e exporte os fretes registrados em uma data específica.
        </p>

    </div>

    <div class="card shadow-sm report-card">

        <h5 class="mb-3">Consultar relatório</h5>

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
                🔎 Consultar fretes
            </button>

            <?php if ($dataValida && count($fretes) > 0): ?>

                <button
                    type="submit"
                    name="baixar"
                    value="1"
                    class="btn btn-success">

                    📥 Baixar relatório CSV

                </button>

            <?php endif; ?>

        </form>

    </div>

    <?php if ($mensagem != ""): ?>

        <div class="alert alert-warning mt-4">
            <?= htmlspecialchars($mensagem) ?>
        </div>

    <?php elseif ($dataValida): ?>

        <div class="card shadow-sm report-card mt-4">

            <h5 class="mb-3">Resultado da consulta</h5>

            <p class="text-muted">
                Data consultada:
                <strong><?= date("d/m/Y", strtotime($dataSelecionada)) ?></strong>
            </p>

            <p>
                Fretes encontrados:
                <strong><?= count($fretes) ?></strong>
            </p>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
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
                                        <span class="badge bg-warning text-dark">
                                            <?= htmlspecialchars($frete["status"]) ?>
                                        </span>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Nenhum frete encontrado nesta data.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

</main>

</body>
</html>
