<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../painel/conexao.php';

$mensagem = '';
$token = trim($_GET['token'] ?? $_POST['token'] ?? '');

$tokenValido = false;

if (preg_match('/^[a-f0-9]{64}$/', $token)) {

    $tokenHash = hash('sha256', $token);

    $stmt = $conexao->prepare(
        "SELECT id
         FROM recuperacao_senha
         WHERE token_hash = ?
         AND usado_em IS NULL
         AND expira_em > NOW()
         LIMIT 1"
    );

    $stmt->bind_param('s', $tokenHash);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $tokenValido = ($resultado->num_rows === 1);

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if (!$tokenValido) {

        $mensagem = 'Este link é inválido, expirou ou já foi utilizado.';

    } elseif (strlen($senha) < 8) {

        $mensagem = 'A senha deve ter pelo menos 8 caracteres.';

    } elseif ($senha !== $confirmarSenha) {

        $mensagem = 'As senhas não coincidem.';

    } else {

        try {

            $conexao->begin_transaction();

            $stmt = $conexao->prepare(
                "SELECT id, id_operador
                 FROM recuperacao_senha
                 WHERE token_hash = ?
                 AND usado_em IS NULL
                 AND expira_em > NOW()
                 LIMIT 1
                 FOR UPDATE"
            );

            $stmt->bind_param('s', $tokenHash);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows !== 1) {

                $conexao->rollback();

                $mensagem = 'Este link é inválido, expirou ou já foi utilizado.';

            } else {

                $recuperacao = $resultado->fetch_assoc();
                $stmt->close();

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $conexao->prepare(
                    "UPDATE operadores
                     SET senha_operador = ?
                     WHERE id_operador = ?"
                );

                $stmt->bind_param(
                    'si',
                    $senhaHash,
                    $recuperacao['id_operador']
                );

                $stmt->execute();

                if ($stmt->affected_rows !== 1) {
                    throw new Exception('Não foi possível atualizar a senha.');
                }

                $stmt->close();

                $stmt = $conexao->prepare(
                    "UPDATE recuperacao_senha
                     SET usado_em = NOW()
                     WHERE id = ? AND usado_em IS NULL"
                );

                $stmt->bind_param('i', $recuperacao['id']);
                $stmt->execute();

                if ($stmt->affected_rows !== 1) {
                    throw new Exception('Não foi possível finalizar a recuperação.');
                }

                $stmt->close();

                $conexao->commit();

                $tokenValido = false;

                $mensagem = 'Senha alterada com sucesso! Você já pode fazer login.';
            }

        } catch (Throwable $e) {

            $conexao->rollback();

            error_log('Erro ao redefinir senha: ' . $e->getMessage());

            $mensagem = 'Ocorreu um erro ao alterar sua senha. Tente novamente.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nova senha | FitControl</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f2f4f8;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        p {
            text-align: center;
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #198754;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #146c43;
        }

        .mensagem {
            padding: 12px;
            margin-top: 18px;
            background: #e8f5e9;
            color: #256029;
            border-radius: 6px;
            text-align: center;
            font-size: 14px;
        }

        .erro {
            background: #fdeaea;
            color: #a12626;
        }

        .voltar {
            display: block;
            margin-top: 22px;
            text-align: center;
            color: #198754;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Definir nova senha</h2>

    <?php if ($mensagem !== ''): ?>

        <div class="mensagem <?php echo ($tokenValido || strpos($mensagem, 'Senha alterada') === 0) ? '' : 'erro'; ?>">
            <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
        </div>

    <?php endif; ?>

    <?php if ($tokenValido): ?>

        <p>Digite sua nova senha e confirme para concluir a recuperação.</p>

        <form method="POST" action="redefinir_senha.php">

            <input
                type="hidden"
                name="token"
                value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>"
            >

            <label for="senha">Nova senha</label>

            <input
                type="password"
                id="senha"
                name="senha"
                minlength="8"
                required
                autocomplete="new-password"
                placeholder="Mínimo de 8 caracteres"
            >

            <label for="confirmar_senha">Confirmar nova senha</label>

            <input
                type="password"
                id="confirmar_senha"
                name="confirmar_senha"
                minlength="8"
                required
                autocomplete="new-password"
                placeholder="Digite novamente sua senha"
            >

            <button type="submit">
                Salvar nova senha
            </button>

        </form>

    <?php endif; ?>

    <?php if (!$tokenValido): ?>

        <a href="index.php" class="voltar">
            Voltar para o login
        </a>

    <?php endif; ?>

</div>

</body>
</html>
