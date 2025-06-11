<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e tem perfil de adm (1) ou secretaria (2)
if (!isset($_SESSION['perfil']) || ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

$produtos = []; // Inicializa o array para armazenar resultados

// Se o formulário foi enviado e a busca não está vazia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    // Verifica se a busca é um número (ID) ou texto (nome)
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM produto WHERE id_produto = :busca ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-be">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar produto</title>
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
    margin: 30px auto 20px auto;
    padding: 22px 28px 16px 28px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(1, 22, 56, 0.13);
}

label {
    display: block;
    margin-bottom: 8px;
    color: #E8C1C5;
    font-weight: bold;
}

input[type="text"] {
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

input[type="text"]:focus {
    border-color: #D499B9;
    outline: none;
}

button[type="submit"] {
    background: #9055A2;
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 1em;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.2s, color 0.2s;
    margin-bottom: 8px;
}

button[type="submit"]:hover {
    background: #D499B9;
    color: #2E294E;
}

table {
    width: 90%;
    margin: 0 auto 30px auto;
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
    background: #2E294E;
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

p {
    text-align: center;
    color: #E8C1C5;
    margin-top: 30px;
}
        </style>
</head>
<body>
    <h2> Lista de produtos </h2> 
    <!-- FORMULARIO PARA BUSCAR USUARIOS -->
    <form action = "buscar_produto.php" method = "POST">
        <label for = "busca"> Digite o ID ou NOME (opcional) </label>
        <input type = "text" id= "busca" name = "busca" required>
        <button type = "submit"> Pesquisar </button>
</form>

    <?php if (!empty($produtos)) : ?>
        <table border = "1">
            <tr>
                <th>ID</th>
                <th>Produto</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Valor unitário</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($produtos as $produto) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($produto['id_produto']); ?></td>
                    <td><?php echo htmlspecialchars($produto['nome_prod']); ?></td>
                    <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                    <td><?php echo htmlspecialchars($produto['qtde']); ?></td>
                    <td><?php echo htmlspecialchars($produto['valor_unit']); ?></td>
                    
                    <td>
                    <a href="alterar_produto.php?id=<?= htmlspecialchars($produto['id_produto']); ?>">Alterar</a>
                    <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto']); ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                    </td>
                </tr>
    <?php endforeach; ?>
    </table>
    <?php else : ?>
        <p>Nenhum produto encontrado.</p>
    <?php endif; ?>
    <br>
    <a href="principal.php">Voltar</a>
    
                



</body>
</html>