<?php

require_once __DIR__ . '/verifica_permissao.php';
require_once __DIR__ . '/conexao.php';

header('Content-Type: application/json; charset=utf-8');

$idOperador = obterUsuarioId();
$idAcademia = obterAcademiaId();
$perfil = obterPerfil();

function responder($codigo, $dados)
{
    http_response_code($codigo);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

try {

    if ($idOperador <= 0 || $idAcademia <= 0) {
        responder(401, ['erro' => 'Sessão inválida. Faça login novamente.']);
    }

    $metodo = $_SERVER['REQUEST_METHOD'];

   

    if ($metodo === 'GET') {

        if ($perfil === 'admin') {

           

            $sql = "
                SELECT
                    al.id,
                    al.nome,
                    al.idade,
                    al.email,
                    al.telefone,
                    al.plano,
                    al.id_instrutor,
                    i.nome AS nome_instrutor,
                    DATE_FORMAT(al.data_cadastro, '%d/%m/%Y') AS data
                FROM alunos al
                LEFT JOIN instrutores i
                    ON i.id = al.id_instrutor
                    AND i.id_academia = al.id_academia
                WHERE al.id_academia = ?
                ORDER BY al.id DESC
            ";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('i', $idAcademia);

        } elseif ($perfil === 'instrutor') {

           

            $idInstrutor = obterInstrutorId();

            if ($idInstrutor <= 0) {
                responder(403, ['erro' => 'Conta de instrutor sem vínculo válido.']);
            }

            $sql = "
                SELECT
                    al.id,
                    al.nome,
                    al.idade,
                    al.email,
                    al.telefone,
                    al.plano,
                    al.id_instrutor,
                    i.nome AS nome_instrutor,
                    DATE_FORMAT(al.data_cadastro, '%d/%m/%Y') AS data
                FROM alunos al
                INNER JOIN instrutores i
                    ON i.id = al.id_instrutor
                    AND i.id_academia = al.id_academia
                WHERE al.id_academia = ?
                    AND al.id_instrutor = ?
                ORDER BY al.id DESC
            ";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('ii', $idAcademia, $idInstrutor);

        } elseif ($perfil === 'aluno') {

            

            $idAluno = obterAlunoId();

            if ($idAluno <= 0) {
                responder(403, ['erro' => 'Conta de aluno sem vínculo válido.']);
            }

            $sql = "
                SELECT
                    al.id,
                    al.nome,
                    al.idade,
                    al.email,
                    al.telefone,
                    al.plano,
                    al.id_instrutor,
                    i.nome AS nome_instrutor,
                    DATE_FORMAT(al.data_cadastro, '%d/%m/%Y') AS data
                FROM alunos al
                LEFT JOIN instrutores i
                    ON i.id = al.id_instrutor
                    AND i.id_academia = al.id_academia
                WHERE al.id = ?
                    AND al.id_academia = ?
                LIMIT 1
            ";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('ii', $idAluno, $idAcademia);

        } else {

            responder(403, ['erro' => 'Perfil sem permissão para visualizar alunos.']);

        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        $alunos = [];

        while ($linha = $resultado->fetch_assoc()) {
            $alunos[] = $linha;
        }

        responder(200, $alunos);
    }

   

    if ($metodo === 'POST') {

        exigirPerfil(['admin']);

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responder(400, ['erro' => 'Dados inválidos.']);
        }

        $nome = trim($dados['nome'] ?? '');
        $idade = (int) ($dados['idade'] ?? 0);
        $email = strtolower(trim($dados['email'] ?? ''));
        $senha = $dados['senha'] ?? '';
        $telefone = trim($dados['telefone'] ?? '');
        $plano = trim($dados['plano'] ?? '');

        $idInstrutor = isset($dados['id_instrutor']) && $dados['id_instrutor'] !== ''
            ? (int) $dados['id_instrutor']
            : null;

        if (
            $nome === '' ||
            $idade <= 0 ||
            $email === '' ||
            $senha === '' ||
            $plano === ''
        ) {
            responder(400, ['erro' => 'Preencha todos os campos obrigatórios, incluindo e-mail e senha.']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            responder(400, ['erro' => 'Informe um e-mail válido.']);
        }

        if (strlen($senha) < 8) {
            responder(400, ['erro' => 'A senha deve ter pelo menos 8 caracteres.']);
        }

        $verificarEmail = $conexao->prepare("
            SELECT id_operador
            FROM operadores
            WHERE email_operador = ?
            LIMIT 1
        ");

        $verificarEmail->bind_param('s', $email);
        $verificarEmail->execute();

        if ($verificarEmail->get_result()->num_rows > 0) {
            responder(409, ['erro' => 'Este e-mail já está sendo utilizado por outra conta.']);
        }

        if ($idInstrutor !== null) {

            if ($idInstrutor <= 0) {
                responder(400, ['erro' => 'Instrutor inválido.']);
            }

            $verificar = $conexao->prepare("
                SELECT id
                FROM instrutores
                WHERE id = ? AND id_academia = ?
            ");

            $verificar->bind_param('ii', $idInstrutor, $idAcademia);
            $verificar->execute();

            if ($verificar->get_result()->num_rows === 0) {
                responder(400, ['erro' => 'Instrutor não encontrado nesta academia.']);
            }
        }

        $conexao->begin_transaction();

        try {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            if ($senhaHash === false) {
                throw new Exception('Não foi possível proteger a senha.');
            }

            $sqlConta = "
                INSERT INTO operadores
                (nome_operador, email_operador, senha_operador, id_academia, perfil)
                VALUES (?, ?, ?, ?, 'aluno')
            ";

            $stmtConta = $conexao->prepare($sqlConta);

            $stmtConta->bind_param(
                'sssi',
                $nome,
                $email,
                $senhaHash,
                $idAcademia
            );

            $stmtConta->execute();

            $idConta = $conexao->insert_id;

            $sqlAluno = "
                INSERT INTO alunos
                (
                    nome,
                    idade,
                    email,
                    telefone,
                    plano,
                    id_operador,
                    id_academia,
                    id_instrutor,
                    id_conta
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";

            $stmtAluno = $conexao->prepare($sqlAluno);

            $stmtAluno->bind_param(
                'sisssiiii',
                $nome,
                $idade,
                $email,
                $telefone,
                $plano,
                $idOperador,
                $idAcademia,
                $idInstrutor,
                $idConta
            );

            $stmtAluno->execute();

            $idAluno = $conexao->insert_id;

            $conexao->commit();

            responder(201, [
                'sucesso' => true,
                'mensagem' => 'Aluno e conta de acesso cadastrados com sucesso!',
                'id' => $idAluno
            ]);

        } catch (Throwable $e) {

            $conexao->rollback();
            throw $e;
        }
    }

    
   

    if ($metodo === 'PUT') {

        exigirPerfil(['admin']);

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responder(400, ['erro' => 'Dados inválidos.']);
        }

        $id = (int) ($dados['id'] ?? 0);
        $nome = trim($dados['nome'] ?? '');
        $idade = (int) ($dados['idade'] ?? 0);
        $email = strtolower(trim($dados['email'] ?? ''));
        $telefone = trim($dados['telefone'] ?? '');
        $plano = trim($dados['plano'] ?? '');

        $idInstrutor = isset($dados['id_instrutor']) && $dados['id_instrutor'] !== ''
            ? (int) $dados['id_instrutor']
            : null;

        if (
            $id <= 0 ||
            $nome === '' ||
            $idade <= 0 ||
            $email === '' ||
            $plano === ''
        ) {
            responder(400, ['erro' => 'Preencha todos os campos obrigatórios.']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            responder(400, ['erro' => 'Informe um e-mail válido.']);
        }

        $buscar = $conexao->prepare("
            SELECT id_conta
            FROM alunos
            WHERE id = ? AND id_academia = ?
            LIMIT 1
        ");

        $buscar->bind_param('ii', $id, $idAcademia);
        $buscar->execute();

        $alunoAtual = $buscar->get_result()->fetch_assoc();

        if (!$alunoAtual) {
            responder(404, ['erro' => 'Aluno não encontrado.']);
        }

        $idConta = (int) $alunoAtual['id_conta'];

        $verificarEmail = $conexao->prepare("
            SELECT id_operador
            FROM operadores
            WHERE email_operador = ?
            AND id_operador <> ?
            LIMIT 1
        ");

        $verificarEmail->bind_param('si', $email, $idConta);
        $verificarEmail->execute();

        if ($verificarEmail->get_result()->num_rows > 0) {
            responder(409, ['erro' => 'Este e-mail já está sendo utilizado por outra conta.']);
        }

        if ($idInstrutor !== null) {

            if ($idInstrutor <= 0) {
                responder(400, ['erro' => 'Instrutor inválido.']);
            }

            $verificarInstrutor = $conexao->prepare("
                SELECT id
                FROM instrutores
                WHERE id = ? AND id_academia = ?
                LIMIT 1
            ");

            $verificarInstrutor->bind_param('ii', $idInstrutor, $idAcademia);
            $verificarInstrutor->execute();

            if ($verificarInstrutor->get_result()->num_rows === 0) {
                responder(400, ['erro' => 'Instrutor não encontrado nesta academia.']);
            }
        }

        $conexao->begin_transaction();

        try {

            $atualizarAluno = $conexao->prepare("
                UPDATE alunos
                SET nome = ?,
                    idade = ?,
                    email = ?,
                    telefone = ?,
                    plano = ?,
                    id_instrutor = ?
                WHERE id = ?
                AND id_academia = ?
            ");

            $atualizarAluno->bind_param(
                'sisssiii',
                $nome,
                $idade,
                $email,
                $telefone,
                $plano,
                $idInstrutor,
                $id,
                $idAcademia
            );

            $atualizarAluno->execute();

            if ($idConta > 0) {

                $atualizarConta = $conexao->prepare("
                    UPDATE operadores
                    SET nome_operador = ?,
                        email_operador = ?
                    WHERE id_operador = ?
                    AND id_academia = ?
                    AND perfil = 'aluno'
                ");

                $atualizarConta->bind_param(
                    'ssii',
                    $nome,
                    $email,
                    $idConta,
                    $idAcademia
                );

                $atualizarConta->execute();
            }

            $conexao->commit();

            responder(200, [
                'sucesso' => true,
                'mensagem' => 'Dados do aluno atualizados com sucesso!'
            ]);

        } catch (Throwable $e) {

            $conexao->rollback();
            throw $e;
        }
    }


    if ($metodo === 'DELETE') {

        exigirPerfil(['admin']);

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            responder(400, ['erro' => 'ID inválido.']);
        }

        $conexao->begin_transaction();

        try {

            $buscar = $conexao->prepare("
                SELECT id_conta
                FROM alunos
                WHERE id = ? AND id_academia = ?
            ");

            $buscar->bind_param('ii', $id, $idAcademia);
            $buscar->execute();

            $aluno = $buscar->get_result()->fetch_assoc();

            if (!$aluno) {
                $conexao->rollback();
                responder(404, ['erro' => 'Aluno não encontrado.']);
            }

            $idConta = (int) $aluno['id_conta'];

            $excluir = $conexao->prepare("
                DELETE FROM alunos
                WHERE id = ? AND id_academia = ?
            ");

            $excluir->bind_param('ii', $id, $idAcademia);
            $excluir->execute();

            if ($idConta > 0) {

                $excluirConta = $conexao->prepare("
                    DELETE FROM operadores
                    WHERE id_operador = ?
                    AND id_academia = ?
                    AND perfil = 'aluno'
                ");

                $excluirConta->bind_param('ii', $idConta, $idAcademia);
                $excluirConta->execute();
            }

            $conexao->commit();

            responder(200, [
                'sucesso' => true,
                'mensagem' => 'Aluno e conta de acesso excluídos com sucesso!'
            ]);

        } catch (Throwable $e) {

            $conexao->rollback();
            throw $e;
        }
    }

    responder(405, ['erro' => 'Método não permitido.']);

} catch (mysqli_sql_exception $e) {

    error_log('Erro SQL na API de alunos: ' . $e->getMessage());

    if ((int) $e->getCode() === 1062) {
        responder(409, ['erro' => 'Este e-mail já está cadastrado.']);
    }

    responder(500, [
        'erro' => 'Erro no banco de dados ao processar o cadastro.'
    ]);

} catch (Throwable $e) {

    error_log('Erro na API de alunos: ' . $e->getMessage());

    responder(500, [
        'erro' => 'Ocorreu um erro ao processar a solicitação.'
    ]);
}
