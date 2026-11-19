
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

    <title>Editar Produto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Editar Produto</h2>

    <div class="card shadow-sm p-4 mt-4">

        <?php if ($mensagem != ""): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nome</label>

                <input type="text" name="nome"
                       class="form-control"
                       value="<?= htmlspecialchars($produto["nome"]) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>

                <textarea name="descricao" class="form-control"><?= htmlspecialchars($produto["descricao"] ?? "") ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Peso (kg)</label>

                <input type="number" name="peso"
                       class="form-control"
                       step="0.01" min="0.01"
                       value="<?= htmlspecialchars($produto["peso"]) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Valor (R$)</label>

                <input type="number" name="valor"
                       class="form-control"
                       step="0.01" min="0"
                       value="<?= htmlspecialchars($produto["valor"]) ?>"
                       required>
            </div>

            <button type="submit" class="btn btn-primary">
                Salvar alterações
            </button>

            <a href="lista_produtos.php" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>

</div>

</body>
</html>
