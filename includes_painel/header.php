<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION['usuario']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-danger">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            Gestão de Monitoramento
        </a>

        <button class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="horarios.php">
                        Horários
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="dias.php">
                        Dias
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="agendamento.php">
                        Agendamento
                    </a>
                </li>

            </ul>

            <?php if ($logado): ?>

                <span class="text-white me-3">
                    Olá, <?= htmlspecialchars($_SESSION['usuario']) ?>
                </span>

                <a href="logout.php">
                    <button type="button" class="btn btn-dark">
                        Sair
                    </button>
                </a>

            <?php else: ?>

                <a href="login/">
                    <button type="button" class="btn btn-success">
                        Fazer Login
                    </button>
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>
