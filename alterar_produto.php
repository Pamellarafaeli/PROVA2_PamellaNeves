<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if (!isset($_SESSION['perfil']) || ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

// Inicializa variável
$produto = null;

// Se o formulário for enviado, busca o produto pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['buscar_produto'])) {
        $busca = trim($_POST['buscar_produto']);

        // Verifica se a busca é um número (ID) ou um nome
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM produto WHERE id_produto = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o produto não for encontrado, exibe um alerta
        if (!$produto) {
            echo "<script>alert('Produto não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Produto</title>
    <script src="scripts.js"></script>
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

form {
    background: #2E294E;
    max-width: 400px;
    margin: 40px auto 25px auto;
    padding: 28px 30px 18px 30px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(1, 22, 56, 0.14);
}

label {
    display: block;
    margin-bottom: 7px;
    color: #E8C1C5;
    font-weight: bold;
}

input[type="text"],
input[type="number"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 18px;
    border: 1px solid #9055A2;
    border-radius: 8px;
    background: #E8C1C5;
    color: #2E294E;
    font-size: 1em;
    box-sizing: border-box;
    transition: border-color 0.2s;
}

input[type="text"]:focus,
input[type="number"]:focus {
    border-color: #D499B9;
    outline: none;
}

button[type="submit"],
button[type="reset"] {
    background: #9055A2;
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 1em;
    margin-right: 8px;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}

button[type="submit"]:hover,
button[type="reset"]:hover {
    background: #D499B9;
    color: #2E294E;
}

a {
    display: block;
    text-align: center;
    margin-top: 24px;
    color: #E8C1C5;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.2s;
}
a:hover {
    color: #9055A2;
}

#sugestoes {
    margin-bottom: 10px;
}
        </style>
</head>
<body>
    <h2>Alterar Produto</h2>

    <!-- Formulário para buscar produto pelo ID ou Nome -->
    <form action="alterar_produto.php" method="POST">
        <label for="buscar_produto">Digite o ID ou Nome do produto:</label>
        <input type="text" id="buscar_produto" name="buscar_produto" required onkeyup="buscarSugestoes()">
        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($produto): ?>
        <!-- Formulário para alterar produto -->
        <form action="processa_alteracao_produto.php" method="POST">
            <input type="hidden" name="id_produto" value="<?= htmlspecialchars($produto['id_produto']) ?>">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome_prod']) ?>" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>" required>

            <label for="preco">Preço unitário:</label>
            <input type="number" id="preco" name="preco" value="<?= htmlspecialchars($produto['valor_unit']) ?>" step="0.01" required>

            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body>
</html>