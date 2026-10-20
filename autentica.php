<?php

session_start();

include('painel/conexao.php');

if (
    empty($_POST['email_operador']) ||
    empty($_POST['senha_operador'])
) {
    $_SESSION['nao_autenticado'] = true;

    header('Location: login/index.php');
    exit;
}

$email = trim($_POST['email_operador']);
$senha = $_POST['senha_operador'];

$stmt = $conexao->prepare(
    "SELECT id_operador, nome_operador, email_operador, senha_operador
     FROM operadores
     WHERE email_operador = ?
     LIMIT 1"
);

$stmt->bind_param('s', $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {

    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario['senha_operador'])) {

        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $usuario['id_operador'];
        $_SESSION['usuario'] = $usuario['email_operador'];
        $_SESSION['nome_usuario'] = $usuario['nome_operador'];

        header('Location: index.php');
        exit;
    }
}

$_SESSION['nao_autenticado'] = true;

header('Location: login/index.php');
exit;