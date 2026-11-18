<?php
require_once __DIR__ . '/painel/conexao.php';

$academiaId = 1;

$perfil = $_GET['perfil'] ?? '';

$perfisPermitidos = ['instrutor', 'aluno'];

$erro = '';
$sucesso = '';

$nome = '';
$email = '';
$especialidade = '';
$idade = '';
$telefone = '';
$plano = '';
$idInstrutor = 0;

$instrutores = [];

if (in_array($perfil, $perfisPermitidos, true)) {
    $stmtLista = $conexao->prepare(
        "SELECT id, nome, especialidade
         FROM instrutores
         WHERE id_academia = ?
         ORDER BY nome"
    );

    $stmtLista->bind_param('i', $academiaId);
    $stmtLista->execute();

    $resultadoLista = $stmtLista->get_result();

    while ($linha = $resultadoLista->fetch_assoc()) {
        $instrutores[] = $linha;
    }

    $stmtLista->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    in_array($perfil, $perfisPermitidos, true)) {

    $nome = trim($_POST['nome_operador'] ?? '');
    $email = trim($_POST['email_operador'] ?? '');
    $senha = $_POST['senha_operador'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    $especialidade = trim($_POST['especialidade'] ?? '');
    $idade = trim($_POST['idade'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $plano = trim($_POST['plano'] ?? '');
    $idInstrutor = (int) ($_POST['id_instrutor'] ?? 0);

    if (
        $nome === '' ||
        $email === '' ||
        $senha === '' ||
        $confirmar === ''
    ) {
        $erro = 'Preencha todos os campos obrigatórios.';

    } elseif (strlen($nome) > 150) {
        $erro = 'O nome deve ter no máximo 150 caracteres.';

    } elseif (
        strlen($email) > 150 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $erro = 'Digite um e-mail válido.';

    } elseif (strlen($senha) < 8) {
        $erro = 'A senha deve ter pelo menos 8 caracteres.';

    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não coincidem.';

    } elseif (
        $perfil === 'instrutor' &&
        $especialidade === ''
    ) {
        $erro = 'Informe sua especialidade.';

    } elseif (
        $perfil === 'instrutor' &&
        strlen($especialidade) > 150
    ) {
        $erro = 'A especialidade deve ter no máximo 150 caracteres.';

    } elseif (
        $perfil === 'aluno' &&
        (
            $idade === '' ||
            !ctype_digit($idade) ||
            (int) $idade < 1 ||
            (int) $idade > 120
        )
    ) {
        $erro = 'Informe uma idade válida.';

    } elseif (
        $perfil === 'aluno' &&
        ($plano === '' || strlen($plano) > 30)
    ) {
        $erro = 'Informe um plano válido, com até 30 caracteres.';

    } elseif (
        $perfil === 'aluno' &&
        strlen($telefone) > 30
    ) {
        $erro = 'O telefone deve ter no máximo 30 caracteres.';

    } elseif (
        $perfil === 'aluno' &&
        $idInstrutor <= 0
    ) {
        $erro = 'Selecione seu instrutor.';

    } else {

        try {
            $stmtVerifica = $conexao->prepare(
                "SELECT id_operador
                 FROM operadores
                 WHERE email_operador = ?
                 LIMIT 1"
            );

            $stmtVerifica->bind_param('s', $email);
            $stmtVerifica->execute();

            $resultadoVerifica = $stmtVerifica->get_result();

            if ($resultadoVerifica->num_rows > 0) {
                $erro = 'Este e-mail já está cadastrado.';
            }

            $stmtVerifica->close();

            if ($erro === '' && $perfil === 'aluno') {
                $stmtInstrutor = $conexao->prepare(
                    "SELECT id
                     FROM instrutores
                     WHERE id = ?
                       AND id_academia = ?
                     LIMIT 1"
                );

                $stmtInstrutor->bind_param(
                    'ii',
                    $idInstrutor,
                    $academiaId
                );

                $stmtInstrutor->execute();

                $resultadoInstrutor = $stmtInstrutor->get_result();

                if ($resultadoInstrutor->num_rows !== 1) {
                    $erro = 'O instrutor selecionado não é válido.';
                }

                $stmtInstrutor->close();
            }

            if ($erro === '') {

                $conexao->begin_transaction();

                $senhaHash = password_hash(
                    $senha,
                    PASSWORD_DEFAULT
                );

                $stmtConta = $conexao->prepare(
                    "INSERT INTO operadores
                        (
                            nome_operador,
                            email_operador,
                            senha_operador,
                            id_academia,
                            perfil
                        )
                     VALUES (?, ?, ?, ?, ?)"
                );

                $stmtConta->bind_param(
                    'sssis',
                    $nome,
                    $email,
                    $senhaHash,
                    $academiaId,
                    $perfil
                );

                $stmtConta->execute();

                $idConta = (int) $conexao->insert_id;

                $stmtConta->close();

                if ($perfil === 'instrutor') {

                    $stmtPerfil = $conexao->prepare(
                        "INSERT INTO instrutores
                            (
                                nome,
                                especialidade,
                                id_operador,
                                id_conta,
                                id_academia
                            )
                         VALUES (?, ?, ?, ?, ?)"
                    );

                    $stmtPerfil->bind_param(
                        'ssiii',
                        $nome,
                        $especialidade,
                        $idConta,
                        $idConta,
                        $academiaId
                    );

                } else {

                    $idadeInt = (int) $idade;

                    $stmtPerfil = $conexao->prepare(
                        "INSERT INTO alunos
                            (
                                nome,
                                idade,
                                email,
                                telefone,
                                plano,
                                id_operador,
                                id_conta,
                                id_instrutor,
                                id_academia
                            )
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                    );

                    $stmtPerfil->bind_param(
                        'sisssiiii',
                        $nome,
                        $idadeInt,
                        $email,
                        $telefone,
                        $plano,
                        $idConta,
                        $idConta,
                        $idInstrutor,
                        $academiaId
                    );
                }

                $stmtPerfil->execute();
                $stmtPerfil->close();

                $conexao->commit();

                $sucesso = 'Cadastro realizado com sucesso!';

                $nome = '';
                $email = '';
                $especialidade = '';
                $idade = '';
                $telefone = '';
                $plano = '';
                $idInstrutor = 0;
            }

        } catch (Throwable $e) {

            try {
                $conexao->rollback();
            } catch (Throwable $erroRollback) {
                error_log($erroRollback->getMessage());
            }

            error_log('Erro no cadastro FitControl: ' . $e->getMessage());

            if (
                $e instanceof mysqli_sql_exception &&
                (int) $e->getCode() === 1062
            ) {
                $erro = 'Este e-mail já está cadastrado.';
            } else {
                $erro = 'Não foi possível concluir o cadastro. Verifique os dados e tente novamente.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Criar conta | FitControl</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #080808;
            color: #fff;
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
            width: 100%;
            max-width: 480px;
            box-shadow: 0 10px 35px rgba(255, 0, 0, .10);
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

        .subtitulo,
        .form-text {
            color: #aaa;
        }

        .subtitulo {
            font-size: 14px;
        }

        .form-label {
            color: #eee;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            background-color: #222;
            color: #fff;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #222;
            color: #fff;
            border-color: #ff2525;
            box-shadow: 0 0 0 .2rem rgba(255, 37, 37, .20);
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-select option {
            background: #222;
            color: #fff;
        }

        .btn-cadastrar {
            background-color: #e50914;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .btn-cadastrar:hover {
            background-color: #b80710;
            color: #fff;
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

        <?php if (!in_array($perfil, $perfisPermitidos, true)): ?>

            <p class="subtitulo text-center mb-4">
                Selecione primeiro o tipo de acesso na tela de login.
            </p>

            <div class="alert alert-warning">
                O cadastro público de administrador não está disponível.
                Administradores devem ser criados pelo responsável da academia.
            </div>

            <div class="text-center mt-4">
                <a href="login/index.php" class="link-login">
                    Voltar para o login
                </a>
            </div>

        <?php else: ?>

            <p class="subtitulo text-center mb-4">
                Cadastro de
                <strong>
                    <?= $perfil === 'instrutor' ? 'Instrutor' : 'Aluno' ?>
                </strong>
            </p>

            <?php if ($erro !== ''): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($sucesso !== ''): ?>

                <div class="alert alert-success">
                    <?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?>
                </div>

                <div class="text-center mt-3">
                    <a href="login/index.php" class="link-login">
                        Ir para o login
                    </a>
                </div>

            <?php else: ?>

                <form
                    method="POST"
                    action="cadastro.php?perfil=<?= urlencode($perfil) ?>">

                    <div class="mb-3">
                        <label class="form-label" for="nome_operador">
                            Nome completo
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nome_operador"
                            name="nome_operador"
                            maxlength="150"
                            value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email_operador">
                            E-mail
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email_operador"
                            name="email_operador"
                            maxlength="150"
                            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                            required>
                    </div>

                    <?php if ($perfil === 'instrutor'): ?>

                        <div class="mb-3">
                            <label class="form-label" for="especialidade">
                                Especialidade
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="especialidade"
                                name="especialidade"
                                maxlength="150"
                                placeholder="Ex.: Musculação"
                                value="<?= htmlspecialchars($especialidade, ENT_QUOTES, 'UTF-8') ?>"
                                required>
                        </div>

                    <?php else: ?>

                        <div class="mb-3">
                            <label class="form-label" for="idade">
                                Idade
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="idade"
                                name="idade"
                                min="1"
                                max="120"
                                value="<?= htmlspecialchars($idade, ENT_QUOTES, 'UTF-8') ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="telefone">
                                Telefone
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="telefone"
                                name="telefone"
                                maxlength="30"
                                value="<?= htmlspecialchars($telefone, ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="plano">
                                Plano
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="plano"
                                name="plano"
                                maxlength="30"
                                placeholder="Ex.: Mensal"
                                value="<?= htmlspecialchars($plano, ENT_QUOTES, 'UTF-8') ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="id_instrutor">
                                Instrutor responsável
                            </label>

                            <select
                                class="form-select"
                                id="id_instrutor"
                                name="id_instrutor"
                                required>

                                <option value="">Selecione seu instrutor</option>

                                <?php foreach ($instrutores as $instrutor): ?>
                                    <option
                                        value="<?= (int) $instrutor['id'] ?>"
                                        <?= $idInstrutor === (int) $instrutor['id'] ? 'selected' : '' ?>>

                                        <?= htmlspecialchars($instrutor['nome'], ENT_QUOTES, 'UTF-8') ?>
                                        -
                                        <?= htmlspecialchars($instrutor['especialidade'] ?? '', ENT_QUOTES, 'UTF-8') ?>

                                    </option>
                                <?php endforeach; ?>

                            </select>

                            <?php if (count($instrutores) === 0): ?>
                                <div class="form-text">
                                    Não existem instrutores cadastrados nesta academia.
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label" for="senha_operador">
                            Senha
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="senha_operador"
                            name="senha_operador"
                            minlength="8"
                            autocomplete="new-password"
                            required>

                        <div class="form-text">
                            Mínimo de 8 caracteres.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="confirmar_senha">
                            Confirmar senha
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="confirmar_senha"
                            name="confirmar_senha"
                            minlength="8"
                            autocomplete="new-password"
                            required>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-cadastrar w-100"
                        <?= ($perfil === 'aluno' && count($instrutores) === 0) ? 'disabled' : '' ?>>

                        Cadastrar

                    </button>

                </form>

            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="login/index.php" class="link-login">
                    Voltar para o login
                </a>
            </div>

        <?php endif; ?>

    </div>
</div>

</body>
</html>
