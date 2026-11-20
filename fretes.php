
<?php

require_once __DIR__ . "/includes/conexao.php";

$mensagem = "";
$tipo_mensagem = "info";

if (isset($_GET["sucesso"])) {
    $mensagem = "Frete cadastrado com sucesso!";
    $tipo_mensagem = "success";
}

$produtos = $conexao->query(
    "SELECT id, nome FROM produtos ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$enderecos = $conexao->query(
    "SELECT id, nome_destinatario, rua, cidade, estado
     FROM enderecos ORDER BY nome_destinatario"
)->fetchAll(PDO::FETCH_ASSOC);

$transportadoras = $conexao->query(
    "SELECT id, nome FROM transportadoras ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $produto_id = $_POST["produto_id"] ?? "";
    $origem_id = $_POST["endereco_origem_id"] ?? "";
    $destino_id = $_POST["endereco_destino_id"] ?? "";
    $transportadora_id = $_POST["transportadora_id"] ?? "";
    $data_frete = $_POST["data_frete"] ?? "";
    $valor_frete = $_POST["valor_frete"] ?? "";

    if (
        !ctype_digit((string)$produto_id) ||
        !ctype_digit((string)$origem_id) ||
        !ctype_digit((string)$destino_id) ||
        !ctype_digit((string)$transportadora_id) ||
        !is_numeric($valor_frete) ||
        $valor_frete < 0 ||
        $data_frete == ""
    ) {

        $mensagem = "Preencha todos os campos corretamente!";
        $tipo_mensagem = "danger";

    } elseif ($origem_id == $destino_id) {

        $mensagem = "A origem e o destino devem ser diferentes!";
        $tipo_mensagem = "danger";

    } else {

        try {

            $sql = "INSERT INTO fretes
            (produto_id, endereco_origem_id, endereco_destino_id,
            transportadora_id, data_frete, valor_frete)
            VALUES
            (:produto, :origem, :destino, :transportadora, :data, :valor)";

            $comando = $conexao->prepare($sql);

            $comando->execute([
                ":produto" => $produto_id,
                ":origem" => $origem_id,
                ":destino" => $destino_id,
                ":transportadora" => $transportadora_id,
                ":data" => $data_frete,
                ":valor" => $valor_frete
            ]);

            header("Location: fretes.php?sucesso=1");
            exit;

        } catch (PDOException $erro) {

            $mensagem = "Erro ao cadastrar o frete. Verifique os dados e tente novamente.";
            $tipo_mensagem = "danger";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerar frete - Gerenciador Logístico</title>

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

        .form-control,
        .form-select {
            padding: 11px;
            border-radius: 8px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8529a7;
            box-shadow: 0 0 0 0.2rem rgba(133, 41, 167, 0.15);
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

        .trajeto-card {
            background-color: #f3eaf7;
            border: 1px solid #e4d1ed;
            border-radius: 8px;
            padding: 15px;
            color: #41364a;
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
                <a class="nav-link" href="lista_enderecos.php">📍 Endereços cadastrados</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="transportadoras.php">➕ Cadastrar transportadora</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="lista_transportadoras.php">🚚 Transportadoras cadastradas</a>
            </li>

            <li class="nav-item">
                <a class="nav-link active" href="fretes.php">📝 Gerar frete</a>
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

        <h2 class="page-title mb-2">📝 Gerar frete</h2>

        <p class="text-muted mb-4">
            Registre o transporte de produtos, informando origem, destino e transportadora.
        </p>

        <?php if ($mensagem != ""): ?>
            <div class="alert alert-<?= $tipo_mensagem ?>" style="max-width: 850px;">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm form-card">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Produto</label>

                    <select name="produto_id" class="form-select" required>
                        <option value="">Selecione um produto</option>

                        <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto['id'] ?>"
                                <?= (($_POST["produto_id"] ?? "") == $produto["id"]) ? "selected" : "" ?>>
                                <?= htmlspecialchars($produto['nome']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Endereço de origem</label>

                    <select name="endereco_origem_id" id="origem" class="form-select" required>
                        <option value="">Selecione a origem</option>

                        <?php foreach ($enderecos as $endereco): ?>
                            <option value="<?= $endereco['id'] ?>"
                                <?= (($_POST["endereco_origem_id"] ?? "") == $endereco["id"]) ? "selected" : "" ?>>
                                <?= htmlspecialchars($endereco['rua'] . " - " . $endereco['cidade']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Endereço de destino</label>

                    <select name="endereco_destino_id" id="destino" class="form-select" required>
                        <option value="">Selecione o destino</option>

                        <?php foreach ($enderecos as $endereco): ?>
                            <option value="<?= $endereco['id'] ?>"
                                <?= (($_POST["endereco_destino_id"] ?? "") == $endereco["id"]) ? "selected" : "" ?>>
                                <?= htmlspecialchars($endereco['rua'] . " - " . $endereco['cidade']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="trajeto-card mb-4">
                    <strong>📍 Resumo do trajeto:</strong>
                    <p id="resumoTrajeto" class="mb-0 mt-2">
                        Selecione a origem e o destino.
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Transportadora</label>

                    <select name="transportadora_id" class="form-select" required>
                        <option value="">Selecione a transportadora</option>

                        <?php foreach ($transportadoras as $transportadora): ?>
                            <option value="<?= $transportadora['id'] ?>"
                                <?= (($_POST["transportadora_id"] ?? "") == $transportadora["id"]) ? "selected" : "" ?>>
                                <?= htmlspecialchars($transportadora['nome']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Data do frete</label>

                    <input
                        type="date"
                        name="data_frete"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST["data_frete"] ?? "") ?>"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Valor do frete (R$)</label>

                    <input
                        type="number"
                        name="valor_frete"
                        class="form-control"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars($_POST["valor_frete"] ?? "") ?>"
                        required>
                </div>

                <button type="submit" class="btn btn-primary">
                    🚚 Registrar frete
                </button>

                <a href="lista_fretes.php" class="btn btn-outline-secondary ms-2">
                    Ver fretes cadastrados
                </a>

            </form>

        </div>

    </main>

    <script src="js/fretes.js"></script>

</body>
</html>
