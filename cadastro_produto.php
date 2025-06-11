<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['perfil']) || ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_prod = $_POST['nome_prod'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $qtde = $_POST['qtde'] ?? '';
    $valor_unit = $_POST['valor_unit'] ?? '';
    $id_fornecedor = $_POST['id_fornecedor'] ?? '';

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($nome_prod) || empty($descricao) || empty($qtde) || empty($valor_unit) || empty($id_fornecedor)) {
        echo "<script>alert('Todos os campos são obrigatórios.');</script>";
    } else {
        try {
            $pdo->beginTransaction();

            // Insere o produto
            $sql = "INSERT INTO produto (nome_prod, descricao, qtde, valor_unit) VALUES (:nome_prod, :descricao, :qtde, :valor_unit)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_prod', $nome_prod);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':qtde', $qtde);
            $stmt->bindParam(':valor_unit', $valor_unit);

            if ($stmt->execute()) {
                // Pega o id do produto inserido
                $id_produto = $pdo->lastInsertId();

                // Relaciona fornecedor ao produto
                $sql_fp = "INSERT INTO fornecedor_produto (id_fornecedor, id_produto) VALUES (:id_fornecedor, :id_produto)";
                $stmt_fp = $pdo->prepare($sql_fp);
                $stmt_fp->bindParam(':id_fornecedor', $id_fornecedor);
                $stmt_fp->bindParam(':id_produto', $id_produto);

                if ($stmt_fp->execute()) {
                    $pdo->commit();
                    echo "<script>alert('Produto cadastrado com sucesso!');</script>";
                } else {
                    $pdo->rollBack();
                    echo "<script>alert('Erro ao vincular fornecedor ao produto.');</script>";
                }
            } else {
                $pdo->rollBack();
                echo "<script>alert('Erro ao cadastrar produto!');</script>";
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar produto</title>
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
    margin: 40px auto;
    padding: 30px 30px 20px 30px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(1, 22, 56, 0.15);
}

label {
    display: block;
    margin-bottom: 6px;
    color: #E8C1C5;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
select {
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
input[type="number"]:focus,
select:focus {
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
    margin-right: 10px;
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
        </style>
</head>
<body>
    <h2>Cadastrar produto</h2>
    <form action="cadastro_produto.php" method="POST">

        <label for="nome_prod">Nome do produto:</label><br>
        <input type="text" id="nome_prod" name="nome_prod" required><br><br>

        <label for="descricao">Descrição:</label><br>
        <input type="text" id="descricao" name="descricao" required><br><br>

        <label for="qtde">Quantidade:</label><br>
        <input type="number" id="qtde" name="qtde" required><br><br>

        <label for="valor_unit">Valor unitário:</label><br>
        <input type="number" id="valor_unit" name="valor_unit" step="0.01" required><br><br>

        <label for="id_fornecedor">Fornecedor:</label><br>
        <select id="id_fornecedor" name="id_fornecedor" required>
            <option value="">Selecione um fornecedor</option>
            <?php
            // Consulta para obter os fornecedores
            $stmt = $pdo->query("SELECT id_fornecedor, nome_fornecedor FROM fornecedor");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($row['id_fornecedor']) . "'>" . htmlspecialchars($row['nome_fornecedor']) . "</option>";
            }
            ?>
        </select><br><br>
        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
    </form>
    <a href="principal.php">Voltar</a>
</body>
</html>