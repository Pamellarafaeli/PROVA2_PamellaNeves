<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    echo "<script> alert('Acesso negado!'); window.location.href='login.php'; </script>";
    exit();
}

$sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = $_GET['id'];

    
    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Produto excluído com sucesso!'); window.location.href='excluir_produto.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir produto!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Produto</title>
    <style>
        body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #011638;
    color: #E8C1C5;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color: #D499B9;
    margin-top: 30px;
}

table {
    width: 90%;
    margin: 30px auto 0 auto;
    border-collapse: collapse;
    background: #2E294E;
    color: #E8C1C5;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(1, 22, 56, 0.13);
}

th, td {
    padding: 12px 10px;
    text-align: center;
    border: 3px solid #011638;
}

th {
    background: #9055A2;
    color: #fff;
}

tr:nth-child(even) {
    background: #D499B9;
    color: #2E294E;
}

tr:nth-child(odd) {
    background:rgb(148, 108, 130);
}

a {
    color: #E8C1C5;
    text-decoration: none;
    font-weight: bold;
    margin: 0 5px;
    transition: color 0.2s;
}

a:hover {
    color: #9055A2;
}

a {
    display: block;
    text-align: center;
    margin: 30px auto 0 auto;
    color: #E8C1C5;
    text-decoration: none;
    font-weight: bold;
    font-size: 1.1em;
    width: fit-content;
    transition: color 0.2s;
}

a:hover {
    color: #9055A2;
}

p {
    text-align: center;
    color: #E8C1C5;
    margin-top: 30px;
}

footer {
            width: 100%;
            height: 50px;
            background: linear-gradient(90deg, #2E294E 0%, #9055A2 100%);
            color: #fff;
            text-align: center;
            padding: 18px 0 18px 0;
            font-weight: bold;
            font-size: 1.1em;
            letter-spacing: 2px;
            border-top-left-radius: 32px;
            border-top-right-radius: 32px;
            left: 0;
            bottom: 0;
            box-shadow: 0 -2px 16px rgba(1, 22, 56, 0.10);
        }

        @media (max-width: 600px) {
            form {
                max-width: 90vw;
                padding: 16px 8px 12px 8px;
            }
            footer {
                font-size: 1em;
                border-top-left-radius: 16px;
                border-top-right-radius: 16px;
                padding: 12px 0;
            }
        }
        </style>
</head>
<body>
    <h2>Excluir Produto</h2>

    <?php if (!empty($produtos)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço unitário</th>
                <th>Quantidade</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                    <td><?= htmlspecialchars($produto['nome_prod']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= htmlspecialchars($produto['valor_unit']) ?></td>
                    <td><?= htmlspecialchars($produto['qtde']) ?></td>
                    <td>
                        <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum produto encontrado.</p>
    <?php endif; ?>

    <a href="principal.php">Voltar</a><br><BR>
    <footer> Pamella Rafaeli Neves </footer>
</body>
</html>