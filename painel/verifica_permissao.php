<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function exigirPerfil($perfisPermitidos)
{
    if (empty($_SESSION['usuario_id'])) {
        http_response_code(401);
        exit('Você precisa fazer login.');
    }

    $perfil = $_SESSION['perfil'] ?? '';

    if (!in_array($perfil, $perfisPermitidos, true)) {
        http_response_code(403);
        exit('Você não tem permissão para realizar esta ação.');
    }
}

/**
 */
function obterUsuarioId()
{
    return (int) ($_SESSION['usuario_id'] ?? 0);
}

/**
 */
function obterAcademiaId()
{
    return (int) ($_SESSION['id_academia'] ?? 0);
}

/**
 */
function obterPerfil()
{
    return $_SESSION['perfil'] ?? '';
}

/**
 */
function obterInstrutorId()
{
    return (int) ($_SESSION['instrutor_id'] ?? 0);
}

/**
 */
function obterAlunoId()
{
    return (int) ($_SESSION['aluno_id'] ?? 0);
}
