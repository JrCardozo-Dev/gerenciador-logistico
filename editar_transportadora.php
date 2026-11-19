
<?php
require_once __DIR__ . "/includes/conexao.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Transportadora inválida.");
}

$consulta = $conexao->prepare(
    "SELECT * FROM transportadoras WHERE id = ?"
);
$consulta->execute([$id]);
$transportadora = $consulta->fetch(PDO::FETCH_ASSOC);

if (!$transportadora) {
    die("Transportadora não encontrada.");
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $cnpj = trim($_POST["cnpj"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    if (
        $nome === "" ||
        $cnpj === "" ||
        $telefone === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        $atualizar = $conexao->prepare(
            "UPDATE transportadoras
             SET nome = ?, cnpj = ?, telefone = ?, email = ?
             WHERE id = ?"
        );

        $atualizar->execute([
            $nome, $cnpj, $telefone, $email, $id
        ]);

        header("Location: lista_transportadoras.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar transportadora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Editar transportadora</h2>

    <?php if ($erro): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control mb-3"
               value="<?= htmlspecialchars($transportadora['nome']) ?>"
               required>

        <label class="form-label">CNPJ</label>
        <input type="text" name="cnpj" class="form-control mb-3"
               value="<?= htmlspecialchars($transportadora['cnpj']) ?>"
               required>

        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control mb-3"
               value="<?= htmlspecialchars($transportadora['telefone']) ?>"
               required>

        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control mb-3"
               value="<?= htmlspecialchars($transportadora['email']) ?>"
               required>

        <button type="submit" class="btn btn-success">
            Salvar alterações
        </button>

        <a href="lista_transportadoras.php" class="btn btn-secondary">
            Cancelar
        </a>

    </form>
</div>

</body>
</html>
