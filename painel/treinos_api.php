
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Você precisa fazer login.']);
    exit;
}

$idOperador = (int) $_SESSION['usuario_id'];

require_once __DIR__ . '/conexao.php';

try {
    $metodo = $_SERVER['REQUEST_METHOD'];

    // LISTAR TREINOS DO OPERADOR
    if ($metodo === 'GET') {
        $sql = "
            SELECT
                id,
                aluno_id AS aluno,
                grupo,
                exercicio,
                series,
                repeticoes,
                carga,
                DATE_FORMAT(data_cadastro, '%d/%m/%Y') AS data
            FROM treinos
            WHERE id_operador = ?
            ORDER BY id DESC
        ";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $idOperador);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $treinos = [];

        while ($linha = $resultado->fetch_assoc()) {
            $treinos[] = $linha;
        }

        echo json_encode($treinos);
        exit;
    }

    // CADASTRAR TREINO
    if ($metodo === 'POST') {
        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos.']);
            exit;
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
            $repeticoes === ''
        ) {
            http_response_code(400);
            echo json_encode([
                'erro' => 'Preencha todos os campos obrigatórios.'
            ]);
            exit;
        }

        // CONFIRMAR QUE O ALUNO PERTENCE AO OPERADOR
        $verificar = $conexao->prepare(
            "SELECT id FROM alunos WHERE id = ? AND id_operador = ?"
        );

        $verificar->bind_param('ii', $aluno, $idOperador);
        $verificar->execute();

        if ($verificar->get_result()->num_rows === 0) {
            http_response_code(400);
            echo json_encode(['erro' => 'Aluno não encontrado.']);
            exit;
        }

        // INSERIR TREINO COM O OPERADOR DA SESSÃO
        $sql = "
            INSERT INTO treinos
            (aluno_id, grupo, exercicio, series, repeticoes, carga, id_operador)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            'issisdi',
            $aluno,
            $grupo,
            $exercicio,
            $series,
            $repeticoes,
            $carga,
            $idOperador
        );

        $stmt->execute();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Ficha de treino cadastrada com sucesso!',
            'id' => $conexao->insert_id
        ]);
        exit;
    }

    // EXCLUIR TREINO DO OPERADOR
    if ($metodo === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            exit;
        }

        $stmt = $conexao->prepare(
            "DELETE FROM treinos WHERE id = ? AND id_operador = ?"
        );

        $stmt->bind_param('ii', $id, $idOperador);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            http_response_code(404);
            echo json_encode(['erro' => 'Treino não encontrado.']);
            exit;
        }

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Treino excluído com sucesso!'
        ]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);

} catch (Throwable $e) {
    error_log('Erro na API de treinos: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'erro' => 'Ocorreu um erro ao processar a solicitação.'
    ]);
}
