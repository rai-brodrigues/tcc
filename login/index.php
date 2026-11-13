
<?php
session_start();
$erro = isset($_SESSION['nao_autenticado']) && $_SESSION['nao_autenticado'] === true;
if ($erro) {
    unset($_SESSION['nao_autenticado']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>LOGIN | Terceirão 3K26 Play</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/util.css">
    <link rel="stylesheet" href="css/main.css">

    <style>
        body {
            background: #111 !important;
        }

        .container-login100 {
            background: linear-gradient(135deg, #080808, #260000) !important;
        }

        .wrap-login100 {
            background: #181818 !important;
            border: 2px solid #b30000;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(220, 0, 0, 0.25);
            padding: 40px 30px;
        }

        .login100-form-title {
            color: #fff !important;
            font-weight: bold;
        }

        .input100 {
            color: #fff !important;
            background: #292929 !important;
            border-radius: 8px;
        }

        .input100::placeholder {
            color: #aaa !important;
        }

        .wrap-input100 {
            border-bottom: 2px solid #b30000 !important;
        }

        .symbol-input100 {
            color: #e00000 !important;
        }

        .login100-form-btn {
            background: #c00000 !important;
            color: #fff !important;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .login100-form-btn:hover {
            background: #ff0000 !important;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
        }

        .txt1 {
            color: #ccc !important;
        }

        .txt2 {
            color: #ff3333 !important;
            font-weight: bold;
        }

        .txt2:hover {
            color: #fff !important;
        }

        .cadastro-container {
            text-align: center;
            margin-top: 20px;
        }

        .cadastro-container p {
            color: #ccc;
            margin-bottom: 10px;
        }

        .btn-cadastro {
            display: inline-block;
            background: transparent;
            border: 2px solid #c00000;
            color: #ff3333;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none !important;
            transition: 0.3s;
        }

        .btn-cadastro:hover {
            background: #c00000;
            color: #fff;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }

        .alert-danger {
            background: #500000;
            color: #fff;
            border: 1px solid #ff3333;
        }
    </style>
</head>

<body>

<div class="limiter">
    <div class="container-login100">

        <div class="wrap-login100">

            <?php if ($erro): ?>
                <div class="alert alert-danger text-center" role="alert" style="width:100%; margin-bottom:1rem;">
                    Usuário ou senha inválidos. Verifique e tente novamente.
                </div>
            <?php endif; ?>

            <div class="login100-pic js-tilt" data-tilt>
                <img src="images/img-01.png" alt="IMG">
            </div>

            <form class="login100-form validate-form"
                  action="../autentica.php" method="POST">

                <span class="login100-form-title">
                    PAINEL ADMINISTRATIVO
                </span>

                <div class="wrap-input100 validate-input"
                     data-validate="E-mail obrigatório">

                    <input class="input100"
                           type="text"
                           name="email_operador"
                           placeholder="E-mail"
                           required>

                    <span class="focus-input100"></span>

                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input"
                     data-validate="Senha obrigatória">

                    <input class="input100"
                           type="password"
                           name="senha_operador"
                           placeholder="Senha"
                           required>

                    <span class="focus-input100"></span>

                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" type="submit">
                        Entrar
                    </button>
                </div>

                <!-- VOLTAR -->
                <div class="text-center p-t-12">
                    <span class="txt1">Para voltar</span>
                    <a class="txt2" href="../">
                        clique aqui
                    </a>
                </div>

                <!-- CADASTRO -->
                <div class="cadastro-container">
                    <p>Não tem uma conta?</p>

                    <a href="../cadastro.php" class="btn-cadastro">
                        <i class="fa fa-user-plus"></i>
                        Cadastre-se aqui
                    </a>
                </div>
				<div class="cadastro-container">
    				<a href="esqueci_senha.php" class="txt2">
        			<i class="fa fa-lock"></i>
        			Esqueci minha senha
    				</a>
				</div>

            </form>
        </div>
    </div>
</div>

<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap/js/popper.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/tilt/tilt.jquery.min.js"></script>

<script>
    $('.js-tilt').tilt({
        scale: 1.1
    });
</script>

<script src="js/main.js"></script>

</body>
</html>
