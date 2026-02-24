<?php
// excluir.php - Exclui uma posição pelo ID
require_once "conexao.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php?msg=erro");
    exit;
}

try {
    // Verificar se o registro existe antes de excluir
    $stmt = $pdo->prepare("SELECT id FROM posicoes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $existe = $stmt->fetch();

    if (!$existe) {
        header("Location: index.php?msg=erro");
        exit;
    }

    // Executar exclusão
    $stmt = $pdo->prepare("DELETE FROM posicoes WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: index.php?msg=excluido");
    exit;

} catch (PDOException $e) {
    header("Location: index.php?msg=erro");
    exit;
}
