

<?php

require_once __DIR__ . "/includes/conexao.php";

$mensagem = "";

if (isset($_GET["sucesso"])) {
    $mensagem = "Frete cadastrado com sucesso!";
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

    } elseif ($origem_id == $destino_id) {

        $mensagem = "A origem e o destino devem ser diferentes!";

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

        }
    }
}

?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Fretes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Cadastro de Fretes</h2>

    <p class="text-muted">
        Registre o transporte de produtos.
    </p>

    <div class="card shadow-sm p-4 mt-4">
        <?php if ($mensagem != ""): ?>
         <div class="alert alert-info">
           <?= htmlspecialchars($mensagem) ?>
         </div>
         <?php endif; ?>
        

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Produto</label>

                <select name="produto_id" class="form-select" required>
                    <option value="">Selecione um produto</option>

                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?= $produto['id'] ?>">
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
            <option value="<?= $endereco['id'] ?>">
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
            <option value="<?= $endereco['id'] ?>">
                <?= htmlspecialchars($endereco['rua'] . " - " . $endereco['cidade']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="alert alert-info mt-3">
    <strong>Resumo do trajeto:</strong>
    <p id="resumoTrajeto" class="mb-0">
        Selecione a origem e o destino.
    </p>
</div>

                    <option value="">Selecione o destino</option>

                    <?php foreach ($enderecos as $endereco): ?>
                        <option value="<?= $endereco['id'] ?>">
                            <?= htmlspecialchars($endereco['rua'] . " - " . $endereco['cidade']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Transportadora</label>

                <select name="transportadora_id" class="form-select" required>
                    <option value="">Selecione a transportadora</option>

                    <?php foreach ($transportadoras as $transportadora): ?>
                        <option value="<?= $transportadora['id'] ?>">
                            <?= htmlspecialchars($transportadora['nome']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Data do frete</label>
                <input type="date" name="data_frete" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Valor do frete (R$)</label>
                <input type="number" name="valor_frete"
                       class="form-control" min="0" step="0.01" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Registrar Frete
            </button>

            <a href="index.php" class="btn btn-secondary">
                Voltar
            </a>

        </form>

    </div>

</div>
<script src="js/fretes.js"></script>
</body>
</html>
