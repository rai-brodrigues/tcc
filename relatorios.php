<?php
require_once __DIR__ . '/painel/verifica_login.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Relatórios | Gestão de Monitoramento</title>

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

        .card small {
            color: #ccc;
        }

        .card h2.text-danger {
            color: #dc3545 !important;
        }

        hr {
            border-color: #555;
        }

        #resumo,
        #relatorioAvaliacoes {
            color: #ddd;
        }

        .table {
            color: #fff;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #292929;
            color: #fff;
            border-color: #444;
        }

        .table tbody td {
            background-color: #1a1a1a;
            color: #ddd;
            border-color: #444;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #b02a37;
            border-color: #b02a37;
        }

        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
            }

            h2, h5, p, small, #resumo,
            #relatorioAvaliacoes {
                color: #000 !important;
            }

            .card {
                background: #fff !important;
                color: #000 !important;
                border: 1px solid #ccc !important;
                box-shadow: none !important;
            }

            .table thead th,
            .table tbody td {
                background: #fff !important;
                color: #000 !important;
                border-color: #ccc !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/includes_site/header.php'; ?>

<main class="container mt-4 mb-5">

    <h2>
        <i class="fa-solid fa-chart-line text-danger me-2"></i>
        Relatórios de desempenho
    </h2>

    <p class="text-muted">
        Consulte os dados gerais e o desempenho da academia.
    </p>

    <div class="card p-4 mt-4 shadow-sm">

        <h5>
            <i class="fa-solid fa-chart-pie text-danger me-2"></i>
            Resumo da academia
        </h5>

        <div class="row g-3 mt-2">

            <div class="col-md-3 col-6">
                <div class="card p-3 h-100">
                    <small>
                        <i class="fa-solid fa-users me-1"></i>
                        Total de alunos
                    </small>
                    <h2 class="text-danger mt-2" id="relTotalAlunos">0</h2>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card p-3 h-100">
                    <small>
                        <i class="fa-solid fa-dumbbell me-1"></i>
                        Total de treinos
                    </small>
                    <h2 class="text-danger mt-2" id="relTotalTreinos">0</h2>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card p-3 h-100">
                    <small>
                        <i class="fa-solid fa-person-chalkboard me-1"></i>
                        Instrutores
                    </small>
                    <h2 class="text-danger mt-2" id="relTotalInstrutores">0</h2>
                </div>
            </div>

            <div class="col-md-3 col-6">
                <div class="card p-3 h-100">
                    <small>
                        <i class="fa-solid fa-heart-pulse me-1"></i>
                        Avaliações
                    </small>
                    <h2 class="text-danger mt-2" id="relTotalAvaliacoes">0</h2>
                </div>
            </div>

        </div>

        <hr class="my-4">

        <h5>
            <i class="fa-solid fa-clipboard-list text-danger me-2"></i>
            Resumo geral
        </h5>

        <p id="resumo" class="mt-3">
            Carregando informações...
        </p>

        <hr class="my-4">

        <h5>
            <i class="fa-solid fa-file-medical text-danger me-2"></i>
            Histórico de avaliações físicas
        </h5>

        <div id="relatorioAvaliacoes" class="mt-3">
            Carregando avaliações...
        </div>

        <div class="mt-4 no-print">
            <button
                class="btn btn-danger"
                onclick="window.print()">

                <i class="fa-solid fa-print me-2"></i>
                Imprimir relatório
            </button>
        </div>

    </div>

</main>

<?php include __DIR__ . '/includes_site/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/tcc/includes_site/jsfitcontrol.js?v=4"></script>
</body>
</html>
