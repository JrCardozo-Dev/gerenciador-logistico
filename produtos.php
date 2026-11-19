
<?php
require_once __DIR__ . "/includes/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $peso = $_POST["peso"];
    $valor = $_POST["valor"];

    if ($nome == "" || !is_numeric($peso) || !is_numeric($valor) || $peso <= 0 || $valor < 0) {

        $mensagem = "Preencha os campos corretamente!";

    } else {

        try {

            $sql = "INSERT INTO produtos (nome, descricao, peso, valor)
                    VALUES (:nome, :descricao, :peso, :valor)";

            $comando = $conexao->prepare($sql);

            $comando->execute([
                ":nome" => $nome,
                ":descricao" => $descricao,
                ":peso" => $peso,
                ":valor" => $valor
            ]);

            $mensagem = "Produto cadastrado com sucesso!";

        } catch (PDOException $erro) {

            $mensagem = "Erro ao cadastrar produto: " . $erro->getMessage();

        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Produtos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <h2>Cadastro de Produtos</h2>

        <p class="text-muted">
            Cadastre os produtos que serão transportados.
        </p>

        <div class="card shadow-sm p-4 mt-4">

            <h4>Novo Produto</h4>
            <?php if ($mensagem != ""): ?>
    <div class="alert alert-info">
        <?= htmlspecialchars($mensagem) ?>
    </div>
<?php endif; ?>

            
<form action="" method="POST">

    <div class="mb-3">
        <label class="form-label">Nome do produto</label>
        <input type="text" name="nome" class="form-control" placeholder="Digite o nome do produto" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="descricao" class="form-control" rows="3" placeholder="Descreva o produto"></textarea>
    </div>

    <div class="row">

        <div class="col-md-6 mb-3">
            <label class="form-label">Peso (kg)</label>
            <input type="number" name="peso" class="form-control" step="0.01" min="0.01" placeholder="Ex: 10.50" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Valor (R$)</label>
            <input type="number" name="valor" class="form-control" step="0.01" min="0" placeholder="Ex: 250.00" required>
        </div>

    </div>

    <button type="submit" class="btn btn-primary">
        Cadastrar Produto
    </button>

</form>


        </div>

    </div>

</body>
</html>
