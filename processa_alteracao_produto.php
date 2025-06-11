<?php
session_start();
require 'conexao.php';

// Permite apenas Admin (1) ou Almoxarife (3) - ajuste conforme sua política de permissão
if (!isset($_SESSION['perfil']) || ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

// Verifica se os dados foram enviados por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produto = $_POST['id_produto'] ?? '';
    $nome_prod = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $valor_unit = $_POST['preco'] ?? '';
    
    // Você pode adicionar a quantidade se quiser permitir alteração dela também:
    // $qtde = $_POST['qtde'] ?? '';

    // Validação simples
    if (empty($id_produto) || empty($nome_prod) || empty($descricao) || empty($valor_unit)) {
        echo "<script>alert('Todos os campos são obrigatórios!'); window.history.back();</script>";
        exit;
    }

    // Atualiza o produto
    $sql = "UPDATE produto 
            SET nome_prod = :nome_prod, descricao = :descricao, valor_unit = :valor_unit 
            WHERE id_produto = :id_produto";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_prod', $nome_prod);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':valor_unit', $valor_unit);
    $stmt->bindParam(':id_produto', $id_produto);

    if ($stmt->execute()) {
        echo "<script>alert('Produto alterado com sucesso!'); window.location.href='alterar_produto.php';</script>";
    } else {
        echo "<script>alert('Erro ao alterar produto!'); window.history.back();</script>";
    }
} else {
    // Se acessar diretamente sem POST
    header('Location: alterar_produto.php');
    exit;
}
?>