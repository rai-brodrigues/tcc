
<footer class="footer-academia">
    <div class="footer-content">

        <div class="footer-column footer-brand">
            <div class="footer-logo">
                <i class="fas fa-dumbbell"></i>
                <span>FitControl</span>
            </div>

            <p>
                Sistema de gestão e monitoramento para academias.
                Mais organização, praticidade e controle para o seu negócio.
            </p>

            <div class="footer-status">
                <i class="fas fa-shield-alt"></i>
                Gestão com acesso protegido
            </div>
        </div>

        <div class="footer-column">
            <h5>Sistema</h5>
            <ul>
                <li><a href="dashboard.php">Painel inicial</a></li>
                <li><a href="alunos.php">Alunos</a></li>
                <li><a href="treinos.php">Treinos</a></li>
                <li><a href="avaliacoes.php">Avaliações</a></li>
                <li><a href="instrutores.php">Instrutores</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h5>Funcionalidades</h5>
            <ul>
                <li><span>Gestão de alunos</span></li>
                <li><span>Controle de treinos</span></li>
                <li><span>Acompanhamento físico</span></li>
                <li><span>Gestão de instrutores</span></li>
                <li><span>Relatórios da academia</span></li>
            </ul>
        </div>

        <div class="footer-column">
            <h5>Acesso</h5>

            <p>
                Acesse sua conta para gerenciar os dados
                e utilizar todos os recursos do FitControl.
            </p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>
            &copy; <?php echo date("Y"); ?>
            FitControl - Sistema de Gestão para Academias.
        </p>

        <span>
            <i class="fas fa-lock"></i>
            Todos os direitos reservados.
        </span>
    </div>
</footer>

<style>
html, body {
    min-height: 100%;
    margin: 0;
}

body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

header {
    flex-shrink: 0;
}

main {
    flex: 1 0 auto;
    padding-bottom: 40px;
}

.footer-academia {
    flex-shrink: 0;
    margin-top: auto;
    width: 100%;
    background: linear-gradient(135deg, #8b0000, #c1121f);
    color: #fff;
    padding: 55px 25px 0;
    box-sizing: border-box;
    border-top: 3px solid #6f0000;
    font-family: Arial, sans-serif;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1.5fr 1fr 1.2fr 1.3fr;
    gap: 45px;
    padding-bottom: 40px;
}

.footer-column h5 {
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    margin: 5px 0 22px;
    position: relative;
}

.footer-column h5::after {
    content: "";
    display: block;
    width: 32px;
    height: 3px;
    background: #ffd6d6;
    margin-top: 10px;
    border-radius: 5px;
}

.footer-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #fff;
    font-size: 25px;
    font-weight: 800;
    margin-bottom: 18px;
}

.footer-logo i {
    width: 43px;
    height: 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.16);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    font-size: 20px;
}

.footer-brand p,
.footer-column p {
    color: #f1dada;
    font-size: 13px;
    line-height: 1.8;
    margin: 0 0 18px;
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #fff;
    font-size: 12px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.22);
    padding: 9px 12px;
    border-radius: 6px;
}

.footer-status i {
    color: #ffd6d6;
}

.footer-column ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-column li {
    margin-bottom: 13px;
    font-size: 13px;
    color: #f1dada;
}

.footer-column li a,
.footer-column li span {
    color: #f1dada;
    text-decoration: none;
    transition: 0.2s;
}

.footer-column li a:hover {
    color: #fff;
    padding-left: 5px;
}

.footer-login {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 11px 17px;
    background: #fff;
    color: #a0000a;
    text-decoration: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
    transition: 0.2s;
}

.footer-login:hover {
    background: #ffe5e5;
    color: #8b0000;
    transform: translateY(-2px);
}

.footer-bottom {
    max-width: 1200px;
    margin: 0 auto;
    min-height: 65px;
    border-top: 1px solid rgba(255,255,255,0.22);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.footer-bottom p,
.footer-bottom span {
    color: #f1dada;
    font-size: 11px;
    margin: 0;
}

.footer-bottom span {
    display: flex;
    align-items: center;
    gap: 7px;
}

.footer-bottom i {
    color: #ffd6d6;
}

@media (max-width: 900px) {
    .footer-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 35px;
    }
}

@media (max-width: 550px) {
    .footer-academia {
        padding: 40px 22px 0;
    }

    .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
        padding-bottom: 30px;
    }

    .footer-logo {
        font-size: 23px;
    }

    .footer-bottom {
        justify-content: center;
        text-align: center;
        padding: 18px 0;
        flex-direction: column;
    }
}
</style>
