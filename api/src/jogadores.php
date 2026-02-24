<?php
// jogadores.php - Listagem de jogadores
require_once "conexao.php";

$stmt = $pdo->query("
    SELECT j.id, j.nome, p.posicao
    FROM jogador j
    INNER JOIN posicoes p ON j.posicao = p.id
    ORDER BY j.id ASC
");
$jogadores = $stmt->fetchAll();

$mensagem = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogadores - Futebol DB</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --verde: #00c853;
            --amarelo: #ffd600;
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
            border-bottom: 2px solid var(--amarelo);
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            font-family: 'Bebas Neue', cursive;
            font-size: 2.2rem;
            letter-spacing: 3px;
            color: var(--amarelo);
        }

        nav { display: flex; gap: 6px; }
        .nav-link {
            padding: 8px 18px;
            border-radius: 4px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-decoration: none;
            color: var(--texto-suave);
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        .nav-link:hover { color: var(--branco); border-color: var(--cinza-borda); }
        .nav-ativo { color: var(--amarelo) !important; border-color: var(--amarelo) !important; }

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

        .btn-amarelo { background: var(--amarelo); color: var(--preto); }
        .btn-amarelo:hover { background: #ffe033; }

        .btn-perigo {
            background: transparent;
            border: 1px solid #c62828;
            color: #ef5350;
            padding: 6px 14px;
            font-size: 0.78rem;
        }
        .btn-perigo:hover { background: #c62828; color: #fff; }

        .btn-editar {
            background: transparent;
            border: 1px solid #1565c0;
            color: #42a5f5;
            padding: 6px 14px;
            font-size: 0.78rem;
        }
        .btn-editar:hover { background: #1565c0; color: #fff; }

        .alerta {
            padding: 14px 20px;
            border-radius: 4px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .alerta-sucesso { background: rgba(0,200,83,0.1); border: 1px solid var(--verde); color: var(--verde); }
        .alerta-erro    { background: rgba(198,40,40,0.1); border: 1px solid #c62828; color: #ef5350; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--cinza-medio);
            border-radius: 8px;
            overflow: hidden;
        }

        thead { background: var(--cinza-escuro); }

        thead th {
            padding: 14px 20px;
            text-align: left;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--amarelo);
            font-weight: 600;
        }

        tbody tr {
            border-top: 1px solid var(--cinza-borda);
            transition: background 0.15s;
        }
        tbody tr:hover { background: rgba(255, 214, 0, 0.04); }

        tbody td { padding: 14px 20px; font-size: 0.9rem; }
        .td-id { color: var(--texto-suave); font-size: 0.8rem; }

        .badge-posicao {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            background: rgba(255, 214, 0, 0.12);
            border: 1px solid rgba(255, 214, 0, 0.3);
            color: var(--amarelo);
            letter-spacing: 0.5px;
        }

        .td-acoes { display: flex; gap: 8px; }

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
    <nav>
        <a href="index.php" class="nav-link">Posições</a>
        <a href="jogadores.php" class="nav-link nav-ativo">Jogadores</a>
    </nav>
</header>

<div class="container">
    <div class="topo">
        <h2>Jogadores Cadastrados</h2>
        <a href="cadastrar_jogador.php" class="btn btn-amarelo">+ Novo Jogador</a>
    </div>

    <?php if ($mensagem === 'cadastrado'): ?>
        <div class="alerta alerta-sucesso">✔ Jogador cadastrado com sucesso!</div>
    <?php elseif ($mensagem === 'editado'): ?>
        <div class="alerta alerta-sucesso">✔ Jogador atualizado com sucesso!</div>
    <?php elseif ($mensagem === 'excluido'): ?>
        <div class="alerta alerta-sucesso">✔ Jogador excluído com sucesso!</div>
    <?php elseif ($mensagem === 'erro'): ?>
        <div class="alerta alerta-erro">✘ Ocorreu um erro. Tente novamente.</div>
    <?php endif; ?>

    <?php if (empty($jogadores)): ?>
        <div class="vazio">Nenhum jogador cadastrado ainda.</div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Posição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jogadores as $j): ?>
            <tr>
                <td class="td-id"><?= $j['id'] ?></td>
                <td><?= htmlspecialchars($j['nome']) ?></td>
                <td><span class="badge-posicao"><?= htmlspecialchars($j['posicao']) ?></span></td>
                <td class="td-acoes">
                    <a href="editar_jogador.php?id=<?= $j['id'] ?>" class="btn btn-editar">Editar</a>
                    <a href="excluir_jogador.php?id=<?= $j['id'] ?>" class="btn btn-perigo"
                       onclick="return confirm('Deseja excluir este jogador?')">Excluir</a>
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
