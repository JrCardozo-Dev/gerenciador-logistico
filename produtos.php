

<?php
require_once __DIR__ . "/includes/conexao.php";

$mensagem = "";
$tipoMensagem = "info";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $peso = $_POST["peso"];
    $valor = $_POST["valor"];

    if ($nome == "" || !is_numeric($peso) || !is_numeric($valor) || $peso <= 0 || $valor < 0) {

        $mensagem = "Preencha os campos corretamente!";
        $tipoMensagem = "danger";

    } else {

        try {

            $sql = "INSERT INTO produtos (nome, descricao, peso, valor)
                    VALUES (:nome, :descricao, :peso, :valor)";

            $comando = $conexao->prepare($sql);

            $comando->execute([
                ":nome" => $nome,
                ":descricao" => $descricao,
                ":peso" => $peso,
                ":valor" => $valor
            ]);

            $mensagem = "Produto cadastrado com sucesso!";
            $tipoMensagem = "success";

        } catch (PDOException $erro) {

            $mensagem = "Erro ao cadastrar produto.";
            $tipoMensagem = "danger";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Produtos - Gerenciador Logístico</title>

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

        .page-title {
            font-weight: 700;
            color: #30243a;
        }

        .form-card {
            border: none;
            border-radius: 12px;
            padding: 30px;
            max-width: 850px;
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
                <a href="index.php" class="nav-link">🏠 Dashboard</a>
            </li>

            <li class="nav-item">
                <a href="produtos.php" class="nav-link active">➕ Cadastrar produto</a>
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

        <h2 class="page-title">Cadastro de Produtos</h2>

        <p class="text-muted mb-4">
            Cadastre os produtos que serão transportados.
        </p>

        <div class="card shadow-sm form-card">

            <h4 class="mb-4">📦 Novo Produto</h4>

            <?php if ($mensagem != ""): ?>
                <div class="alert alert-<?= $tipoMensagem ?>">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label">Nome do produto</label>
                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        placeholder="Digite o nome do produto"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea
                        name="descricao"
                        class="form-control"
                        rows="3"
                        placeholder="Descreva o produto"></textarea>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Peso (kg)</label>
                        <input
                            type="number"
                            name="peso"
                            class="form-control"
                            step="0.01"
                            min="0.01"
                            placeholder="Ex: 10.50"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Valor (R$)</label>
                        <input
                            type="number"
                            name="valor"
                            class="form-control"
                            step="0.01"
                            min="0"
                            placeholder="Ex: 250.00"
                            required>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    ✓ Cadastrar Produto
                </button>

                <a href="lista_produtos.php" class="btn btn-outline-secondary ms-2">
                    Ver produtos cadastrados
                </a>

            </form>

        </div>

    </main>

</body>
</html>

