
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Verificar se o operador está conectado
if (empty($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Você precisa fazer login.']);
    exit;
}

$idOperador = (int) $_SESSION['usuario_id'];

require_once __DIR__ . '/conexao.php';

$metodo = $_SERVER['REQUEST_METHOD'];

try {

    // LISTAR SOMENTE OS ALUNOS DO OPERADOR
    if ($metodo === 'GET') {

        $sql = "
            SELECT
                id,
                nome,
                idade,
                email,
                telefone,
                plano,
                DATE_FORMAT(data_cadastro, '%d/%m/%Y') AS data
            FROM alunos
            WHERE id_operador = ?
            ORDER BY id DESC
        ";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('i', $idOperador);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $alunos = [];

        while ($linha = $resultado->fetch_assoc()) {
            $alunos[] = $linha;
        }

        echo json_encode($alunos);
        exit;
    }

    // CADASTRAR ALUNO PARA O OPERADOR CONECTADO
    if ($metodo === 'POST') {

        $dados = json_decode(file_get_contents('php://input'), true);

        if (!is_array($dados)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Dados inválidos.']);
            exit;
        }

        $nome = trim($dados['nome'] ?? '');
        $idade = (int) ($dados['idade'] ?? 0);
        $email = trim($dados['email'] ?? '');
        $telefone = trim($dados['telefone'] ?? '');
        $plano = trim($dados['plano'] ?? '');

        if ($nome === '' || $idade <= 0 || $plano === '') {
            http_response_code(400);
            echo json_encode([
                'erro' => 'Preencha os campos obrigatórios.'
            ]);
            exit;
        }

        $sql = "
            INSERT INTO alunos
            (nome, idade, email, telefone, plano, id_operador)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            'sisssi',
            $nome,
            $idade,
            $email,
            $telefone,
            $plano,
            $idOperador
        );

        $stmt->execute();

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Aluno cadastrado com sucesso!',
            'id' => $conexao->insert_id
        ]);

        exit;
    }

    // EXCLUIR SOMENTE ALUNO DO OPERADOR CONECTADO
    if ($metodo === 'DELETE') {

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID inválido.']);
            exit;
        }

        $stmt = $conexao->prepare(
            "DELETE FROM alunos WHERE id = ? AND id_operador = ?"
        );

        $stmt->bind_param('ii', $id, $idOperador);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            http_response_code(404);
            echo json_encode(['erro' => 'Aluno não encontrado.']);
            exit;
        }

        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Aluno excluído com sucesso!'
        ]);

        exit;
    }

    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);

} catch (Throwable $e) {

    error_log('Erro na API de alunos: ' . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        'erro' => 'Ocorreu um erro ao processar a solicitação.'
    ]);
}
