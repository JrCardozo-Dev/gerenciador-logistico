
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

    <title>Fretes Cadastrados - Gerenciador Logístico</title>

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

        .table-card {
            border: none;
            border-radius: 12px;
            padding: 25px;
        }

        .table thead th {
            background-color: #f0e6f5;
            color: #41364a;
            white-space: nowrap;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #8529a7;
            border-color: #8529a7;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #641b82;
            border-color: #641b82;
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
            <a class="nav-link active" href="lista_fretes.php">📋 Fretes cadastrados</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="relatorios.php">📊 Relatórios</a>
        </li>

    </ul>

</nav>

<main class="main-content">

    <div class="mb-4">

        <h2 class="page-title">📋 Fretes cadastrados</h2>

        <p class="text-muted">
            Consulte todos os transportes registrados no sistema.
        </p>

    </div>

    <div class="mb-4">

        <a href="fretes.php" class="btn btn-primary">
            ➕ Novo frete
        </a>

    </div>

    <div class="card shadow-sm table-card">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
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
                                    <span class="badge bg-warning text-dark">
                                        <?= htmlspecialchars($frete["status"]) ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Nenhum frete cadastrado ainda.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</main>

</body>
</html>
