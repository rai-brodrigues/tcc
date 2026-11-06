
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

    // LISTAR INSTRUTORES DO OPERADOR
    if ($metodo === 'GET') {
        $sql = "
            SELECT
                id,
                nome,
                especialidade,
                DATE_FORMAT(data_cadastro, '%d/%m/%Y') AS data
            FROM instrutores
            WHERE id_operador = ?
            ORDER BY id DESC
        ";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $idOperador);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $instrutores = [];

        while ($linha = $resultado->fetch_assoc()) {
            $instrutores[] = $linha;
        }

        echo json_encode($instrutores, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // CADASTRAR INSTRUTOR
    if ($metodo === 'POST') {
        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos.']);
            exit;
        }

        $nome = trim($dados['nome'] ?? '');
        $especialidade = trim($dados['especialidade'] ?? '');

        if ($nome === '') {
            http_response_code(400);
            echo json_encode(['erro' => 'Informe o nome do instrutor.']);
            exit;
        }

        $sql = "
            INSERT INTO instrutores
            (nome, especialidade, id_operador)
            VALUES (?, NULLIF(?, ''), ?)
        ";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('ssi', $nome, $especialidade, $idOperador);
        $stmt->execute();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Instrutor cadastrado com sucesso!',
            'id' => $conexao->insert_id
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // EXCLUIR INSTRUTOR DO OPERADOR
    if ($metodo === 'DELETE') {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id <= 0) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID do instrutor inválido.']);
            exit;
        }

        $stmt = $conexao->prepare(
            "DELETE FROM instrutores WHERE id = ? AND id_operador = ?"
        );

        $stmt->bind_param('ii', $id, $idOperador);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            http_response_code(404);
            echo json_encode(['erro' => 'Instrutor não encontrado.']);
            exit;
        }

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Instrutor excluído com sucesso!'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);

} catch (Throwable $erro) {
    error_log('Erro na API de instrutores: ' . $erro->getMessage());

    http_response_code(500);
    echo json_encode(['erro' => 'Erro interno ao processar os instrutores.']);
}
