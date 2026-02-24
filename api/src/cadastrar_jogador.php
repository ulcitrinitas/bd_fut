<?php
// cadastrar_jogador.php - Cadastro de novo jogador
require_once "conexao.php";

// Buscar posições para o select
$posicoes = $pdo->query("SELECT id, posicao FROM posicoes ORDER BY posicao ASC")->fetchAll();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome    = trim($_POST['nome'] ?? '');
    $posicao = filter_input(INPUT_POST, 'posicao', FILTER_VALIDATE_INT);

    if (empty($nome)) {
        $erro = "O campo Nome é obrigatório.";
    } elseif (!$posicao) {
        $erro = "Selecione uma posição válida.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO jogador (nome, posicao) VALUES (:nome, :posicao)");
            $stmt->execute([':nome' => $nome, ':posicao' => $posicao]);
            header("Location: jogadores.php?msg=cadastrado");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Jogador</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&display=swap');

        :root {
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
            max-width: 520px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .card {
            background: var(--cinza-medio);
            border: 1px solid var(--cinza-borda);
            border-radius: 8px;
            padding: 36px;
        }

        .card h2 {
            font-family: 'Bebas Neue', cursive;
            font-size: 1.6rem;
            letter-spacing: 2px;
            margin-bottom: 28px;
            color: var(--amarelo);
        }

        label {
            display: block;
            font-size: 0.78rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--texto-suave);
            margin-bottom: 8px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px 16px;
            background: var(--preto);
            border: 1px solid var(--cinza-borda);
            border-radius: 4px;
            color: var(--branco);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            margin-bottom: 24px;
            transition: border 0.2s;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
        }

        input[type="text"]:focus,
        select:focus { border-color: var(--amarelo); }

        .select-wrapper {
            position: relative;
            margin-bottom: 24px;
        }

        .select-wrapper select { margin-bottom: 0; }

        .select-wrapper::after {
            content: '▾';
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--texto-suave);
            pointer-events: none;
            font-size: 0.9rem;
        }

        .acoes { display: flex; gap: 12px; }

        .btn {
            padding: 11px 26px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-amarelo { background: var(--amarelo); color: var(--preto); }
        .btn-amarelo:hover { background: #ffe033; }

        .btn-voltar {
            background: transparent;
            border: 1px solid var(--cinza-borda);
            color: var(--texto-suave);
        }
        .btn-voltar:hover { border-color: var(--branco); color: var(--branco); }

        .alerta-erro {
            background: rgba(198,40,40,0.1);
            border: 1px solid #c62828;
            color: #ef5350;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 0.88rem;
            margin-bottom: 20px;
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
    <div class="card">
        <h2>Novo Jogador</h2>

        <?php if ($erro): ?>
            <div class="alerta-erro">✘ <?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="cadastrar_jogador.php">
            <label for="nome">Nome do Jogador</label>
            <input type="text" id="nome" name="nome"
                   placeholder="Ex: Pelé, Ronaldo..."
                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                   required>

            <label for="posicao">Posição</label>
            <div class="select-wrapper">
                <select id="posicao" name="posicao" required>
                    <option value="">— Selecione uma posição —</option>
                    <?php foreach ($posicoes as $p): ?>
                        <option value="<?= $p['id'] ?>"
                            <?= (($_POST['posicao'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['posicao']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="acoes">
                <button type="submit" class="btn btn-amarelo">Salvar</button>
                <a href="jogadores.php" class="btn btn-voltar">Voltar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
