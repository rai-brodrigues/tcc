<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/painel/conexao.php';

if (
    empty($_POST['email_operador']) ||
    empty($_POST['senha_operador']) ||
    empty($_POST['perfil_escolhido'])
) {
    $_SESSION['nao_autenticado'] = true;
    header('Location: login/index.php');
    exit;
}

$email = trim($_POST['email_operador']);
$senha = $_POST['senha_operador'];
$perfilEscolhido = $_POST['perfil_escolhido'];

$perfisPermitidos = ['admin', 'instrutor', 'aluno'];

if (!in_array($perfilEscolhido, $perfisPermitidos, true)) {
    $_SESSION['nao_autenticado'] = true;
    header('Location: login/index.php');
    exit;
}

try {

    $sql = "
        SELECT
            op.id_operador,
            op.nome_operador,
            op.email_operador,
            op.senha_operador,
            op.perfil,
            op.id_academia,
            i.id AS instrutor_id,
            a.id AS aluno_id
        FROM operadores op
        LEFT JOIN instrutores i
            ON i.id_conta = op.id_operador
            AND i.id_academia = op.id_academia
        LEFT JOIN alunos a
            ON a.id_conta = op.id_operador
            AND a.id_academia = op.id_academia
        WHERE op.email_operador = ?
        LIMIT 1
    ";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($senha, $usuario['senha_operador'])) {

    if ($usuario['perfil'] !== $perfilEscolhido) {
        $_SESSION['erro_login'] = 'perfil';
        header('Location: login/index.php');
        exit;
    }

            if (
                $usuario['perfil'] === 'instrutor' &&
                empty($usuario['instrutor_id'])
            ) {
                $_SESSION['nao_autenticado'] = true;
                header('Location: login/index.php');
                exit;
            }

            if (
                $usuario['perfil'] === 'aluno' &&
                empty($usuario['aluno_id'])
            ) {
                $_SESSION['nao_autenticado'] = true;
                header('Location: login/index.php');
                exit;
            }

            session_regenerate_id(true);

            $_SESSION['usuario_id'] = (int) $usuario['id_operador'];
            $_SESSION['usuario'] = $usuario['email_operador'];
            $_SESSION['nome_usuario'] = $usuario['nome_operador'];
            $_SESSION['perfil'] = $usuario['perfil'];
            $_SESSION['id_academia'] = (int) $usuario['id_academia'];

            $_SESSION['instrutor_id'] = $usuario['instrutor_id'] !== null
                ? (int) $usuario['instrutor_id']
                : null;

            $_SESSION['aluno_id'] = $usuario['aluno_id'] !== null
                ? (int) $usuario['aluno_id']
                : null;

            header('Location: dashboard.php');
            exit;
        }
    }

    $_SESSION['nao_autenticado'] = true;
    header('Location: login/index.php');
    exit;

} catch (Throwable $e) {

    error_log('Erro na autenticação: ' . $e->getMessage());

    $_SESSION['nao_autenticado'] = true;
    header('Location: login/index.php');
    exit;
}