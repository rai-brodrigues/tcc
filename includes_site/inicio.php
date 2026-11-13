
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Conheça nossa academia, modalidades de treino, estrutura e benefícios para sua saúde e qualidade de vida.">

    <title>Academia | Gestão de Monitoramento</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>

        :root {
            --vermelho: #ff0000;
            --vermelho-escuro: #c00000;
            --preto: #111111;
            --preto-secundario: #0b0b0b;
            --card: #1a1a1a;
            --branco: #ffffff;
            --cinza: #aaaaaa;
            --borda: #292929;
            --transicao: 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 25px;
        }

        body {
            font-family: 'Montserrat', Arial, Helvetica, sans-serif;
            background: var(--preto);
            color: var(--branco);
            overflow-x: hidden;
        }

        a {
            -webkit-tap-highlight-color: transparent;
        }

        button {
            font-family: inherit;
        }

       

        .carrossel {
            width: 100%;
            max-width: 1300px;
            margin: 0 auto;
            padding: 75px 25px 65px;
            position: relative;
        }

        .titulo {
            text-align: center;
            margin-bottom: 40px;
        }

        .titulo h1 {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 4px;
            color: var(--vermelho);
            margin-bottom: 17px;
            text-transform: uppercase;
        }

        .titulo h1::before,
        .titulo h1::after {
            content: "";
            width: 30px;
            height: 2px;
            background: var(--vermelho);
        }

        .titulo h2 {
            font-size: clamp(28px, 4vw, 43px);
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .titulo h2 span {
            color: var(--vermelho);
        }

        .titulo p {
            margin-top: 15px;
            color: var(--cinza);
            font-size: 15px;
            line-height: 1.7;
        }

        .slider {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: #000;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.4);
        }

        .slides {
            display: flex;
            transition: transform 0.65s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .card {
            min-width: 100%;
            height: 530px;
            position: relative;
            overflow: hidden;
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 6s ease;
        }

        .card.ativo img {
            transform: scale(1.07);
        }

        .card::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(
                    90deg,
                    rgba(0, 0, 0, 0.85) 0%,
                    rgba(0, 0, 0, 0.48) 48%,
                    rgba(0, 0, 0, 0.08) 100%
                ),
                linear-gradient(
                    to top,
                    rgba(0, 0, 0, 0.65),
                    transparent 55%
                );
        }

        .conteudo {
            position: absolute;
            z-index: 2;
            bottom: 65px;
            left: 65px;
            right: 65px;
            max-width: 650px;
        }

        .conteudo .categoria {
            display: inline-block;
            background: var(--vermelho);
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .conteudo h3 {
            font-size: clamp(32px, 5vw, 55px);
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .conteudo p {
            font-size: 16px;
            line-height: 1.8;
            color: #eeeeee;
            max-width: 570px;
        }

        .seta {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            width: 48px;
            height: 48px;
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 50%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(8px);
            color: #fff;
            font-size: 17px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transicao);
        }

        .seta:hover {
            background: var(--vermelho);
            border-color: var(--vermelho);
            transform: translateY(-50%) scale(1.08);
        }

        .anterior {
            left: 20px;
        }

        .proximo {
            right: 20px;
        }

        .indicadores {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
        }

        .indicador {
            width: 25px;
            height: 5px;
            border: none;
            border-radius: 10px;
            background: #555;
            cursor: pointer;
            transition: var(--transicao);
        }

        .indicador.ativo {
            width: 45px;
            background: var(--vermelho);
        }

      

        .sobre {
            width: 100%;
            background: var(--preto-secundario);
            padding: 100px 25px;
            border-top: 1px solid #222;
            border-bottom: 1px solid #222;
        }

        .sobre-container {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 75px;
            align-items: center;
        }

        .sobre-imagem {
            position: relative;
            height: 490px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #333;
        }

        .sobre-imagem::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(0,0,0,0.3),
                transparent
            );
            pointer-events: none;
        }

        .sobre-imagem img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.7s ease;
        }

        .sobre-imagem:hover img {
            transform: scale(1.06);
        }

        .sobre-destaque {
            position: absolute;
            z-index: 2;
            left: 20px;
            bottom: 20px;
            background: var(--vermelho);
            padding: 17px 22px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .sobre-destaque i {
            font-size: 25px;
        }

        .sobre-destaque strong {
            font-size: 13px;
            display: block;
        }

        .sobre-destaque small {
            font-size: 11px;
            display: block;
            margin-top: 4px;
        }

        .sobre-texto .subtitulo {
            color: var(--vermelho);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 15px;
            display: block;
        }

        .sobre-texto h2 {
            font-size: clamp(30px, 4vw, 43px);
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 25px;
        }

        .sobre-texto h2 span {
            color: var(--vermelho);
        }

        .sobre-texto p {
            color: #bbbbbb;
            font-size: 15px;
            line-height: 1.9;
            margin-bottom: 17px;
        }

        .sobre-lista {
            list-style: none;
            margin-top: 25px;
            display: grid;
            gap: 14px;
        }

        .sobre-lista li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #eeeeee;
            font-size: 14px;
            font-weight: 600;
        }

        .sobre-lista i {
            color: var(--vermelho);
            font-size: 17px;
        }

        .botao {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            padding: 15px 28px;
            background: var(--vermelho);
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 7px;
            transition: var(--transicao);
        }

        .botao:hover {
            background: #fff;
            color: #111;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(255,0,0,0.2);
        }

        

        .beneficios {
            padding: 100px 25px;
            background: var(--preto);
        }

        .secao-titulo {
            text-align: center;
            margin-bottom: 55px;
        }

        .secao-titulo .subtitulo {
            display: block;
            color: var(--vermelho);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .secao-titulo h2 {
            font-size: clamp(29px, 4vw, 42px);
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .secao-titulo h2 span {
            color: var(--vermelho);
        }

        .secao-titulo p {
            color: var(--cinza);
            font-size: 15px;
            line-height: 1.7;
        }

        .beneficios-grid {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .beneficio {
            position: relative;
            background: var(--card);
            padding: 38px 28px;
            text-align: center;
            border-radius: 13px;
            border: 1px solid var(--borda);
            transition: var(--transicao);
            overflow: hidden;
        }

        .beneficio::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--vermelho);
            transform: scaleX(0);
            transform-origin: left;
            transition: 0.4s ease;
        }

        .beneficio:hover {
            transform: translateY(-8px);
            border-color: rgba(255,0,0,0.6);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .beneficio:hover::before {
            transform: scaleX(1);
        }

        .beneficio .icone {
            width: 75px;
            height: 75px;
            margin: 0 auto 23px;
            border-radius: 15px;
            background: rgba(255,0,0,0.1);
            border: 1px solid rgba(255,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transicao);
        }

        .beneficio:hover .icone {
            background: var(--vermelho);
            transform: rotate(-5deg);
        }

        .beneficio i {
            font-size: 31px;
            color: var(--vermelho);
            transition: var(--transicao);
        }

        .beneficio:hover i {
            color: #fff;
        }

        .beneficio h3 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .beneficio p {
            color: #aaaaaa;
            font-size: 13px;
            line-height: 1.8;
        }

        

        .chamada {
            position: relative;
            padding: 110px 25px;
            text-align: center;
            background:
                linear-gradient(
                    rgba(0,0,0,0.82),
                    rgba(0,0,0,0.9)
                ),
                url("https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=1600&q=80")
                center/cover no-repeat;
            border-top: 1px solid #222;
        }

        .chamada-conteudo {
            max-width: 800px;
            margin: auto;
        }

        .chamada .subtitulo {
            display: block;
            color: var(--vermelho);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 3px;
            margin-bottom: 18px;
            text-transform: uppercase;
        }

        .chamada h2 {
            font-size: clamp(32px, 5vw, 49px);
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 22px;
            line-height: 1.2;
        }

        .chamada h2 span {
            color: var(--vermelho);
        }

        .chamada p {
            max-width: 680px;
            margin: 0 auto 35px;
            color: #dddddd;
            font-size: 15px;
            line-height: 1.9;
        }

        .botao-whatsapp {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 17px 30px;
            background: #25D366;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 800;
            transition: var(--transicao);
            box-shadow: 0 8px 25px rgba(37,211,102,0.15);
        }

        .botao-whatsapp i {
            font-size: 21px;
        }

        .botao-whatsapp:hover {
            background: #1ebe5d;
            color: #fff;
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(37,211,102,0.25);
        }


        @keyframes aparecer {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .titulo,
        .secao-titulo {
            animation: aparecer 0.7s ease both;
        }

        

        @media (max-width: 1000px) {

            .sobre-container {
                gap: 40px;
            }

            .beneficios-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .card {
                height: 480px;
            }

            .conteudo {
                left: 50px;
                right: 50px;
            }
        }

        @media (max-width: 768px) {

            .carrossel {
                padding: 55px 15px 50px;
            }

            .titulo {
                margin-bottom: 30px;
            }

            .titulo h1 {
                font-size: 11px;
                letter-spacing: 2px;
            }

            .titulo h1::before,
            .titulo h1::after {
                width: 18px;
            }

            .titulo p {
                font-size: 13px;
            }

            .slider {
                border-radius: 13px;
            }

            .card {
                height: 430px;
            }

            .card::after {
                background: linear-gradient(
                    to top,
                    rgba(0,0,0,0.95),
                    rgba(0,0,0,0.1)
                );
            }

            .conteudo {
                left: 25px;
                right: 25px;
                bottom: 40px;
            }

            .conteudo .categoria {
                font-size: 9px;
                padding: 7px 11px;
                margin-bottom: 13px;
            }

            .conteudo h3 {
                font-size: 32px;
                margin-bottom: 12px;
            }

            .conteudo p {
                font-size: 13px;
                line-height: 1.7;
            }

            .seta {
                width: 38px;
                height: 38px;
                font-size: 13px;
            }

            .anterior {
                left: 10px;
            }

            .proximo {
                right: 10px;
            }

            .sobre {
                padding: 65px 20px;
            }

            .sobre-container {
                grid-template-columns: 1fr;
                gap: 35px;
            }

            .sobre-imagem {
                height: 360px;
            }

            .sobre-texto h2 {
                font-size: 32px;
            }

            .sobre-texto p {
                font-size: 14px;
            }

            .beneficios {
                padding: 70px 20px;
            }

            .beneficios-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .beneficio {
                padding: 28px 17px;
            }

            .beneficio h3 {
                font-size: 16px;
            }

            .beneficio p {
                font-size: 12px;
            }

            .beneficio .icone {
                width: 62px;
                height: 62px;
            }

            .beneficio i {
                font-size: 25px;
            }

            .chamada {
                padding: 75px 20px;
            }

            .chamada p {
                font-size: 14px;
            }

        }

        @media (max-width: 480px) {

            .titulo h2 {
                font-size: 27px;
            }

            .card {
                height: 390px;
            }

            .conteudo {
                left: 20px;
                right: 20px;
                bottom: 30px;
            }

            .conteudo h3 {
                font-size: 27px;
            }

            .conteudo p {
                font-size: 12px;
            }

            .sobre-imagem {
                height: 290px;
            }

            .sobre-destaque {
                left: 12px;
                bottom: 12px;
                padding: 12px 15px;
            }

            .sobre-destaque i {
                font-size: 20px;
            }

            .sobre-destaque strong {
                font-size: 11px;
            }

            .sobre-destaque small {
                font-size: 10px;
            }

            .beneficios-grid {
                grid-template-columns: 1fr;
            }

            .beneficio {
                padding: 30px 25px;
            }

            .secao-titulo h2 {
                font-size: 27px;
            }

            .chamada h2 {
                font-size: 30px;
            }

            .botao-whatsapp {
                width: 100%;
                padding: 16px 15px;
                font-size: 12px;
            }

        }

        /

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }

        }

    </style>

</head>

<body>

    

    <section class="carrossel" aria-label="Modalidades da academia">

        <div class="titulo">

            <h1>SEJA BEM-VINDO!</h1>

            <h2>
                Conheça nossos <span>treinos</span>
            </h2>

            <p>
                Descubra novas modalidades e encontre o treino ideal para seus objetivos.
            </p>

        </div>

        <div class="slider" id="slider">

            <div class="slides" id="slides" aria-live="polite">

                <div class="card ativo">

                    <img
                        src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=1400&q=80"
                        alt="Pessoa realizando exercício de musculação"
                        fetchpriority="high"
                    >

                    <div class="conteudo">

                        <span class="categoria">Força e resistência</span>

                        <h3>Musculação</h3>

                        <p>
                            Fortaleça e desenvolva seus músculos com exercícios
                            focados em força, resistência e hipertrofia muscular.
                        </p>

                    </div>

                </div>

                <div class="card">

                    <img
                        src="https://images.unsplash.com/photo-1538805060514-97d9cc17730c?auto=format&fit=crop&w=1400&q=80"
                        alt="Pessoa praticando exercício cardiovascular"
                        loading="lazy"
                    >

                    <div class="conteudo">

                        <span class="categoria">Saúde e condicionamento</span>

                        <h3>Cardio</h3>

                        <p>
                            Melhore seu condicionamento físico, resistência e
                            capacidade cardiovascular através de treinos dinâmicos.
                        </p>

                    </div>

                </div>

                <div class="card">

                    <img
                        src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1400&q=80"
                        alt="Ambiente de treinamento funcional e intenso"
                        loading="lazy"
                    >

                    <div class="conteudo">

                        <span class="categoria">Desafie seus limites</span>

                        <h3>Cross Training</h3>

                        <p>
                            Combine força, velocidade, resistência e potência
                            em treinos intensos e variados para desafiar seus limites.
                        </p>

                    </div>

                </div>

                <div class="card">

                    <img
                        src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1400&q=80"
                        alt="Pessoa realizando treinamento físico"
                        loading="lazy"
                    >

                    <div class="conteudo">

                        <span class="categoria">Movimento e equilíbrio</span>

                        <h3>Funcional</h3>

                        <p>
                            Desenvolva equilíbrio, mobilidade, coordenação e força
                            utilizando movimentos que ajudam no desempenho do dia a dia.
                        </p>

                    </div>

                </div>

            </div>

            <button
                type="button"
                class="seta anterior"
                id="anterior"
                aria-label="Slide anterior"
            >
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <button
                type="button"
                class="seta proximo"
                id="proximo"
                aria-label="Próximo slide"
            >
                <i class="fa-solid fa-chevron-right"></i>
            </button>

        </div>

        <div class="indicadores" id="indicadores" aria-label="Selecionar modalidade">

            <button class="indicador ativo" type="button" aria-label="Musculação" aria-current="true"></button>

            <button class="indicador" type="button" aria-label="Cardio"></button>

            <button class="indicador" type="button" aria-label="Cross Training"></button>

            <button class="indicador" type="button" aria-label="Funcional"></button>

        </div>

    </section>


    

    <section class="sobre">

        <div class="sobre-container">

            <div class="sobre-imagem">

                <img
                    src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1200&q=80"
                    alt="Estrutura e equipamentos da academia"
                    loading="lazy"
                >

                <div class="sobre-destaque">

                    <i class="fa-solid fa-dumbbell"></i>

                    <div>
                        <strong>Seu melhor treino</strong>
                        <small>Começa com uma escolha.</small>
                    </div>

                </div>

            </div>

            <div class="sobre-texto">

                <span class="subtitulo">Conheça nossa estrutura</span>

                <h2>
                    Sobre a <span>Academia</span>
                </h2>

                <p>
                    Nossa academia foi criada para ajudar você a alcançar
                    seus objetivos e transformar sua rotina através da
                    atividade física.
                </p>

                <p>
                    Contamos com um ambiente moderno, equipamentos de
                    qualidade e profissionais preparados para acompanhar
                    sua evolução.
                </p>

                <p>
                    Seja para ganhar massa muscular, perder peso, melhorar
                    seu condicionamento ou simplesmente ter mais qualidade
                    de vida, aqui você encontra o treino ideal para você.
                </p>

                <ul class="sobre-lista">

                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        Equipamentos de qualidade
                    </li>

                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        Acompanhamento profissional
                    </li>

                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        Ambiente preparado para sua evolução
                    </li>

                </ul>

                <a href="#contato" class="botao">
                    Quero conhecer
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

        </div>

    </section>


    

    <section class="beneficios">

        <div class="secao-titulo">

            <span class="subtitulo">Nosso diferencial</span>

            <h2>
                Por que treinar <span>conosco?</span>
            </h2>

            <p>
                Tudo o que você precisa para evoluir está aqui.
            </p>

        </div>

        <div class="beneficios-grid">

            <div class="beneficio">

                <div class="icone">
                    <i class="fa-solid fa-dumbbell"></i>
                </div>

                <h3>Equipamentos</h3>

                <p>
                    Equipamentos modernos para proporcionar segurança,
                    conforto e melhores resultados durante seus treinos.
                </p>

            </div>

            <div class="beneficio">

                <div class="icone">
                    <i class="fa-solid fa-user-tie"></i>
                </div>

                <h3>Profissionais</h3>

                <p>
                    Profissionais preparados para orientar seus exercícios
                    e ajudar você a alcançar seus objetivos.
                </p>

            </div>

            <div class="beneficio">

                <div class="icone">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>

                <h3>Qualidade de Vida</h3>

                <p>
                    Treine para cuidar do corpo, melhorar sua saúde e
                    conquistar mais disposição para o seu dia.
                </p>

            </div>

            <div class="beneficio">

                <div class="icone">
                    <i class="fa-solid fa-fire"></i>
                </div>

                <h3>Motivação</h3>

                <p>
                    Um ambiente preparado para manter você motivado
                    durante toda sua jornada.
                </p>

            </div>

            <div class="beneficio">

                <div class="icone">
                    <i class="fa-solid fa-users"></i>
                </div>

                <h3>Ambiente</h3>

                <p>
                    Um espaço agradável para você treinar, evoluir
                    e fazer parte de uma comunidade.
                </p>

            </div>

            <div class="beneficio">

                <div class="icone">
                    <i class="fa-solid fa-trophy"></i>
                </div>

                <h3>Resultados</h3>

                <p>
                    Defina suas metas, mantenha o foco e acompanhe
                    sua evolução a cada treino.
                </p>

            </div>

        </div>

    </section>


    

    <section class="chamada" id="contato">

        <div class="chamada-conteudo">

            <span class="subtitulo">Sua evolução começa aqui</span>

            <h2>
                Pronto para <span>começar?</span>
            </h2>

            <p>
                Não espere mais para cuidar da sua saúde e transformar
                seus objetivos em realidade. Venha conhecer nossa academia
                e faça parte da nossa equipe.
            </p>

            <a
                href="https://wa.me/55992290181"
                target="_blank"
                rel="noopener noreferrer"
                class="botao-whatsapp"
            >

                <i class="fa-brands fa-whatsapp"></i>

                Fale conosco pelo WhatsApp

            </a>

        </div>

    </section>



    <script>

        document.addEventListener("DOMContentLoaded", function () {

            const slidesContainer = document.getElementById("slides");
            const slider = document.getElementById("slider");

            const cards = document.querySelectorAll(".card");
            const indicadores = document.querySelectorAll(".indicador");

            const anterior = document.getElementById("anterior");
            const proximo = document.getElementById("proximo");

            let slideAtual = 0;
            let intervalo = null;

            const tempo = 5000;
            const totalSlides = cards.length;

            const movimentoReduzido = window.matchMedia(
                "(prefers-reduced-motion: reduce)"
            ).matches;

            function atualizarSlide() {

                slidesContainer.style.transform =
                    `translateX(-${slideAtual * 100}%)`;

                cards.forEach((card, index) => {

                    card.classList.toggle(
                        "ativo",
                        index === slideAtual
                    );

                    card.setAttribute(
                        "aria-hidden",
                        index !== slideAtual
                    );

                });

                indicadores.forEach((indicador, index) => {

                    indicador.classList.toggle(
                        "ativo",
                        index === slideAtual
                    );

                    if (index === slideAtual) {

                        indicador.setAttribute("aria-current", "true");

                    } else {

                        indicador.removeAttribute("aria-current");

                    }

                });

            }

            function irParaSlide(numero) {

                slideAtual = (numero + totalSlides) % totalSlides;

                atualizarSlide();

                reiniciarIntervalo();

            }

            function mudarSlide(direcao) {

                irParaSlide(slideAtual + direcao);

            }

            function iniciarIntervalo() {

                if (movimentoReduzido || document.hidden) {
                    return;
                }

                pararIntervalo();

                intervalo = setInterval(function () {

                    slideAtual = (slideAtual + 1) % totalSlides;

                    atualizarSlide();

                }, tempo);

            }

            function pararIntervalo() {

                if (intervalo !== null) {

                    clearInterval(intervalo);

                    intervalo = null;

                }

            }

            function reiniciarIntervalo() {

                pararIntervalo();

                iniciarIntervalo();

            }

            anterior.addEventListener("click", function () {

                mudarSlide(-1);

            });

            proximo.addEventListener("click", function () {

                mudarSlide(1);

            });

            indicadores.forEach((indicador, index) => {

                indicador.addEventListener("click", function () {

                    irParaSlide(index);

                });

            });

            slider.addEventListener("mouseenter", pararIntervalo);

            slider.addEventListener("mouseleave", iniciarIntervalo);

            slider.addEventListener("focusin", pararIntervalo);

            slider.addEventListener("focusout", iniciarIntervalo);

            document.addEventListener("visibilitychange", function () {

                if (document.hidden) {

                    pararIntervalo();

                } else {

                    iniciarIntervalo();

                }

            });

            atualizarSlide();

            iniciarIntervalo();

        });

    </script>

</body>

</html>
