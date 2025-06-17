<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    echo "<script> alert('Acesso negado!'); window.location.href='login.php'; </script>";
    exit();
}


$produto = null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['buscar_produto'])) {
        $busca = trim($_POST['buscar_produto']);

      
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
        html, body {
            height: 100%;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #011638;
            color: #E8C1C5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            box-sizing: border-box;
            padding-bottom: 75px;
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

        footer {
            width: 100%;
            background: linear-gradient(90deg, #2E294E 0%, #9055A2 100%);
            color: #fff;
            text-align: center;
            padding: 18px 0 18px 0;
            font-weight: bold;
            font-size: 1.1em;
            letter-spacing: 2px;
            border-top-left-radius: 32px;
            border-top-right-radius: 32px;
            position: fixed;
            left: 0;
            bottom: 0;
            box-shadow: 0 -2px 16px rgba(1, 22, 56, 0.10);
            z-index: 999;
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
    <h2>Alterar Produto</h2>

    <form action="alterar_produto.php" method="POST">
        <label for="buscar_produto">Digite o ID ou Nome do produto:</label>
        <input type="text" id="buscar_produto" name="buscar_produto" required onkeyup="buscarSugestoes()">
        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($produto): ?>
 
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
    <footer> Pamella Rafaeli Neves </footer>
</body>
</html>