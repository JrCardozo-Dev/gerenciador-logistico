
<?php
require_once __DIR__ . "/includes/conexao.php";

$consulta = $conexao->query(
    "SELECT * FROM transportadoras ORDER BY id DESC"
);
$transportadoras = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Transportadoras cadastradas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2>Transportadoras cadastradas</h2>

    <a href="transportadoras.php" class="btn btn-primary mb-3">
        Nova transportadora
    </a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Ação</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($transportadoras as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= htmlspecialchars($t['nome']) ?></td>
                    <td><?= htmlspecialchars($t['cnpj']) ?></td>
                    <td><?= htmlspecialchars($t['telefone']) ?></td>
                    <td><?= htmlspecialchars($t['email']) ?></td>
                    <td>
                        <a href="editar_transportadora.php?id=<?= $t['id'] ?>"
                           class="btn btn-warning btn-sm">
                            Editar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-secondary">Voltar ao início</a>
</div>

</body>
</html>
