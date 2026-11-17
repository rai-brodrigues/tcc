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

        $sql = "
            SELECT
                av.id,
                av.aluno_id AS aluno,
                al.nome AS nome_aluno,
                av.peso,
                av.altura,
                av.gordura,
                ROUND(av.peso / NULLIF(av.altura * av.altura, 0), 2) AS imc,
                DATE_FORMAT(av.data_cadastro, '%d/%m/%Y') AS data
            FROM avaliacoes av
            INNER JOIN alunos al
                ON al.id = av.aluno_id
                AND al.id_academia = av.id_academia
            WHERE av.id_academia = ?
        ";

        if ($perfil === 'instrutor') {
            $sql .= " AND al.id_instrutor = ?";
        } elseif ($perfil === 'aluno') {
            $sql .= " AND av.aluno_id = ?";
        } elseif ($perfil !== 'admin') {
            responder(403, ['erro' => 'Perfil sem permissão.']);
        }

        $sql .= " ORDER BY av.id DESC";

        $stmt = $conexao->prepare($sql);

        if ($perfil === 'instrutor') {
            $instrutorId = obterInstrutorId();
            $stmt->bind_param('ii', $idAcademia, $instrutorId);
        } elseif ($perfil === 'aluno') {
            $alunoId = obterAlunoId();
            $stmt->bind_param('ii', $idAcademia, $alunoId);
        } else {
            $stmt->bind_param('i', $idAcademia);
        }

        $stmt->execute();

        $resultado = $stmt->get_result();
        $avaliacoes = [];

        while ($linha = $resultado->fetch_assoc()) {
            $avaliacoes[] = $linha;
        }

        responder(200, $avaliacoes);
    }

    if ($metodo === 'POST') {

        if (!in_array($perfil, ['admin', 'instrutor'], true)) {
            responder(403, ['erro' => 'Você não tem permissão para cadastrar avaliações.']);
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responder(400, ['erro' => 'Dados inválidos.']);
        }

        $aluno = (int) ($dados['aluno'] ?? 0);
        $peso = (float) ($dados['peso'] ?? 0);
        $altura = (float) ($dados['altura'] ?? 0);

        $gorduraInformada = $dados['gordura'] ?? '';
        $gordura = ($gorduraInformada === '')
            ? null
            : (float) $gorduraInformada;

        if ($aluno <= 0 || $peso <= 0 || $altura <= 0) {
            responder(400, ['erro' => 'Preencha corretamente os campos obrigatórios.']);
        }

        if ($gordura !== null && ($gordura < 0 || $gordura > 100)) {
            responder(400, ['erro' => 'A gordura corporal deve estar entre 0 e 100%.']);
        }

        $sqlVerificar = "
            SELECT id
            FROM alunos
            WHERE id = ?
            AND id_academia = ?
        ";

        if ($perfil === 'instrutor') {
            $sqlVerificar .= " AND id_instrutor = ?";
        }

        $verificar = $conexao->prepare($sqlVerificar);

        if ($perfil === 'instrutor') {
            $instrutorId = obterInstrutorId();
            $verificar->bind_param('iii', $aluno, $idAcademia, $instrutorId);
        } else {
            $verificar->bind_param('ii', $aluno, $idAcademia);
        }

        $verificar->execute();

        if ($verificar->get_result()->num_rows === 0) {
            responder(400, ['erro' => 'Aluno não encontrado ou não vinculado a este instrutor.']);
        }

        $sql = "
            INSERT INTO avaliacoes
            (aluno_id, peso, altura, gordura, id_operador, id_academia)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            'idddii',
            $aluno,
            $peso,
            $altura,
            $gordura,
            $idOperador,
            $idAcademia
        );

        $stmt->execute();

        responder(201, [
            'sucesso' => true,
            'mensagem' => 'Avaliação cadastrada com sucesso!',
            'id' => $conexao->insert_id
        ]);
    }

    if ($metodo === 'PUT') {
        if ($perfil !== 'admin') {
            responder(403, ['erro' => 'Somente o administrador pode editar avaliações.']);
        }

        responder(400, ['erro' => 'A edição de avaliações ainda não está implementada.']);
    }

    if ($metodo === 'DELETE') {

        if ($perfil !== 'admin') {
            responder(403, ['erro' => 'Somente o administrador pode excluir avaliações.']);
        }

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            responder(400, ['erro' => 'ID inválido.']);
        }

        $stmt = $conexao->prepare("
            DELETE FROM avaliacoes
            WHERE id = ? AND id_academia = ?
        ");

        $stmt->bind_param('ii', $id, $idAcademia);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            responder(404, ['erro' => 'Avaliação não encontrada.']);
        }

        responder(200, [
            'sucesso' => true,
            'mensagem' => 'Avaliação excluída com sucesso!'
        ]);
    }

    responder(405, ['erro' => 'Método não permitido.']);

} catch (Throwable $e) {

    error_log('Erro na API de avaliações: ' . $e->getMessage());

    responder(500, [
        'erro' => 'Ocorreu um erro ao processar a avaliação.'
    ]);
}
