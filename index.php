
<?php

require_once __DIR__ . "/includes/conexao.php";

// Contar produtos cadastrados
$totalProdutos = $conexao->query(
    "SELECT COUNT(*) FROM produtos"
)->fetchColumn();

// Contar transportadoras cadastradas
$totalTransportadoras = $conexao->query(
    "SELECT COUNT(*) FROM transportadoras"
)->fetchColumn();

// Contar fretes registrados
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
        .sidebar {
     width: 250px;
     min-height: 100vh;
     background-color: #7f29a7da !important;
     position: fixed;
     top: 0;
     left: 0;
     }

        .sidebar a {
            display: block;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .sidebar a:hover {
            background-color: #000000;
        }
    </style>




</head>


<body class="bg-light">

    <div class="container-fluid">
        <div class="row">

            <!-- Menu lateral -->
            
<nav class="sidebar text-white p-3">
    <h4 class="mb-4">Gerenciador Logístico</h4>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a href="index.php" class="nav-link text-white">
                🏠 Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="produtos.php" class="nav-link text-white">
                ➕ Cadastrar produto
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="lista_produtos.php" class="nav-link text-white">
                📦 Produtos cadastrados
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="enderecos.php" class="nav-link text-white">
                ➕ Cadastrar endereço
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="lista_enderecos.php" class="nav-link text-white">
                📍 Endereços cadastrados
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="transportadoras.php" class="nav-link text-white">
                ➕ Cadastrar transportadora
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="lista_transportadoras.php" class="nav-link text-white">
                🚚 Transportadoras cadastradas
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="fretes.php" class="nav-link text-white">
                📝 Gerar frete
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="lista_fretes.php" class="nav-link text-white">
                📋 Fretes cadastrados
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="relatorios.php" class="nav-link text-white">
                📊 Relatórios
            </a>
        </li>

    </ul>
</nav>

                <h4>Logística</h4>
                <hr>

                <a href="index.php" class="nav-link text-white p-3">
                Dashboard
               </a>

                <a href="produtos.php" class="nav-link text-white p-3">
                Produtos
               </a>

                <a href="enderecos.php" class="nav-link text-white p-3">
                Endereços
               </a>

                <a href="transportadoras.php" class="nav-link text-white p-3">
                Transportadoras
               </a>

                <a href="fretes.php" class="nav-link text-white p-3">
                Fretes
               </a>

                <a href="relatorios.php" class="nav-link text-white p-3">
                Relatórios
               </a>

                <a href="lista_fretes.php" class="nav-link text-white p-3">
                Fretes cadastrados
               </a>


            </nav>

            <!-- Conteúdo principal -->
            <main class="p-4" style="margin-left: 250px;">

                <h2>Gerenciador Logístico</h2>
                <p class="text-muted">
                    Bem-vindo ao painel de controle!
                </p>

                <div class="row g-3 mt-3">

                    <div class="col-md-4">
                        <div class="card shadow-sm p-3">
                            <h5>Produtos</h5>
                            <h2><?= $totalProdutos ?></h2>
                            <p class="text-muted">Produtos cadastrados</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm p-3">
                            <h5>Transportadoras</h5>
                            <h2><?= $totalTransportadoras ?></h2>
                            <p class="text-muted">Empresas cadastradas</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm p-3">
                            <h5>Fretes</h5>
                            <h2><?= $totalFretes ?></h2>
                            <p class="text-muted">Fretes registrados</p>
                        </div>
                    </div>

                </div>

            </main>

        </div>
    </div>

</body>
</html>
