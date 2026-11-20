


<?php
require_once __DIR__ . "/includes/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome_destinatario = trim($_POST["nome_destinatario"] ?? "");
    $cep = trim($_POST["cep"] ?? "");
    $rua = trim($_POST["rua"] ?? "");
    $numero = trim($_POST["numero"] ?? "");
    $bairro = trim($_POST["bairro"] ?? "");
    $cidade = trim($_POST["cidade"] ?? "");
    $estado = strtoupper(trim($_POST["estado"] ?? ""));
    $complemento = trim($_POST["complemento"] ?? "");

    if (
        $nome_destinatario === "" ||
        $cep === "" ||
        $rua === "" ||
        $cidade === "" ||
        $estado === ""
    ) {
        $mensagem = "Preencha todos os campos obrigatórios.";
    } elseif (strlen($estado) !== 2) {
        $mensagem = "Informe a sigla do estado com 2 letras.";
    } else {

        $sql = "INSERT INTO enderecos
                (nome_destinatario, cep, rua, numero, bairro, cidade, estado, complemento)
                VALUES
                (:nome_destinatario, :cep, :rua, :numero, :bairro, :cidade, :estado, :complemento)";

        $stmt = $conexao->prepare($sql);

        $stmt->execute([
            ":nome_destinatario" => $nome_destinatario,
            ":cep" => $cep,
            ":rua" => $rua,
            ":numero" => $numero,
            ":bairro" => $bairro,
            ":cidade" => $cidade,
            ":estado" => $estado,
            ":complemento" => $complemento
        ]);

        header("Location: lista_enderecos.php?sucesso=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar endereço - Gerenciador Logístico</title>

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

        .form-control {
            padding: 11px;
            border-radius: 8px;
        }

        .form-control:focus {
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
                <a class="nav-link active" href="enderecos.php">➕ Cadastrar endereço</a>
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

        <h2 class="page-title mb-2">📍 Cadastrar endereço</h2>

        <p class="text-muted mb-4">
            Cadastre os endereços de origem e destino utilizados nos fretes.
        </p>

        <?php if ($mensagem !== ""): ?>
            <div class="alert alert-danger" style="max-width: 850px;">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm form-card">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Nome do destinatário *</label>
                    <input type="text" name="nome_destinatario" class="form-control"
                           value="<?= htmlspecialchars($_POST["nome_destinatario"] ?? "") ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">CEP *</label>
                    <input type="text" name="cep" class="form-control"
                           value="<?= htmlspecialchars($_POST["cep"] ?? "") ?>"
                           placeholder="00000-000" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rua *</label>
                    <input type="text" name="rua" class="form-control"
                           value="<?= htmlspecialchars($_POST["rua"] ?? "") ?>"
                           required>
                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Número</label>
                        <input type="text" name="numero" class="form-control"
                               value="<?= htmlspecialchars($_POST["numero"] ?? "") ?>">
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Bairro</label>
                        <input type="text" name="bairro" class="form-control"
                               value="<?= htmlspecialchars($_POST["bairro"] ?? "") ?>">
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Cidade *</label>
                        <input type="text" name="cidade" class="form-control"
                               value="<?= htmlspecialchars($_POST["cidade"] ?? "") ?>"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado (UF) *</label>
                        <input type="text" name="estado" class="form-control"
                               maxlength="2"
                               value="<?= htmlspecialchars($_POST["estado"] ?? "") ?>"
                               placeholder="PR" required>
                    </div>

                </div>

                <div class="mb-4">
                    <label class="form-label">Complemento</label>
                    <input type="text" name="complemento" class="form-control"
                           value="<?= htmlspecialchars($_POST["complemento"] ?? "") ?>">
                </div>

                <button type="submit" class="btn btn-primary">
                    💾 Salvar endereço
                </button>

                <a href="lista_enderecos.php" class="btn btn-outline-secondary ms-2">
                    Ver endereços cadastrados
                </a>

            </form>

        </div>

    </main>

</body>
</html>

