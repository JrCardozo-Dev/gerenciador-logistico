
<?php

require_once __DIR__ . "/includes/conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("Produto inválido!");
}

// Buscar produto
$consulta = $conexao->prepare(
    "SELECT * FROM produtos WHERE id = :id"
);

$consulta->execute([":id" => $id]);

$produto = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado!");
}

$mensagem = "";

// Atualizar produto
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $peso = $_POST["peso"] ?? "";
    $valor = $_POST["valor"] ?? "";

    if (
        $nome == "" ||
        !is_numeric($peso) ||
        !is_numeric($valor) ||
        $peso <= 0 ||
        $valor < 0
    ) {

        $mensagem = "Preencha os campos corretamente!";

    } else {

        $sql = "UPDATE produtos SET
                nome = :nome,
                descricao = :descricao,
                peso = :peso,
                valor = :valor
                WHERE id = :id";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            ":nome" => $nome,
            ":descricao" => $descricao,
            ":peso" => $peso,
            ":valor" => $valor,
            ":id" => $id
        ]);

        header("Location: lista_produtos.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Produto - Gerenciador Logístico</title>

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
            <a class="nav-link active" href="lista_produtos.php">📦 Produtos cadastrados</a>
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
            <a class="nav-link" href="relatorios.php">📊 Relatórios</a>
        </li>

    </ul>

</nav>

<main class="main-content">

    <div class="mb-4">

        <h2 class="page-title">✏️ Editar produto</h2>

        <p class="text-muted">
            Atualize as informações do produto cadastrado.
        </p>

    </div>

    <div class="card shadow-sm form-card">

        <?php if ($mensagem != ""): ?>

            <div class="alert alert-warning">
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">

                <label class="form-label">Nome</label>

                <input
                    type="text"
                    name="nome"
                    class="form-control"
                    value="<?= htmlspecialchars($produto["nome"]) ?>"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">Descrição</label>

                <textarea
                    name="descricao"
                    class="form-control"
                    rows="3"
                ><?= htmlspecialchars($produto["descricao"] ?? "") ?></textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">Peso (kg)</label>

                <input
                    type="number"
                    name="peso"
                    class="form-control"
                    step="0.01"
                    min="0.01"
                    value="<?= htmlspecialchars($produto["peso"]) ?>"
                    required
                >

            </div>

            <div class="mb-4">

                <label class="form-label">Valor (R$)</label>

                <input
                    type="number"
                    name="valor"
                    class="form-control"
                    step="0.01"
                    min="0"
                    value="<?= htmlspecialchars($produto["valor"]) ?>"
                    required
                >

            </div>

            <button type="submit" class="btn btn-primary">
                💾 Salvar alterações
            </button>

            <a href="lista_produtos.php" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>

</main>

</body>
</html>
