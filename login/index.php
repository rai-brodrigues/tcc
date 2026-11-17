<?php
session_start();


$erro = isset($_SESSION['nao_autenticado']) && $_SESSION['nao_autenticado'] === true;
$erroPerfil = isset($_SESSION['erro_login']) && $_SESSION['erro_login'] === 'perfil';

if ($erro) {
    unset($_SESSION['nao_autenticado']);
}

if ($erroPerfil) {
    unset($_SESSION['erro_login']);
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>LOGIN | FitControl</title>
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
            display: block;
            margin-bottom: 20px;
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

        .opcoes-perfil {
            width: 100%;
        }

        .btn-perfil {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px;
            margin-bottom: 15px;
            background: #242424;
            border: 1px solid #555;
            border-radius: 10px;
            color: #fff;
            text-align: left;
            transition: 0.3s;
        }

        .btn-perfil:hover {
            background: #3a1010;
            border-color: #e00000;
            box-shadow: 0 0 12px rgba(220, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .btn-perfil i {
            font-size: 28px;
            color: #e00000;
            width: 35px;
            text-align: center;
        }

        .perfil-texto strong {
            display: block;
            font-size: 16px;
            color: #fff;
        }

        .perfil-texto small {
            display: block;
            color: #bbb;
            margin-top: 4px;
        }

        #formularioLogin {
            display: none;
        }

        .btn-voltar {
            display: inline-block;
            background: transparent;
            border: 1px solid #c00000;
            color: #ff3333;
            padding: 8px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .btn-voltar:hover {
            background: #c00000;
            color: #fff;
        }

        .descricao-perfil {
            color: #bbb;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="limiter">
    <div class="container-login100">

        <div class="wrap-login100">

           
<?php if ($erroPerfil): ?>
    <div class="alert alert-danger text-center" role="alert"
         style="width:100%; margin-bottom:1rem;">
        <i class="fa fa-exclamation-circle"></i>
        <strong>Não foi possível fazer login!</strong><br>
        A conta informada não pertence ao nível de acesso selecionado.
        Selecione o nível correto e tente novamente.
    </div>

<?php elseif ($erro): ?>
    <div class="alert alert-danger text-center" role="alert"
         style="width:100%; margin-bottom:1rem;">
        <i class="fa fa-exclamation-circle"></i>
        Usuário ou senha inválidos. Verifique e tente novamente.
    </div>
<?php endif; ?>


            <div class="login100-pic js-tilt" data-tilt>
                <img src="images/img-01.png" alt="FitControl">
            </div>

            <div id="selecaoPerfil">

                <span class="login100-form-title text-center">
                    ACESSO AO FITCONTROL
                </span>

                <p class="descricao-perfil">
                    Selecione como deseja entrar no sistema:
                </p>

                <div class="opcoes-perfil">

                    <button type="button"
                            class="btn-perfil"
                            onclick="selecionarPerfil('admin', 'ADMINISTRADOR')">

                        <i class="fa fa-shield"></i>

                        <span class="perfil-texto">
                            <strong>Entrar como Administrador</strong>
                            <small>Gerenciamento completo da academia.</small>
                        </span>
                    </button>

                    <button type="button"
                            class="btn-perfil"
                            onclick="selecionarPerfil('instrutor', 'INSTRUTOR')">

                            <i class="fa fa-user"></i>

                        <span class="perfil-texto">
                            <strong>Entrar como Instrutor</strong>
                            <small>Acesso aos alunos, treinos e avaliações vinculados.</small>
                        </span>
                    </button>

                    <button type="button"
                            class="btn-perfil"
                            onclick="selecionarPerfil('aluno', 'ALUNO')">

                        <i class="fa fa-user"></i>

                        <span class="perfil-texto">
                            <strong>Entrar como Aluno</strong>
                            <small>Acompanhe seus treinos e avaliações.</small>
                        </span>
                    </button>

                </div>

            </div>

            <form id="formularioLogin"
                  class="login100-form validate-form"
                  action="../autentica.php"
                  method="POST">

                <button type="button"
                        class="btn-voltar"
                        onclick="voltarPerfis()">
                    <i class="fa fa-arrow-left"></i> Voltar
                </button>

                <span class="login100-form-title text-center"
                      id="tituloPerfil">
                    ENTRAR
                </span>

                <p class="descricao-perfil" id="descricaoPerfil"></p>

                <input type="hidden"
                       name="perfil_escolhido"
                       id="perfilEscolhido">

                <div class="wrap-input100 validate-input"
                     data-validate="E-mail obrigatório">

                    <input class="input100"
                           type="email"
                           name="email_operador"
                           placeholder="E-mail"
                           autocomplete="username"
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
                           autocomplete="current-password"
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

                <div class="text-center p-t-12">
                    <span class="txt1">Para voltar</span>
                    <a class="txt2" href="../">
                        clique aqui
                    </a>
                </div>

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

    function selecionarPerfil(perfil, nomePerfil) {

        document.getElementById('perfilEscolhido').value = perfil;

        document.getElementById('tituloPerfil').textContent =
            'ENTRAR COMO ' + nomePerfil;

        const descricoes = {
            admin: 'Acesso administrativo da academia.',
            instrutor: 'Acesso à área do instrutor.',
            aluno: 'Acesso à área do aluno.'
        };

        document.getElementById('descricaoPerfil').textContent =
            descricoes[perfil];

        document.getElementById('selecaoPerfil').style.display = 'none';
        document.getElementById('formularioLogin').style.display = 'block';
    }

    function voltarPerfis() {

        document.getElementById('formularioLogin').style.display = 'none';
        document.getElementById('selecaoPerfil').style.display = 'block';

        document.getElementById('perfilEscolhido').value = '';
        document.getElementById('formularioLogin').reset();
    }
</script>

<script src="js/main.js"></script>

</body>
</html>
```