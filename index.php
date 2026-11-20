
<?php

require_once __DIR__ . "/includes/conexao.php";

$totalProdutos = $conexao->query(
    "SELECT COUNT(*) FROM produtos"
)->fetchColumn();

$totalTransportadoras = $conexao->query(
    "SELECT COUNT(*) FROM transportadoras"
)->fetchColumn();

$totalFretes = $conexao->query(
    "SELECT COUNT(*) FROM fretes"
)->fetchColumn();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerenciador Logístico</title>

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

        .welcome {
            margin-bottom: 30px;
        }

        .welcome h2 {
            font-weight: 700;
            color: #30243a;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 22px;
            height: 100%;
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-card h2 {
            font-size: 32px;
            font-weight: bold;
            color: #8529a7;
            margin: 10px 0;
        }

        .stat-card p {
            color: #777;
            margin-bottom: 0;
        }

        .stat-icon {
            font-size: 25px;
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

    <!-- MENU LATERAL -->
    <nav class="sidebar text-white">

        <h4>📦 Gerenciador Logístico</h4>

        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="index.php" class="nav-link active">🏠 Dashboard</a>
            </li>

            <li class="nav-item">
                <a href="produtos.php" class="nav-link">➕ Cadastrar produto</a>
            </li>

            <li class="nav-item">
                <a href="lista_produtos.php" class="nav-link">📦 Produtos cadastrados</a>
            </li>

            <li class="nav-item">
                <a href="enderecos.php" class="nav-link">➕ Cadastrar endereço</a>
            </li>

            <li class="nav-item">
                <a href="lista_enderecos.php" class="nav-link">📍 Endereços cadastrados</a>
            </li>

            <li class="nav-item">
                <a href="transportadoras.php" class="nav-link">➕ Cadastrar transportadora</a>
            </li>

            <li class="nav-item">
                <a href="lista_transportadoras.php" class="nav-link">🚚 Transportadoras cadastradas</a>
            </li>

            <li class="nav-item">
                <a href="fretes.php" class="nav-link">📝 Gerar frete</a>
            </li>

            <li class="nav-item">
                <a href="lista_fretes.php" class="nav-link">📋 Fretes cadastrados</a>
            </li>

            <li class="nav-item">
                <a href="relatorios.php" class="nav-link">📊 Relatórios</a>
            </li>

        </ul>

    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-content">

        <div class="welcome">
            <h2>Dashboard</h2>
            <p class="text-muted">
                Bem-vindo ao painel de controle do Gerenciador Logístico!
            </p>
        </div>

        <div class="row g-4">

            <div class="col-12 col-md-6 col-xl-4">
                <div class="card shadow-sm stat-card">
                    <span class="stat-icon">📦</span>
                    <h5>Produtos</h5>
                    <h2><?= $totalProdutos ?></h2>
                    <p>Produtos cadastrados no sistema</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="card shadow-sm stat-card">
                    <span class="stat-icon">🚚</span>
                    <h5>Transportadoras</h5>
                    <h2><?= $totalTransportadoras ?></h2>
                    <p>Empresas cadastradas no sistema</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="card shadow-sm stat-card">
                    <span class="stat-icon">📝</span>
                    <h5>Fretes</h5>
                    <h2><?= $totalFretes ?></h2>
                    <p>Fretes registrados no sistema</p>
                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0 mt-4 p-4">
            <h5>Bem-vindo ao sistema!</h5>
            <p class="text-muted mb-0">
                Utilize o menu lateral para cadastrar produtos,
                endereços, transportadoras, gerar fretes e consultar relatórios.
            </p>
        </div>

    </main>

</body>
</html>
