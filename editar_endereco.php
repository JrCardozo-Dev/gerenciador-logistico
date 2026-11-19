
<?php

require_once __DIR__ . "/includes/conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("Endereço inválido!");
}

$consulta = $conexao->prepare(
    "SELECT * FROM enderecos WHERE id = :id"
);
$consulta->execute([":id" => $id]);

$endereco = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$endereco) {
    die("Endereço não encontrado!");
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome_destinatario"] ?? "");
    $cep = trim($_POST["cep"] ?? "");
    $rua = trim($_POST["rua"] ?? "");
    $numero = trim($_POST["numero"] ?? "");
    $bairro = trim($_POST["bairro"] ?? "");
    $cidade = trim($_POST["cidade"] ?? "");
    $estado = strtoupper(trim($_POST["estado"] ?? ""));
    $complemento = trim($_POST["complemento"] ?? "");

    if (
        $nome == "" || $cep == "" || $rua == "" ||
        $cidade == "" || !preg_match("/^[A-Z]{2}$/", $estado)
    ) {

        $mensagem = "Preencha corretamente os campos obrigatórios!";

    } else {

        $sql = "UPDATE enderecos SET
                nome_destinatario = :nome,
                cep = :cep,
                rua = :rua,
                numero = :numero,
                bairro = :bairro,
                cidade = :cidade,
                estado = :estado,
                complemento = :complemento
                WHERE id = :id";

        $comando = $conexao->prepare($sql);

        $comando->execute([
            ":nome" => $nome,
            ":cep" => $cep,
            ":rua" => $rua,
            ":numero" => $numero,
            ":bairro" => $bairro,
            ":cidade" => $cidade,
            ":estado" => $estado,
            ":complemento" => $complemento,
            ":id" => $id
        ]);

        header("Location: lista_enderecos.php");
        exit;
    }

    // Manter os valores digitados caso exista erro
    $endereco = array_merge($endereco, [
        "nome_destinatario" => $nome,
        "cep" => $cep,
        "rua" => $rua,
        "numero" => $numero,
        "bairro" => $bairro,
        "cidade" => $cidade,
        "estado" => $estado,
        "complemento" => $complemento
    ]);
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar endereço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Editar endereço</h2>

    <div class="card shadow-sm p-4 mt-4">

        <?php if ($mensagem != ""): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nome do destinatário</label>
                <input type="text" name="nome_destinatario"
                       class="form-control"
                       value="<?= htmlspecialchars($endereco["nome_destinatario"]) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">CEP</label>
                <input type="text" name="cep" class="form-control"
                       value="<?= htmlspecialchars($endereco["cep"]) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rua</label>
                <input type="text" name="rua" class="form-control"
                       value="<?= htmlspecialchars($endereco["rua"]) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Número</label>
                <input type="text" name="numero" class="form-control"
                       value="<?= htmlspecialchars($endereco["numero"] ?? "") ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Bairro</label>
                <input type="text" name="bairro" class="form-control"
                       value="<?= htmlspecialchars($endereco["bairro"] ?? "") ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Cidade</label>
                <input type="text" name="cidade" class="form-control"
                       value="<?= htmlspecialchars($endereco["cidade"]) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado (UF)</label>
                <input type="text" name="estado" class="form-control"
                       maxlength="2"
                       value="<?= htmlspecialchars($endereco["estado"]) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Complemento</label>
                <input type="text" name="complemento" class="form-control"
                       value="<?= htmlspecialchars($endereco["complemento"] ?? "") ?>">
            </div>

            <button type="submit" class="btn btn-primary">
                Salvar alterações
            </button>

            <a href="lista_enderecos.php" class="btn btn-secondary">
                Cancelar
            </a>

        </form>
    </div>
</div>

</body>
</html>
