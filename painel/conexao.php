<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = '3k26_games';

try {

    $conexao = new mysqli($host, $usuario, $senha, $banco);

    $conexao->set_charset('utf8mb4');

} catch (mysqli_sql_exception $e) {

    die('Erro ao conectar ao banco de dados.');
}
