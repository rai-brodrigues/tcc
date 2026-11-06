
<?php

require_once __DIR__ . '/painel/conexao.php';

$erro = '';
$sucesso = '';

$nome = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome_operador'] ?? '');
    $email = trim($_POST['email_operador'] ?? '');
    $senha = $_POST['senha_operador'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    if (
        $nome === '' ||
        $email === '' ||
        $senha === '' ||
        $confirmar === ''
    ) {
        $erro = 'Preencha todos os campos.';

    } elseif (strlen($nome) > 100) {
        $erro = 'O nome deve ter no máximo 100 caracteres.';

    } elseif (
        strlen($email) > 150 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $erro = 'Digite um e-mail válido, como exemplo@email.com.';

    } elseif (strlen($senha) < 8) {
        $erro = 'A senha deve ter pelo menos 8 caracteres.';

    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não coincidem.';

    } else {

        try {
            $stmt = $conexao->prepare(
                "SELECT id_operador
                 FROM operadores
                 WHERE email_operador = ?
                 LIMIT 1"
            );

            $stmt->bind_param('s', $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {

                $erro = 'Este e-mail já está cadastrado.';

            } else {

                $senhaHash = password_hash(
                    $senha,
                    PASSWORD_DEFAULT
                );

                $stmtInsert = $conexao->prepare(
                    "INSERT INTO operadores
                        (nome_operador, email_operador, senha_operador)
                     VALUES (?, ?, ?)"
                );

                $stmtInsert->bind_param(
                    'sss',
                    $nome,
                    $email,
                    $senhaHash
                );

                $stmtInsert->execute();
                $stmtInsert->close();

                $sucesso = 'Cadastro realizado com sucesso!';
                $nome = '';
                $email = '';
            }

            $stmt->close();

        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage());
            $erro = 'Não foi possível concluir o cadastro. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Criar conta | Academia</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #080808;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px 12px;
            font-family: Arial, sans-serif;
        }

        .cadastro-card {
            background-color: #151515;
            border: 1px solid #303030;
            border-radius: 16px;
            color: #ffffff;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 10px 35px rgba(255, 0, 0, 0.10);
        }

        .card-body {
            padding: 35px;
        }

        .titulo {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .titulo span {
            color: #ff2525;
        }

        .subtitulo {
            color: #aaaaaa;
            font-size: 14px;
        }

        .form-label {
            color: #eeeeee;
            font-weight: 600;
        }

        .form-control {
            background-color: #222222;
            color: #ffffff;
            border: 1px solid #444444;
            border-radius: 8px;
            padding: 12px;
        }

        .form-control::placeholder {
            color: #999999;
            opacity: 1;
        }

        .form-control:focus {
            background-color: #222222;
            color: #ffffff;
            border-color: #ff2525;
            box-shadow: 0 0 0 0.2rem rgba(255, 37, 37, 0.20);
        }

        .form-text {
            color: #aaaaaa;
            font-size: 12px;
        }

        .btn-cadastrar {
            background-color: #e50914;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 13px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.2s, transform 0.2s;
        }

        .btn-cadastrar:hover {
            background-color: #b80710;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .link-login {
            color: #ff3636;
            text-decoration: none;
            font-weight: bold;
        }

        .link-login:hover {
            color: #ff7777;
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
        }

        @media (max-width: 480px) {
            .card-body {
                padding: 25px 20px;
            }
        }
    </style>
</head>

<body>

<div class="cadastro-card">
    <div class="card-body">

        <h2 class="titulo text-center mb-2">
            Criar <span>conta</span>
        </h2>

        <p class="subtitulo text-center mb-4">
            Preencha os dados abaixo para se cadastrar.
        </p>

        <?php if ($erro !== ''): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso !== ''): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?>
                <div class="mt-2">
                    <a href="login/index.php" class="link-dark fw-bold">
                        Fazer login agora
                    </a>
                </div>
            </div>
        <?php else: ?>

            <form method="POST" action="cadastro.php">

                <div class="mb-3">
                    <label for="nome_operador" class="form-label">
                        Nome completo
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nome_operador"
                        name="nome_operador"
                        placeholder="Digite seu nome completo"
                        maxlength="100"
                        autocomplete="name"
                        value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                        required>

                    <div class="form-text">
                        Informe seu nome completo.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email_operador" class="form-label">
                        E-mail
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email_operador"
                        name="email_operador"
                        placeholder="exemplo@email.com"
                        maxlength="150"
                        autocomplete="email"
                        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                        required>

                    <div class="form-text">
                        Digite um e-mail válido, contendo @ e o domínio.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="senha_operador" class="form-label">
                        Senha
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="senha_operador"
                        name="senha_operador"
                        placeholder="Crie uma senha"
                        minlength="8"
                        autocomplete="new-password"
                        aria-describedby="dica-senha"
                        required>

                    <div class="form-text" id="dica-senha">
                        A senha deve ter no mínimo 8 caracteres.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="confirmar_senha" class="form-label">
                        Confirmar senha
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="confirmar_senha"
                        name="confirmar_senha"
                        placeholder="Digite a senha novamente"
                        minlength="8"
                        autocomplete="new-password"
                        required>

                    <div class="form-text">
                        Digite novamente a mesma senha.
                    </div>
                </div>

                <button type="submit" class="btn btn-cadastrar w-100">
                    Cadastrar
                </button>

            </form>

        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="login/index.php" class="link-login">
                Voltar para o login
            </a>
        </div>

    </div>
</div>

</body>
</html>
