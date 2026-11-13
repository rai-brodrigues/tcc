
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../painel/conexao.php';
require_once __DIR__ . '/../painel/email_config.php';

function finalizarRecuperacao()
{
    $_SESSION['mensagem_recuperacao'] =
        'Se o e-mail estiver cadastrado, você receberá um link de recuperação.';

    header('Location: esqueci_senha.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: esqueci_senha.php');
    exit;
}

$email = trim($_POST['email_operador'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    finalizarRecuperacao();
}

try {
    $stmt = $conexao->prepare(
        "SELECT id_operador, nome_operador, email_operador
         FROM operadores
         WHERE email_operador = ?
         LIMIT 1"
    );

    $stmt->bind_param('s', $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows !== 1) {
        finalizarRecuperacao();
    }

    $operador = $resultado->fetch_assoc();
    $stmt->close();

    $token = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);

    $expiracao = date('Y-m-d H:i:s', time() + 3600);

    $stmt = $conexao->prepare(
        "DELETE FROM recuperacao_senha
         WHERE id_operador = ? AND usado_em IS NULL"
    );

    $stmt->bind_param('i', $operador['id_operador']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexao->prepare(
        "INSERT INTO recuperacao_senha
         (id_operador, token_hash, expira_em)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        'iss',
        $operador['id_operador'],
        $tokenHash,
        $expiracao
    );

    $stmt->execute();
    $stmt->close();

    $link = 'https://fitcontrol.ceedo.com.br/login/redefinir_senha.php?token=' . urlencode($token);

    $mail = criarMailer();

    $mail->addAddress(
        $operador['email_operador'],
        $operador['nome_operador']
    );

    $mail->isHTML(true);
    $mail->Subject = 'Recuperação de senha - FitControl';

    $mail->Body = '
        <h2>FitControl</h2>
        <p>Olá, ' .
        htmlspecialchars($operador['nome_operador'], ENT_QUOTES, 'UTF-8') .
        '!</p>
        <p>Recebemos uma solicitação para redefinir sua senha.</p>
        <p>
            <a href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">
                Clique aqui para criar uma nova senha
            </a>
        </p>
        <p>Este link expira em 1 hora e só pode ser utilizado uma vez.</p>
        <p>Se você não solicitou a recuperação, ignore este e-mail.</p>
    ';

    $mail->AltBody =
        "Para redefinir sua senha, acesse: $link\n"
        . "Este link expira em 1 hora.";

    $mail->send();

    finalizarRecuperacao();

} catch (Throwable $e) {

    error_log('Erro na recuperação de senha: ' . $e->getMessage());

    finalizarRecuperacao();
}
