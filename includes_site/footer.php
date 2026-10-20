<footer class="footer-academia">
    <div class="footer-content">

        <div class="footer-brand">
            <div class="footer-icon">
                <i class="fas fa-dumbbell"></i>
            </div>

            <div>
                <h5>Gestão de Monitoramento</h5>
                <p>Sistema de gerenciamento para academia</p>
            </div>
        </div>

        <div class="footer-info">
            <span>
                <i class="fas fa-shield-alt"></i>
                Sistema seguro
            </span>

            <span>
                <i class="fas fa-chart-line"></i>
                Monitoramento em tempo real
            </span>

            <span>
                <i class="fas fa-calendar-check"></i>
                Gestão simplificada
            </span>
        </div>

        <div class="footer-copy">
            <p>
                &copy; <?php echo date("Y"); ?> Gestão de Monitoramento para Academia
            </p>
            <small>Todos os direitos reservados.</small>
        </div>

    </div>
</footer>

<style>
.footer-academia {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(135deg, #8b0000, #c1121f);
    color: #fff;
    padding: 8px 20px;
    box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.20);
    z-index: 1000;
    box-sizing: border-box;
}

.footer-content {
    max-width: 1200px;
    margin: auto;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-icon {
    width: 32px;
    height: 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 8px;

    font-size: 15px;
    color: #fff;
    flex-shrink: 0;
}

.footer-brand h5 {
    margin: 0;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
}

.footer-brand p {
    margin: 1px 0 0;
    font-size: 10px;
    color: #fff;
}

.footer-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    font-size: 11px;
    color: #fff;
}

.footer-info span {
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    color: #fff;
}

.footer-info i {
    color: #fff;
}

.footer-copy {
    text-align: right;
    color: #fff;
}

.footer-copy p {
    margin: 0;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
}

.footer-copy small {
    color: #fff;
    font-size: 9px;
}

body {
    padding-bottom: 55px;
}

@media (max-width: 900px) {
    .footer-content {
        flex-direction: column;
        text-align: center;
        gap: 6px;
    }

    .footer-brand {
        justify-content: center;
    }

    .footer-info {
        flex-wrap: wrap;
        gap: 8px;
    }

    .footer-copy {
        text-align: center;
    }

    .footer-academia {
        position: relative;
    }

    body {
        padding-bottom: 0;
    }
}