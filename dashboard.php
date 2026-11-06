<?php
require_once __DIR__ . '/painel/verifica_login.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Gestão de Monitoramento</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

     <style>
        body {
            background-color: #111 !important;
            color: #fff;
        }

        h2, h5 {
            color: #fff;
        }

        .text-muted {
            color: #aaa !important;
        }

        .card {
            background-color: #1a1a1a;
            color: #fff;
            border: 1px solid #292929;
            border-radius: 12px;
        }

        .form-label {
            color: #fff;
        }

        .form-control,
        .form-select {
            background-color: #222;
            color: #fff;
            border: 1px solid #444;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #222;
            color: #fff;
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control::placeholder {
            color: #aaa;
        }

        .form-select option {
            background-color: #222;
            color: #fff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            border-color: #b02a37;
        }

        .table {
            color: #fff;
            --bs-table-color: #fff;
            --bs-table-bg: #1a1a1a;
            --bs-table-hover-color: #fff;
            --bs-table-hover-bg: #292929;
            border-color: #444;
        }

        .table thead th {
            background-color: #dc3545;
            color: #fff;
            border-color: #b02a37;
        }

        .table td {
            color: #fff;
            background-color: #1a1a1a;
        }

        .table-hover tbody tr:hover td {
            background-color: #292929;
            color: #fff;
        }
    </style>

</head>

<body>

<?php include __DIR__ . '/includes_site/header.php'; ?>

<main class="container mt-4">

    <h2>Painel de controle</h2>

    <p class="text-muted">
        Bem-vindo ao sistema de supervisão de treinos.
    </p>

    <div class="row g-3 mt-3">

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <small>Alunos cadastrados</small>
                <h2 class="text-danger" id="total">0</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <small>Treinos cadastrados</small>
                <h2 class="text-danger" id="totalTreinos">0</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <small>Instrutores</small>
                <h2 class="text-danger" id="totalInstrutores">0</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <small>Avaliações físicas</small>
                <h2 class="text-danger" id="totalAvaliacoes">0</h2>
            </div>
        </div>

    </div>

    <div class="card p-4 mt-4 shadow-sm">

        <h5>Objetivo do sistema</h5>

        <p>
            Centralizar o cadastro dos alunos, organizar fichas de treino,
            acompanhar avaliações físicas e facilitar o trabalho dos instrutores.
        </p>

        <hr>

        <h5>Últimas atividades</h5>

        <div id="atividades">
            Nenhuma atividade registrada.
        </div>

    </div>

</main>

<?php include __DIR__ . '/includes_site/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/tcc/includes_site/jsfitcontrol.js?v=4"></script>
</body>
</html>
