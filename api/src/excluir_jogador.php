<?php
// excluir_jogador.php - Exclui um jogador pelo ID
require_once "conexao.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) { header("Location: jogadores.php?msg=erro"); exit; }

try {
    $stmt = $pdo->prepare("SELECT id FROM jogador WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if (!$stmt->fetch()) { header("Location: jogadores.php?msg=erro"); exit; }

    $stmt = $pdo->prepare("DELETE FROM jogador WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: jogadores.php?msg=excluido");
    exit;

} catch (PDOException $e) {
    header("Location: jogadores.php?msg=erro");
    exit;
}
