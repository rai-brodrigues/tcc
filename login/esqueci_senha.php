
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensagem = $_SESSION['mensagem_recuperacao'] ?? '';
unset($_SESSION['mensagem_recuperacao']);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recuperar senha | FitControl</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0b0b0d;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .container {
            width: 100%;
            max-width: 430px;
            padding: 40px 35px;
            background: #171719;
            border: 1px solid #292929;
            border-top: 4px solid #e50914;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
        }

        h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 15px;
            font-size: 26px;
        }

        h2 i {
            color: #e50914;
            margin-right: 6px;
        }

        .descricao {
            text-align: center;
            color: #aaa;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        label {
            display: block;
            margin-bottom: 9px;
            color: #eee;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #383838;
            border-radius: 7px;
            margin-bottom: 20px;
            font-size: 15px;
            background: #0e0e10;
            color: #fff;
            outline: none;
            transition: border 0.3s, box-shadow 0.3s;
        }

        input:focus {
            border-color: #e50914;
            box-shadow: 0 0 0 2px rgba(229, 9, 20, 0.15);
        }

        input::placeholder {
            color: #777;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 7px;
            background: #e50914;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #b80710;
            transform: translateY(-2px);
        }

        button i {
            margin-right: 7px;
        }

        .mensagem {
            padding: 13px;
            margin-bottom: 20px;
            background: #242424;
            color: #eee;
            border-left: 4px solid #e50914;
            border-radius: 6px;
            font-size: 14px;
            text-align: center;
        }

        .voltar {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #e50914;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .voltar:hover {
            color: #ff454d;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 22px;
            }

            h2 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>
            <i class="fa fa-lock"></i>
            Recuperar senha
        </h2>

        <p class="descricao">
            Esqueceu sua senha?
            Informe seu e-mail cadastrado e enviaremos
            um link para você criar uma nova senha.
        </p>

        <?php if (!empty($mensagem)): ?>

            <div class="mensagem">
                <?php
                echo htmlspecialchars(
                    $mensagem,
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </div>

        <?php endif; ?>

        <form action="enviar_recuperacao.php" method="POST">

            <label for="email">
                E-mail cadastrado
            </label>

            <input
                type="email"
                name="email_operador"
                id="email"
                placeholder="Digite seu e-mail"
                required
                autocomplete="email"
            >

            <button type="submit">
                <i class="fa fa-envelope"></i>
                Enviar link de recuperação
            </button>

        </form>

        <a href="index.php" class="voltar">
            <i class="fa fa-arrow-left"></i>
            Voltar para o login
        </a>

    </div>

</body>
</html>
