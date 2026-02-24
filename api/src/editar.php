<?php
// editar.php - Editar posição existente
require_once "conexao.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php?msg=erro");
    exit;
}

// Buscar posição pelo ID
$stmt = $pdo->prepare("SELECT * FROM posicoes WHERE id = :id");
$stmt->execute([':id' => $id]);
$posicao = $stmt->fetch();

if (!$posicao) {
    header("Location: index.php?msg=erro");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaPosicao = trim($_POST['posicao'] ?? '');

    if (empty($novaPosicao)) {
        $erro = "O campo Posição é obrigatório.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE posicoes SET posicao = :posicao WHERE id = :id");
            $stmt->execute([':posicao' => $novaPosicao, ':id' => $id]);
            header("Location: index.php?msg=editado");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Posição</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --verde: #00c853;
            --preto: #0a0a0a;
            --cinza-escuro: #141414;
            --cinza-medio: #1e1e1e;
            --cinza-borda: #2a2a2a;
            --branco: #f5f5f5;
            --texto-suave: #888;
            --azul: #42a5f5;
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
            border-bottom: 2px solid var(--azul);
            padding: 20px 40px;
        }

        header h1 {
            font-family: 'Bebas Neue', cursive;
            font-size: 2.2rem;
            letter-spacing: 3px;
            color: var(--azul);
        }

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
            margin-bottom: 6px;
            color: var(--azul);
        }

        .subtitulo {
            font-size: 0.8rem;
            color: var(--texto-suave);
            margin-bottom: 28px;
        }

        label {
            display: block;
            font-size: 0.78rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--texto-suave);
            margin-bottom: 8px;
        }

        input[type="text"] {
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
        }

        input[type="text"]:focus {
            border-color: var(--azul);
        }

        .acoes {
            display: flex;
            gap: 12px;
        }

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

        .btn-azul {
            background: var(--azul);
            color: var(--preto);
        }

        .btn-azul:hover { background: #90caf9; }

        .btn-voltar {
            background: transparent;
            border: 1px solid var(--cinza-borda);
            color: var(--texto-suave);
        }

        .btn-voltar:hover {
            border-color: var(--branco);
            color: var(--branco);
        }

        .alerta-erro {
            background: rgba(198, 40, 40, 0.1);
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
</header>

<div class="container">
    <div class="card">
        <h2>Editar Posição</h2>
        <p class="subtitulo">ID: #<?= $posicao['id'] ?></p>

        <?php if ($erro): ?>
            <div class="alerta-erro">✘ <?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?= $id ?>">
            <label for="posicao">Nome da Posição</label>
            <input type="text" id="posicao" name="posicao"
                   value="<?= htmlspecialchars($_POST['posicao'] ?? $posicao['posicao']) ?>"
                   required>

            <div class="acoes">
                <button type="submit" class="btn btn-azul">Atualizar</button>
                <a href="index.php" class="btn btn-voltar">Voltar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
