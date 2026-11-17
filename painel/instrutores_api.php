
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
                    id,
                    nome,
                    especialidade,
                    DATE_FORMAT(data_cadastro, '%d/%m/%Y') AS data
                FROM instrutores
                WHERE id_academia = ?
                ORDER BY id DESC
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
                    id,
                    nome,
                    especialidade,
                    DATE_FORMAT(data_cadastro, '%d/%m/%Y') AS data
                FROM instrutores
                WHERE id = ? AND id_academia = ?
            ";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('ii', $idInstrutor, $idAcademia);

        } elseif ($perfil === 'aluno') {

            $idAluno = obterAlunoId();

            if ($idAluno <= 0) {
                responder(403, ['erro' => 'Conta de aluno sem vínculo válido.']);
            }

            $sql = "
                SELECT
                    i.id,
                    i.nome,
                    i.especialidade,
                    DATE_FORMAT(i.data_cadastro, '%d/%m/%Y') AS data
                FROM alunos a
                INNER JOIN instrutores i
                    ON i.id = a.id_instrutor
                    AND i.id_academia = a.id_academia
                WHERE a.id = ?
                    AND a.id_academia = ?
            ";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('ii', $idAluno, $idAcademia);

        } else {
            responder(403, ['erro' => 'Você não tem permissão para visualizar instrutores.']);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        $instrutores = [];

        while ($linha = $resultado->fetch_assoc()) {
            $instrutores[] = $linha;
        }

        responder(200, $instrutores);
    }

    if ($metodo === 'POST') {

        if ($perfil !== 'admin') {
            responder(403, ['erro' => 'Somente o administrador pode cadastrar instrutores.']);
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responder(400, ['erro' => 'Dados inválidos.']);
        }

        $nome = trim($dados['nome'] ?? '');
        $especialidade = trim($dados['especialidade'] ?? '');

        if ($nome === '') {
            responder(400, ['erro' => 'Informe o nome do instrutor.']);
        }

        $sql = "
            INSERT INTO instrutores
            (nome, especialidade, id_operador, id_academia)
            VALUES (?, NULLIF(?, ''), ?, ?)
        ";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            'ssii',
            $nome,
            $especialidade,
            $idOperador,
            $idAcademia
        );

        $stmt->execute();

        responder(201, [
            'sucesso' => true,
            'mensagem' => 'Instrutor cadastrado com sucesso!',
            'id' => $conexao->insert_id
        ]);
    }

    if ($metodo === 'DELETE') {

        if ($perfil !== 'admin') {
            responder(403, ['erro' => 'Somente o administrador pode excluir instrutores.']);
        }

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            responder(400, ['erro' => 'ID do instrutor inválido.']);
        }

        $stmt = $conexao->prepare("
            DELETE FROM instrutores
            WHERE id = ? AND id_academia = ?
        ");

        $stmt->bind_param('ii', $id, $idAcademia);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            responder(404, ['erro' => 'Instrutor não encontrado.']);
        }

        responder(200, [
            'sucesso' => true,
            'mensagem' => 'Instrutor excluído com sucesso!'
        ]);
    }

    responder(405, ['erro' => 'Método não permitido.']);

} catch (Throwable $erro) {

    error_log('Erro na API de instrutores: ' . $erro->getMessage());

    responder(500, [
        'erro' => 'Erro interno ao processar os instrutores.'
    ]);
}
