
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
                    t.id,
                    t.aluno_id AS aluno,
                    a.nome AS aluno_nome,
                    t.grupo,
                    t.exercicio,
                    t.series,
                    t.repeticoes,
                    t.carga,
                    DATE_FORMAT(t.data_cadastro, '%d/%m/%Y') AS data
                FROM treinos t
                INNER JOIN alunos a
                    ON a.id = t.aluno_id
                    AND a.id_academia = t.id_academia
                WHERE t.id_academia = ?
                ORDER BY t.id DESC
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
                    t.id,
                    t.aluno_id AS aluno,
                    a.nome AS aluno_nome,
                    t.grupo,
                    t.exercicio,
                    t.series,
                    t.repeticoes,
                    t.carga,
                    DATE_FORMAT(t.data_cadastro, '%d/%m/%Y') AS data
                FROM treinos t
                INNER JOIN alunos a
                    ON a.id = t.aluno_id
                    AND a.id_academia = t.id_academia
                WHERE t.id_academia = ?
                    AND a.id_instrutor = ?
                ORDER BY t.id DESC
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
                    t.id,
                    t.aluno_id AS aluno,
                    a.nome AS aluno_nome,
                    t.grupo,
                    t.exercicio,
                    t.series,
                    t.repeticoes,
                    t.carga,
                    DATE_FORMAT(t.data_cadastro, '%d/%m/%Y') AS data
                FROM treinos t
                INNER JOIN alunos a
                    ON a.id = t.aluno_id
                    AND a.id_academia = t.id_academia
                WHERE t.id_academia = ?
                    AND t.aluno_id = ?
                ORDER BY t.id DESC
            ";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('ii', $idAcademia, $idAluno);

        } else {
            responder(403, ['erro' => 'Você não tem permissão para visualizar treinos.']);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        $treinos = [];

        while ($linha = $resultado->fetch_assoc()) {
            $treinos[] = $linha;
        }

        responder(200, $treinos);
    }

    if ($metodo === 'POST') {

        if (!in_array($perfil, ['admin', 'instrutor'], true)) {
            responder(403, ['erro' => 'Você não tem permissão para cadastrar treinos.']);
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responder(400, ['erro' => 'Dados inválidos.']);
        }

        $aluno = (int) ($dados['aluno'] ?? 0);
        $grupo = trim($dados['grupo'] ?? '');
        $exercicio = trim($dados['exercicio'] ?? '');
        $series = (int) ($dados['series'] ?? 0);
        $repeticoes = trim($dados['repeticoes'] ?? '');
        $carga = (float) ($dados['carga'] ?? 0);

        if (
            $aluno <= 0 ||
            $grupo === '' ||
            $exercicio === '' ||
            $series <= 0 ||
            $repeticoes === '' ||
            $carga < 0
        ) {
            responder(400, [
                'erro' => 'Preencha todos os campos obrigatórios.'
            ]);
        }

        if ($perfil === 'admin') {

            $sqlVerificar = "
                SELECT id
                FROM alunos
                WHERE id = ?
                    AND id_academia = ?
            ";

            $verificar = $conexao->prepare($sqlVerificar);
            $verificar->bind_param('ii', $aluno, $idAcademia);

        } else {

            $idInstrutor = obterInstrutorId();

            if ($idInstrutor <= 0) {
                responder(403, ['erro' => 'Conta de instrutor sem vínculo válido.']);
            }

            $sqlVerificar = "
                SELECT id
                FROM alunos
                WHERE id = ?
                    AND id_academia = ?
                    AND id_instrutor = ?
            ";

            $verificar = $conexao->prepare($sqlVerificar);
            $verificar->bind_param('iii', $aluno, $idAcademia, $idInstrutor);
        }

        $verificar->execute();

        if ($verificar->get_result()->num_rows === 0) {
            responder(400, ['erro' => 'Aluno não encontrado ou não vinculado a você.']);
        }

        $sql = "
            INSERT INTO treinos
            (
                aluno_id,
                grupo,
                exercicio,
                series,
                repeticoes,
                carga,
                id_operador,
                id_academia
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            'issisdii',
            $aluno,
            $grupo,
            $exercicio,
            $series,
            $repeticoes,
            $carga,
            $idOperador,
            $idAcademia
        );

        $stmt->execute();

        responder(201, [
            'sucesso' => true,
            'mensagem' => 'Ficha de treino cadastrada com sucesso!',
            'id' => $conexao->insert_id
        ]);
    }

    if ($metodo === 'PUT') {

        if ($perfil !== 'admin') {
            responder(403, ['erro' => 'Somente o administrador pode editar treinos.']);
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responder(400, ['erro' => 'Dados inválidos.']);
        }

        $id = (int) ($dados['id'] ?? 0);
        $aluno = (int) ($dados['aluno'] ?? 0);
        $grupo = trim($dados['grupo'] ?? '');
        $exercicio = trim($dados['exercicio'] ?? '');
        $series = (int) ($dados['series'] ?? 0);
        $repeticoes = trim($dados['repeticoes'] ?? '');
        $carga = (float) ($dados['carga'] ?? 0);

        if (
            $id <= 0 ||
            $aluno <= 0 ||
            $grupo === '' ||
            $exercicio === '' ||
            $series <= 0 ||
            $repeticoes === '' ||
            $carga < 0
        ) {
            responder(400, ['erro' => 'Dados do treino inválidos.']);
        }

        $verificarAluno = $conexao->prepare("
            SELECT id
            FROM alunos
            WHERE id = ? AND id_academia = ?
        ");

        $verificarAluno->bind_param('ii', $aluno, $idAcademia);
        $verificarAluno->execute();

        if ($verificarAluno->get_result()->num_rows === 0) {
            responder(400, ['erro' => 'Aluno não encontrado nesta academia.']);
        }

        $stmt = $conexao->prepare("
            UPDATE treinos
            SET
                aluno_id = ?,
                grupo = ?,
                exercicio = ?,
                series = ?,
                repeticoes = ?,
                carga = ?
            WHERE id = ? AND id_academia = ?
        ");

        $stmt->bind_param(
            'issisdii',
            $aluno,
            $grupo,
            $exercicio,
            $series,
            $repeticoes,
            $carga,
            $id,
            $idAcademia
        );

        $stmt->execute();

        if ($stmt->affected_rows === 0) {

            $verificarTreino = $conexao->prepare("
                SELECT id
                FROM treinos
                WHERE id = ? AND id_academia = ?
            ");

            $verificarTreino->bind_param('ii', $id, $idAcademia);
            $verificarTreino->execute();

            if ($verificarTreino->get_result()->num_rows === 0) {
                responder(404, ['erro' => 'Treino não encontrado.']);
            }
        }

        responder(200, [
            'sucesso' => true,
            'mensagem' => 'Treino atualizado com sucesso!'
        ]);
    }

    if ($metodo === 'DELETE') {

        if ($perfil !== 'admin') {
            responder(403, ['erro' => 'Somente o administrador pode excluir treinos.']);
        }

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            responder(400, ['erro' => 'ID inválido.']);
        }

        $stmt = $conexao->prepare("
            DELETE FROM treinos
            WHERE id = ? AND id_academia = ?
        ");

        $stmt->bind_param('ii', $id, $idAcademia);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            responder(404, ['erro' => 'Treino não encontrado.']);
        }

        responder(200, [
            'sucesso' => true,
            'mensagem' => 'Treino excluído com sucesso!'
        ]);
    }

    responder(405, ['erro' => 'Método não permitido.']);

} catch (Throwable $e) {

    error_log('Erro na API de treinos: ' . $e->getMessage());

    responder(500, [
        'erro' => 'Ocorreu um erro ao processar a solicitação.'
    ]);
}
