<?php

require_once __DIR__ . '/verifica_permissao.php';
require_once __DIR__ . '/conexao.php';

header('Content-Type: application/json; charset=utf-8');

$idOperador = obterUsuarioId();
$idAcademia = obterAcademiaId();
$perfil = obterPerfil();
$idInstrutorSessao = obterInstrutorId();
$idAlunoSessao = obterAlunoId();

function responderAgenda($codigo, $dados)
{
    http_response_code($codigo);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

function validarDataAgenda($data)
{
    $data = str_replace('T', ' ', trim($data));

    if (strlen($data) === 16) {
        $data .= ':00';
    }

    $objeto = DateTime::createFromFormat('Y-m-d H:i:s', $data);

    if (!$objeto || $objeto->format('Y-m-d H:i:s') !== $data) {
        return false;
    }

    return $data;
}

function validarHorarioFuncionamentoAgenda($inicio, $fim)
{
    if (!$inicio || !$fim) {
        return '';
    }

    $dataInicio = new DateTime($inicio);
    $dataFim = new DateTime($fim);

    $diaSemana = (int) $dataInicio->format('N');

    if ($diaSemana >= 6) {
        return 'A academia não aceita agendamentos aos sábados e domingos.';
    }

    if ($dataInicio->format('Y-m-d') !== $dataFim->format('Y-m-d')) {
        return 'O agendamento deve terminar no mesmo dia.';
    }

    $horaInicio = ((int) $dataInicio->format('H')) * 60
        + (int) $dataInicio->format('i');

    $horaFim = ((int) $dataFim->format('H')) * 60
        + (int) $dataFim->format('i');

    if ($horaInicio < 360 || $horaInicio >= 1320 || $horaFim > 1320) {
        return 'O horário permitido é das 06h às 22h.';
    }

    if (
        (int) $dataInicio->format('i') !== 0 ||
        (int) $dataFim->format('i') !== 0
    ) {
        return 'Os agendamentos devem começar e terminar em horários cheios.';
    }

    if ($dataFim <= $dataInicio) {
        return 'O horário final deve ser posterior ao inicial.';
    }

    return '';
}

function verificarAlunoAgenda($conexao, $idAluno, $idAcademia, $idInstrutor = null)
{
    if ($idAluno <= 0) {
        return false;
    }

    if ($idInstrutor !== null) {
        $stmt = $conexao->prepare("
            SELECT id
            FROM alunos
            WHERE id = ?
                AND id_academia = ?
                AND id_instrutor = ?
        ");
        $stmt->bind_param('iii', $idAluno, $idAcademia, $idInstrutor);
    } else {
        $stmt = $conexao->prepare("
            SELECT id
            FROM alunos
            WHERE id = ?
                AND id_academia = ?
        ");
        $stmt->bind_param('ii', $idAluno, $idAcademia);
    }

    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

function verificarInstrutorAgenda($conexao, $idInstrutor, $idAcademia)
{
    if ($idInstrutor <= 0) {
        return false;
    }

    $stmt = $conexao->prepare("
        SELECT id
        FROM instrutores
        WHERE id = ?
            AND id_academia = ?
    ");

    $stmt->bind_param('ii', $idInstrutor, $idAcademia);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}

try {

    if ($idOperador <= 0 || $idAcademia <= 0) {
        responderAgenda(401, [
            'erro' => 'Sessão inválida. Faça login novamente.'
        ]);
    }

    $metodo = $_SERVER['REQUEST_METHOD'];

   

    if ($metodo === 'GET') {

        $sql = "
            SELECT
                ag.id,
                ag.id_instrutor,
                ag.id_aluno,
                ag.titulo,
                ag.tipo,
                ag.descricao,
                ag.inicio,
                ag.fim,
                ag.status,
                a.nome AS aluno_nome,
                i.nome AS instrutor_nome
            FROM agenda ag

            LEFT JOIN alunos a
                ON a.id = ag.id_aluno
                AND a.id_academia = ag.id_academia

            LEFT JOIN instrutores i
                ON i.id = ag.id_instrutor
                AND i.id_academia = ag.id_academia

            WHERE ag.id_academia = ?
        ";

        if ($perfil === 'admin') {

            $stmt = $conexao->prepare($sql . "
                ORDER BY ag.inicio ASC
            ");

            $stmt->bind_param('i', $idAcademia);

        } elseif ($perfil === 'instrutor') {

            if ($idInstrutorSessao <= 0) {
                responderAgenda(403, [
                    'erro' => 'Conta de instrutor sem vínculo válido.'
                ]);
            }

            $stmt = $conexao->prepare($sql . "
                AND ag.id_instrutor = ?
                ORDER BY ag.inicio ASC
            ");

            $stmt->bind_param(
                'ii',
                $idAcademia,
                $idInstrutorSessao
            );

        } elseif ($perfil === 'aluno') {

            if ($idAlunoSessao <= 0) {
                responderAgenda(403, [
                    'erro' => 'Conta de aluno sem vínculo válido.'
                ]);
            }

            $stmt = $conexao->prepare($sql . "
                AND ag.id_aluno = ?
                ORDER BY ag.inicio ASC
            ");

            $stmt->bind_param(
                'ii',
                $idAcademia,
                $idAlunoSessao
            );

        } else {
            responderAgenda(403, [
                'erro' => 'Você não tem permissão para visualizar a agenda.'
            ]);
        }

        $stmt->execute();

        $resultado = $stmt->get_result();
        $eventos = [];

        while ($linha = $resultado->fetch_assoc()) {
            $eventos[] = $linha;
        }

        responderAgenda(200, $eventos);
    }

    

    if ($metodo === 'POST') {

        if (!in_array($perfil, ['admin', 'instrutor'], true)) {
            responderAgenda(403, [
                'erro' => 'Alunos não podem cadastrar agendamentos.'
            ]);
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responderAgenda(400, [
                'erro' => 'Dados inválidos.'
            ]);
        }

        $titulo = trim($dados['titulo'] ?? '');
        $tipo = $dados['tipo'] ?? 'treino';
        $descricao = trim($dados['descricao'] ?? '');
        $inicio = validarDataAgenda($dados['inicio'] ?? '');
        $fim = validarDataAgenda($dados['fim'] ?? '');
        $erroHorario = validarHorarioFuncionamentoAgenda($inicio, $fim);

        $tiposPermitidos = [
            'treino',
            'avaliacao',
            'atendimento',
            'outro'
        ];

        if (
    $titulo === '' ||
    !in_array($tipo, $tiposPermitidos, true) ||
    !$inicio ||
    !$fim ||
    $erroHorario !== '' ||
    strtotime($fim) <= strtotime($inicio)
) {
    responderAgenda(400, [
        'erro' => $erroHorario !== ''
            ? $erroHorario
            : 'Verifique o título, tipo, início e término do compromisso.'
    ]);
}

        $idAluno = (int) ($dados['id_aluno'] ?? 0);
        $idInstrutor = (int) ($dados['id_instrutor'] ?? 0);

        if ($perfil === 'instrutor') {

            if ($idInstrutorSessao <= 0) {
                responderAgenda(403, [
                    'erro' => 'Conta de instrutor sem vínculo válido.'
                ]);
            }

            $idInstrutor = $idInstrutorSessao;

        } else {

            if (
                $idInstrutor > 0 &&
                !verificarInstrutorAgenda(
                    $conexao,
                    $idInstrutor,
                    $idAcademia
                )
            ) {
                responderAgenda(400, [
                    'erro' => 'Instrutor não encontrado nesta academia.'
                ]);
            }
        }

        if ($idAluno > 0) {

            $instrutorValidacao = $idInstrutor > 0
                ? $idInstrutor
                : null;

            if (!verificarAlunoAgenda(
                $conexao,
                $idAluno,
                $idAcademia,
                $instrutorValidacao
            )) {
                responderAgenda(400, [
                    'erro' => 'Aluno não encontrado ou não vinculado ao instrutor.'
                ]);
            }
        } else {
            $idAluno = null;
        }

        if ($idInstrutor <= 0) {
            $idInstrutor = null;
        }

        $stmt = $conexao->prepare("
            INSERT INTO agenda (
                id_academia,
                id_instrutor,
                id_aluno,
                id_operador,
                titulo,
                tipo,
                descricao,
                inicio,
                fim
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            'iiiisssss',
            $idAcademia,
            $idInstrutor,
            $idAluno,
            $idOperador,
            $titulo,
            $tipo,
            $descricao,
            $inicio,
            $fim
        );

        $stmt->execute();

        responderAgenda(201, [
            'sucesso' => true,
            'mensagem' => 'Agendamento cadastrado com sucesso!',
            'id' => $conexao->insert_id
        ]);
    }

    

    if ($metodo === 'PUT') {

        if (!in_array($perfil, ['admin', 'instrutor'], true)) {
            responderAgenda(403, [
                'erro' => 'Você não tem permissão para editar agendamentos.'
            ]);
        }

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            responderAgenda(400, [
                'erro' => 'Dados inválidos.'
            ]);
        }

        $id = (int) ($dados['id'] ?? 0);
        $titulo = trim($dados['titulo'] ?? '');
        $tipo = $dados['tipo'] ?? '';
        $descricao = trim($dados['descricao'] ?? '');
        $inicio = validarDataAgenda($dados['inicio'] ?? '');
        $fim = validarDataAgenda($dados['fim'] ?? '');
        $erroHorario = validarHorarioFuncionamentoAgenda($inicio, $fim);
        $status = $dados['status'] ?? 'agendado';

        $tiposPermitidos = [
            'treino',
            'avaliacao',
            'atendimento',
            'outro'
        ];

        $statusPermitidos = [
            'agendado',
            'concluido',
            'cancelado'
        ];

        if (
    $id <= 0 ||
    $titulo === '' ||
    !in_array($tipo, $tiposPermitidos, true) ||
    !in_array($status, $statusPermitidos, true) ||
    !$inicio ||
    !$fim ||
    $erroHorario !== '' ||
    strtotime($fim) <= strtotime($inicio)
) {
    responderAgenda(400, [
        'erro' => $erroHorario !== ''
            ? $erroHorario
            : 'Dados do agendamento inválidos.'
    ]);
}

        $idAluno = (int) ($dados['id_aluno'] ?? 0);

        if ($perfil === 'instrutor') {

            if ($idInstrutorSessao <= 0) {
                responderAgenda(403, [
                    'erro' => 'Conta de instrutor sem vínculo válido.'
                ]);
            }

            $idInstrutor = $idInstrutorSessao;

            $verificar = $conexao->prepare("
                SELECT id
                FROM agenda
                WHERE id = ?
                    AND id_academia = ?
                    AND id_instrutor = ?
            ");

            $verificar->bind_param(
                'iii',
                $id,
                $idAcademia,
                $idInstrutor
            );

            $verificar->execute();

            if ($verificar->get_result()->num_rows === 0) {
                responderAgenda(404, [
                    'erro' => 'Agendamento não encontrado ou não pertence a você.'
                ]);
            }

        } else {

            $idInstrutor = (int) ($dados['id_instrutor'] ?? 0);

            $verificar = $conexao->prepare("
                SELECT id
                FROM agenda
                WHERE id = ? AND id_academia = ?
            ");

            $verificar->bind_param('ii', $id, $idAcademia);
            $verificar->execute();

            if ($verificar->get_result()->num_rows === 0) {
                responderAgenda(404, [
                    'erro' => 'Agendamento não encontrado.'
                ]);
            }

            if (
                $idInstrutor > 0 &&
                !verificarInstrutorAgenda(
                    $conexao,
                    $idInstrutor,
                    $idAcademia
                )
            ) {
                responderAgenda(400, [
                    'erro' => 'Instrutor não encontrado nesta academia.'
                ]);
            }
        }

        if ($idAluno > 0) {

            $instrutorValidacao = $idInstrutor > 0
                ? $idInstrutor
                : null;

            if (!verificarAlunoAgenda(
                $conexao,
                $idAluno,
                $idAcademia,
                $instrutorValidacao
            )) {
                responderAgenda(400, [
                    'erro' => 'Aluno não encontrado ou não vinculado ao instrutor.'
                ]);
            }

        } else {
            $idAluno = null;
        }

        if ($idInstrutor <= 0) {
            $idInstrutor = null;
        }

        $stmt = $conexao->prepare("
            UPDATE agenda
            SET
                id_instrutor = ?,
                id_aluno = ?,
                titulo = ?,
                tipo = ?,
                descricao = ?,
                inicio = ?,
                fim = ?,
                status = ?
            WHERE id = ?
                AND id_academia = ?
        ");

        $stmt->bind_param(
            'iissssssii',
            $idInstrutor,
            $idAluno,
            $titulo,
            $tipo,
            $descricao,
            $inicio,
            $fim,
            $status,
            $id,
            $idAcademia
        );

        $stmt->execute();

        responderAgenda(200, [
            'sucesso' => true,
            'mensagem' => 'Agendamento atualizado com sucesso!'
        ]);
    }

    

    if ($metodo === 'DELETE') {

        if (!in_array($perfil, ['admin', 'instrutor'], true)) {
            responderAgenda(403, [
                'erro' => 'Alunos não podem excluir agendamentos.'
            ]);
        }

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            responderAgenda(400, [
                'erro' => 'ID inválido.'
            ]);
        }

        if ($perfil === 'admin') {

            $stmt = $conexao->prepare("
                DELETE FROM agenda
                WHERE id = ?
                    AND id_academia = ?
            ");

            $stmt->bind_param('ii', $id, $idAcademia);

        } else {

            if ($idInstrutorSessao <= 0) {
                responderAgenda(403, [
                    'erro' => 'Conta de instrutor sem vínculo válido.'
                ]);
            }

            $stmt = $conexao->prepare("
                DELETE FROM agenda
                WHERE id = ?
                    AND id_academia = ?
                    AND id_instrutor = ?
            ");

            $stmt->bind_param(
                'iii',
                $id,
                $idAcademia,
                $idInstrutorSessao
            );
        }

        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            responderAgenda(404, [
                'erro' => 'Agendamento não encontrado ou sem permissão.'
            ]);
        }

        responderAgenda(200, [
            'sucesso' => true,
            'mensagem' => 'Agendamento excluído com sucesso!'
        ]);
    }

    responderAgenda(405, [
        'erro' => 'Método não permitido.'
    ]);

} catch (Throwable $e) {

    error_log('Erro na API da agenda: ' . $e->getMessage());

    responderAgenda(500, [
        'erro' => 'Ocorreu um erro ao processar a agenda.'
    ]);
}
