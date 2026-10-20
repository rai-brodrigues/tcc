<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agendamento</title>

    <?php include 'includes_site/head.php'; ?>
</head>

<body>

    <?php include 'includes_site/header.php'; ?>

    <main class="container mt-5 mb-5">

        <div class="card shadow">
            <div class="card-body p-4">

                <h1 class="text-center mb-4">AGENDAMENTO</h1>

                <form action="horarios.php" method="POST">

                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="nome" class="form-label">
                            Nome <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            class="form-control"
                            placeholder="Digite seu nome"
                            required
                            minlength="3"
                        >
                    </div>

                    <!-- Data -->
                    <div class="mb-3">
                        <label for="data" class="form-label">
                            Data <span class="text-danger">*</span>
                        </label>

                        <input
                            type="date"
                            id="data"
                            name="data"
                            class="form-control"
                            required
                        >
                    </div>

                    <!-- Horário -->
                    <div class="mb-3">
                        <label for="horario" class="form-label">
                            Horário <span class="text-danger">*</span>
                        </label>

                        <input
                            type="time"
                            id="horario"
                            name="horario"
                            class="form-control"
                            required
                        >
                    </div>

                    <!-- Treino -->
                    <div class="mb-4">
                        <label for="treino" class="form-label">
                            Treino <span class="text-danger">*</span>
                        </label>

                        <select
                            id="treino"
                            name="treino"
                            class="form-select"
                            required
                        >
                            <option value="" selected disabled>
                                Selecione o treino
                            </option>

                            <option value="Musculação">
                                Musculação
                            </option>

                            <option value="Cardio">
                                Cardio
                            </option>

                            <option value="Funcional">
                                Funcional
                            </option>

                            <option value="Cross Training">
                                Cross Training
                            </option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-danger w-100"
                        >
                            Agendar
                        </button>

                        <a
                            href="horarios.php"
                            class="btn btn-secondary w-100"
                        >
                            Ver horários
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </main>

    <?php include 'includes_site/footer.php'; ?>

</body>
</html>