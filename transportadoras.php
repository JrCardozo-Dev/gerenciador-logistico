
<?php
require_once __DIR__ . "/includes/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $cnpj = trim($_POST["cnpj"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    if (
    $nome == "" ||
    $cnpj == "" ||
    $telefone == "" ||
    $email == ""
) {

    $mensagem = "Todos os campos são obrigatórios!";

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $mensagem = "Informe um e-mail válido!";

} else {

        try {

            $sql = "INSERT INTO transportadoras
            (nome, cnpj, telefone, email)
            VALUES
            (:nome, :cnpj, :telefone, :email)";

            $comando = $conexao->prepare($sql);

            $comando->execute([
                ":nome" => $nome,
                ":cnpj" => $cnpj,
                ":telefone" => $telefone,
                ":email" => $email
            ]);

            $mensagem = "Transportadora cadastrada com sucesso!";

        } catch (PDOException $erro) {

            $mensagem = "Erro ao cadastrar transportadora.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Transportadoras</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2>Cadastro de Transportadoras</h2>

    <p class="text-muted">
        Cadastre as empresas responsáveis pelo transporte.
    </p>

    <div class="card shadow-sm p-4 mt-4">

        <h4>Nova Transportadora</h4>

        <?php if ($mensagem != ""): ?>
            <div class="alert alert-info">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nome da transportadora</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">CNPJ</label>
                <input type="text" name="cnpj" class="form-control"
                     maxlength="18" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Cadastrar Transportadora
            </button>

            <a href="index.php" class="btn btn-secondary">
                Voltar
            </a>

        </form>

    </div>

</div>

</body>
</html>
