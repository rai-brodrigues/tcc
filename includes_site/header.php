<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } $logado = isset($_SESSION['usuario_id']); ?> <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
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
                <a class="nav-link" href="dias.php">
                    Dias
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="horarios.php">
                    Horários
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="agendamento.php">
                    Agendamento
                </a>
            </li>

        </ul>


        <!-- CONTATOS -->

        <div class="d-flex align-items-center me-3 gap-2">

            <!-- Instagram -->
            <a
                href="https://instagram.com/SEU_INSTAGRAM"
                target="_blank"
                class="text-white"
                aria-label="Instagram"
                title="Instagram"
            >
                <i class="fa-brands fa-instagram fs-5"></i>
            </a>

            <!-- WhatsApp -->
            <a
                href="https://wa.me/5551999999999"
                target="_blank"
                class="text-white"
                aria-label="WhatsApp"
                title="WhatsApp"
            >
                <i class="fa-brands fa-whatsapp fs-5"></i>
            </a>

            <!-- E-mail -->
            <a
                href="mailto:seuemail@gmail.com"
                class="text-white"
                aria-label="E-mail"
                title="E-mail"
            >
                <i class="fa-solid fa-envelope fs-5"></i>
            </a>

        </div>


        <?php if ($logado): ?>

            <span class="text-white me-3">
                Olá, <?= htmlspecialchars($_SESSION['nome_usuario']) ?>
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