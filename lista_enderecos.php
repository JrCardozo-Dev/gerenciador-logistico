

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
    <title>Endereços cadastrados - Gerenciador Logístico</title>

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
        }

        .sidebar .nav-link {
            color: white;
            padding: 11px 12px;
            margin-bottom: 7px;
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 8px;
            transition: 0.2s;
            font-size: 14px;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.18);
            border-color: white;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.22);
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
            background-color: #f0e8f4;
            color: #41364a;
            border-bottom: 2px solid #8529a7;
            padding: 14px;
        }

        .table tbody td {
            padding: 14px;
            vertical-align: middle;
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

        .btn-sm {
            padding: 6px 13px;
        }

        @media (max-width: 768px) {
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
                <a class="nav-link active" href="lista_enderecos.php">📍 Endereços cadastrados</a>
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
                <a class="nav-link" href="relatorios.php">📊 Relatórios</a>
            </li>

        </ul>
    </nav>

    <main class="main-content">

        <h2 class="page-title mb-2">📍 Endereços cadastrados</h2>

        <p class="text-muted mb-4">
            Consulte e edite os endereços de origem e destino registrados no sistema.
        </p>

        <div class="mb-4">
            <a href="enderecos.php" class="btn btn-primary">
                ➕ Novo endereço
            </a>
        </div>

        <div class="card shadow-sm table-card">

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Destinatário</th>
                            <th>CEP</th>
                            <th>Endereço</th>
                            <th>Cidade/UF</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (count($enderecos) > 0): ?>

                            <?php foreach ($enderecos as $endereco): ?>

                                <tr>
                                    <td><?= $endereco["id"] ?></td>

                                    <td>
                                        <?= htmlspecialchars($endereco["nome_destinatario"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($endereco["cep"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($endereco["rua"]) ?>,
                                        <?= htmlspecialchars($endereco["numero"] ?? "") ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($endereco["bairro"] ?? "") ?>
                                        </small>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($endereco["cidade"]) ?>/<?= htmlspecialchars($endereco["estado"]) ?>
                                    </td>

                                    <td>
                                        <a href="editar_endereco.php?id=<?= $endereco["id"] ?>"
                                           class="btn btn-primary btn-sm">
                                            ✏️ Editar
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Nenhum endereço cadastrado até o momento.
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

