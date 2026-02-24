<?php
// index.php - Listagem de posições
require_once "conexao.php";

// Buscar todas as posições
$stmt = $pdo->query("SELECT * FROM posicoes ORDER BY id ASC");
$posicoes = $stmt->fetchAll();

// Mensagem de feedback
$mensagem = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Posições - Futebol</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --verde: #00c853;
            --verde-escuro: #007c32;
            --preto: #0a0a0a;
            --cinza-escuro: #141414;
            --cinza-medio: #1e1e1e;
            --cinza-borda: #2a2a2a;
            --branco: #f5f5f5;
            --texto-suave: #888;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--preto);
            color: var(--branco);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        header {
            background: var(--cinza-escuro);
            border-bottom: 2px solid var(--verde);
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            font-family: 'Bebas Neue', cursive;
            font-size: 2.2rem;
            letter-spacing: 3px;
            color: var(--verde);
        }

        header span {
            font-size: 0.85rem;
            color: var(--texto-suave);
            letter-spacing: 1px;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .topo h2 {
            font-family: 'Bebas Neue', cursive;
            font-size: 1.5rem;
            letter-spacing: 2px;
            color: var(--branco);
        }

        .btn {
            display: inline-block;
            padding: 10px 22px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: 1px;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-verde {
            background: var(--verde);
            color: var(--preto);
        }

        .btn-verde:hover {
            background: #00e676;
        }

        .btn-perigo {
            background: transparent;
            border: 1px solid #c62828;
            color: #ef5350;
            padding: 6px 14px;
            font-size: 0.78rem;
        }

        .btn-perigo:hover {
            background: #c62828;
            color: #fff;
        }

        .btn-editar {
            background: transparent;
            border: 1px solid #1565c0;
            color: #42a5f5;
            padding: 6px 14px;
            font-size: 0.78rem;
        }

        .btn-editar:hover {
            background: #1565c0;
            color: #fff;
        }

        .alerta {
            padding: 14px 20px;
            border-radius: 4px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .alerta-sucesso {
            background: rgba(0, 200, 83, 0.1);
            border: 1px solid var(--verde);
            color: var(--verde);
        }

        .alerta-erro {
            background: rgba(198, 40, 40, 0.1);
            border: 1px solid #c62828;
            color: #ef5350;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--cinza-medio);
            border-radius: 8px;
            overflow: hidden;
        }

        thead {
            background: var(--cinza-escuro);
        }

        thead th {
            padding: 14px 20px;
            text-align: left;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--verde);
            font-weight: 600;
        }

        tbody tr {
            border-top: 1px solid var(--cinza-borda);
            transition: background 0.15s;
        }

        tbody tr:hover {
            background: rgba(0, 200, 83, 0.04);
        }

        tbody td {
            padding: 14px 20px;
            font-size: 0.9rem;
        }

        .td-id {
            color: var(--texto-suave);
            font-size: 0.8rem;
        }

        .td-data {
            color: var(--texto-suave);
            font-size: 0.8rem;
        }

        .td-acoes {
            display: flex;
            gap: 8px;
        }

        .vazio {
            text-align: center;
            padding: 60px;
            color: var(--texto-suave);
        }

        footer {
            text-align: center;
            padding: 30px;
            color: var(--texto-suave);
            font-size: 0.78rem;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<header>
    <h1>⚽ Futebol DB</h1>
    <span>SISTEMA DE GESTÃO DE POSIÇÕES</span>
</header>

<div class="container">
    <div class="topo">
        <h2>Posições Cadastradas</h2>
        <a href="cadastrar.php" class="btn btn-verde">+ Nova Posição</a>
    </div>

    <?php if ($mensagem === 'cadastrado'): ?>
        <div class="alerta alerta-sucesso">✔ Posição cadastrada com sucesso!</div>
    <?php elseif ($mensagem === 'editado'): ?>
        <div class="alerta alerta-sucesso">✔ Posição atualizada com sucesso!</div>
    <?php elseif ($mensagem === 'excluido'): ?>
        <div class="alerta alerta-sucesso">✔ Posição excluída com sucesso!</div>
    <?php elseif ($mensagem === 'erro'): ?>
        <div class="alerta alerta-erro">✘ Ocorreu um erro. Tente novamente.</div>
    <?php endif; ?>

    <?php if (empty($posicoes)): ?>
        <div class="vazio">Nenhuma posição cadastrada ainda.</div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Posição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posicoes as $p): ?>
            <tr>
                <td class="td-id"><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['posicao']) ?></td>
                <td class="td-acoes">
                    <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-editar">Editar</a>
                    <a href="excluir.php?id=<?= $p['id'] ?>" class="btn btn-perigo"
                       onclick="return confirm('Deseja excluir esta posição?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<footer>BD_FUTEBOL &mdash; Sistema de Gestão &copy; <?= date('Y') ?></footer>

</body>
</html>
