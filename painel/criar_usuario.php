<?php

include('painel/conexao.php');

$nome = 'Administrador';
$email = 'admin@3k26.com';
$senha = '123456';

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conexao->prepare(
    "INSERT INTO operadores 
    (nome_operador, email_operador, senha_operador)
    VALUES (?, ?, ?)"
);

$stmt->bind_param('sss', $nome, $email, $senha_hash);

$stmt->execute();

echo 'Usuário criado com sucesso!';
```
