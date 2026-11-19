
<?php
require_once __DIR__ . "/includes/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $destinatario = trim($_POST["nome_destinatario"]);
    $cep = trim($_POST["cep"]);
    $rua = trim($_POST["rua"]);
    $numero = trim($_POST["numero"]);
    $bairro = trim($_POST["bairro"]);
    $cidade = trim($_POST["cidade"]);
    $estado = strtoupper(trim($_POST["estado"]));
    $complemento = trim($_POST["complemento"]);

    if (
        $destinatario == "" ||
        $cep == "" ||
        $rua == "" ||
        $cidade == "" ||
        strlen($estado) != 2
    ) {
        $mensagem = "Preencha corretamente os campos obrigatórios!";
    } else {

        try {

            $sql = "INSERT INTO enderecos
            (nome_destinatario, cep, rua, numero, bairro, cidade, estado, complemento)
            VALUES
            (:destinatario, :cep, :rua, :numero, :bairro, :cidade, :estado, :complemento)";

            $comando = $conexao->prepare($sql);

            $comando->execute([
                ":destinatario" => $destinatario,
                ":cep" => $cep,
                ":rua" => $rua,
                ":numero" => $numero,
                ":bairro" => $bairro,
                ":cidade" => $cidade,
                ":estado" => $estado,
                ":complemento" => $complemento
            ]);

            $mensagem = "Endereço cadastrado com sucesso!";

        } catch (PDOException $erro) {
            $mensagem = "Erro ao cadastrar endereço.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Endereços</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Cadastro de Endereços</h2>
    <p class="text-muted">Cadastre os locais de origem e destino.</p>

    <div class="card shadow-sm p-4 mt-4">

        <h4>Novo Endereço</h4>

        <?php if ($mensagem != ""): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nome do destinatário</label>
                <input type="text" name="nome_destinatario" class="form-control" required>
            </div>

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">CEP</label>
                    <input type="text" name="cep" class="form-control" required>
                </div>

                <div class="col-md-8 mb-3">
                    <label class="form-label">Rua</label>
                    <input type="text" name="rua" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Número</label>
                    <input type="text" name="numero" class="form-control">
                </div>

                <div class="col-md-8 mb-3">
                    <label class="form-label">Bairro</label>
                    <input type="text" name="bairro" class="form-control">
                </div>

                <div class="col-md-8 mb-3">
                    <label class="form-label">Cidade</label>
                    <input type="text" name="cidade" class="form-control" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado (UF)</label>
                    <input type="text" name="estado" class="form-control" maxlength="2" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Complemento</label>
                    <input type="text" name="complemento" class="form-control">
                </div>

            </div>

            <button type="submit" class="btn btn-primary">
                Cadastrar Endereço
            </button>

            <a href="index.php" class="btn btn-secondary">
                Voltar
            </a>

        </form>

    </div>

</div>

</body>
</html>
