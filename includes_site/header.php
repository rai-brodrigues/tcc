
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = !empty($_SESSION['usuario_id']);

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-danger">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            Gestão de Monitoramento
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu"
                aria-controls="menu"
                aria-expanded="false"
                aria-label="Abrir menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav me-auto">

                <?php if ($logado): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            Painel
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="alunos.php">
                            Alunos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="treinos.php">
                            Treinos
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="avaliacoes.php">
                            Avaliações
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="instrutores.php">
                            Instrutores
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="relatorios.php">
                            Relatórios
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

            <div class="d-flex align-items-center me-3 gap-2">

                <a
                    href="https://www.instagram.com/colegioceedo/?next=%2F"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-white"
                    aria-label="Instagram"
                    title="Instagram"
                >
                    <i class="fa-brands fa-instagram fs-5"></i>
                </a>

                <a
                    href="https://wa.me/5551999999999"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-white"
                    aria-label="WhatsApp"
                    title="WhatsApp"
                >
                    <i class="fa-brands fa-whatsapp fs-5"></i>
                </a>

                <a
                    href="https://mail.google.com/mail/u/0/#inbox?compose=CllgCHrdlQsLbGCPjSXgnWvDrkDssVPbTbKsrSJvzMvwpVqjFcnRgwCldrNtkKQCbbJGCXnSZHL"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-white"
                    aria-label="E-mail"
                    title="E-mail"
                >
                    <i class="fa-solid fa-envelope fs-5"></i>
                </a>

            </div>

            <?php if ($logado): ?>

                <span class="text-white me-3">
                    Olá,
                    <?= htmlspecialchars(
                        $_SESSION['nome_usuario'] ?? 'Usuário',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

                <a href="logout.php" class="btn btn-dark">
                    Sair
                </a>

            <?php else: ?>

                <a href="login/" class="btn btn-dark">
                    Fazer Login
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>
