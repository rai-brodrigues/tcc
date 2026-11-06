
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

    if ($metodo === 'GET') {
        $sql = "
            SELECT
                av.id,
                av.aluno_id AS aluno,
                al.nome AS nome_aluno,
                av.peso,
                av.altura,
                av.gordura,
                ROUND(av.peso / (av.altura * av.altura), 2) AS imc,
                DATE_FORMAT(av.data_cadastro, '%d/%m/%Y') AS data
            FROM avaliacoes av
            INNER JOIN alunos al
                ON al.id = av.aluno_id
                AND al.id_operador = av.id_operador
            WHERE av.id_operador = ?
            ORDER BY av.id DESC
        ";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $idOperador);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $avaliacoes = [];

        while ($linha = $resultado->fetch_assoc()) {
            $avaliacoes[] = $linha;
        }

        echo json_encode($avaliacoes);
        exit;
    }

    if ($metodo === 'POST') {
        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos.']);
            exit;
        }

        $aluno = (int) ($dados['aluno'] ?? 0);
        $peso = (float) ($dados['peso'] ?? 0);
        $altura = (float) ($dados['altura'] ?? 0);

        $gorduraInformada = $dados['gordura'] ?? '';
        $gordura = ($gorduraInformada === '')
            ? null
            : (float) $gorduraInformada;

        if ($aluno <= 0 || $peso <= 0 || $altura <= 0) {
            http_response_code(400);
            echo json_encode([
                'erro' => 'Preencha corretamente os campos obrigatórios.'
            ]);
            exit;
        }

        if ($gordura !== null && ($gordura < 0 || $gordura > 100)) {
            http_response_code(400);
            echo json_encode([
                'erro' => 'A gordura corporal deve estar entre 0 e 100%.'
            ]);
            exit;
        }

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

        $sql = "
            INSERT INTO avaliacoes
            (aluno_id, peso, altura, gordura, id_operador)
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            'idddi',
            $aluno,
            $peso,
            $altura,
            $gordura,
            $idOperador
        );

        $stmt->execute();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Avaliação cadastrada com sucesso!',
            'id' => $conexao->insert_id
        ]);
        exit;
    }

    if ($metodo === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            exit;
        }

        $stmt = $conexao->prepare(
            "DELETE FROM avaliacoes WHERE id = ? AND id_operador = ?"
        );

        $stmt->bind_param('ii', $id, $idOperador);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            http_response_code(404);
            echo json_encode(['erro' => 'Avaliação não encontrada.']);
            exit;
        }

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Avaliação excluída com sucesso!'
        ]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);

} catch (Throwable $e) {
    error_log('Erro na API de avaliações: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'erro' => 'Ocorreu um erro ao processar a avaliação.'
    ]);
}
