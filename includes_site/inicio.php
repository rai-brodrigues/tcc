<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Academia - Modalidades</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <style>

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      background: #111;
      color: #fff;
    }

    

    .contato-topo {
      width: 100%;
      background: #000;
      border-bottom: 1px solid #222;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 12px;
      padding: 12px 40px;
    }

    .contato-topo a {
      width: 42px;
      height: 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-decoration: none;
      border-radius: 50%;
      font-size: 20px;
      transition: 0.3s;
    }

    .instagram:hover {
      background: #E1306C;
      transform: translateY(-3px);
    }

    .whatsapp:hover {
      background: #25D366;
      transform: translateY(-3px);
    }

    .email:hover {
      background: #ff3c00;
      transform: translateY(-3px);
    }




    .carrossel {
      width: 100%;
      max-width: 1200px;
      margin: 40px auto 60px;
      padding: 0 20px;
      position: relative;
    }

    .titulo {
      text-align: center;
      margin-bottom: 30px;
    }

    .titulo h1 {
      font-size: 28px;
      margin-bottom: 10px;
      color: #fff;
    }

    .titulo h2 {
      font-size: 36px;
      text-transform: uppercase;
      color: #fff;
    }

    .titulo span {
      color: #ff3c00;
    }

    .slider {
      overflow: hidden;
      border-radius: 20px;
    }

    .slides {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    .card {
      min-width: 100%;
      position: relative;
      height: 500px;
      overflow: hidden;
    }

    .card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .card::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(
        to top,
        rgba(0,0,0,0.9),
        rgba(0,0,0,0.15)
      );
    }

    .conteudo {
      position: absolute;
      z-index: 2;
      bottom: 45px;
      left: 45px;
      max-width: 600px;
      color: #fff;
    }

    .conteudo h3 {
      font-size: 42px;
      margin-bottom: 15px;
      text-transform: uppercase;
      color: #fff;
    }

    .conteudo p {
      font-size: 18px;
      line-height: 1.6;
      color: #fff;
    }




    .seta {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 5;
      width: 50px;
      height: 50px;
      border: 2px solid #fff;
      border-radius: 50%;
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      font-size: 25px;
      cursor: pointer;
      transition: 0.3s;
    }

    .seta:hover {
      background: #fff;
      color: #111;
    }

    .anterior {
      left: 30px;
    }

    .proximo {
      right: 30px;
    }


  

    .indicadores {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20px;
    }

    .indicador {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      border: none;
      background: #555;
      cursor: pointer;
    }

    .indicador.ativo {
      background: #ff3c00;
      transform: scale(1.2);
    }


   

    .sobre {
      width: 100%;
      background: #0b0b0b;
      padding: 80px 20px;
      border-top: 1px solid #222;
    }

    .sobre-container {
      max-width: 1200px;
      margin: auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 50px;
      align-items: center;
    }

    .sobre-imagem {
      position: relative;
      height: 500px;
      overflow: hidden;
      border-radius: 20px;
    }

    .sobre-imagem img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: 0.5s;
    }

    .sobre-imagem:hover img {
      transform: scale(1.05);
    }

    .sobre-texto h2 {
      font-size: 42px;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .sobre-texto h2 span {
      color: #ff3c00;
    }

    .sobre-texto p {
      color: #ccc;
      font-size: 17px;
      line-height: 1.8;
      margin-bottom: 18px;
    }

    .botao {
      display: inline-block;
      margin-top: 15px;
      padding: 14px 28px;
      background: #ff3c00;
      color: #fff;
      text-decoration: none;
      font-weight: bold;
      text-transform: uppercase;
      border-radius: 8px;
      transition: 0.3s;
    }

    .botao:hover {
      background: #fff;
      color: #111;
      transform: translateY(-3px);
    }


   

    .beneficios {
      padding: 80px 20px;
      background: #111;
    }

    .secao-titulo {
      text-align: center;
      margin-bottom: 50px;
    }

    .secao-titulo h2 {
      font-size: 38px;
      text-transform: uppercase;
      margin-bottom: 12px;
    }

    .secao-titulo h2 span {
      color: #ff3c00;
    }

    .secao-titulo p {
      color: #aaa;
      font-size: 17px;
    }

    .beneficios-grid {
      max-width: 1200px;
      margin: auto;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 25px;
    }

    .beneficio {
      background: #1a1a1a;
      padding: 35px 25px;
      text-align: center;
      border-radius: 15px;
      border: 1px solid #292929;
      transition: 0.3s;
    }

    .beneficio:hover {
      transform: translateY(-8px);
      border-color: #ff3c00;
      box-shadow: 0 10px 30px rgba(255, 60, 0, 0.15);
    }

    .beneficio i {
      font-size: 45px;
      color: #ff3c00;
      margin-bottom: 20px;
    }

    .beneficio h3 {
      font-size: 22px;
      margin-bottom: 12px;
    }

    .beneficio p {
      color: #aaa;
      line-height: 1.6;
    }


    

    .chamada {
      padding: 100px 20px;
      text-align: center;
      background:
        linear-gradient(
          rgba(0,0,0,0.75),
          rgba(0,0,0,0.85)
        ),
        url("https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=1600&q=80")
        center/cover;
    }

    .chamada h2 {
      font-size: 45px;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .chamada h2 span {
      color: #ff3c00;
    }

    .chamada p {
      max-width: 700px;
      margin: 0 auto 30px;
      color: #ddd;
      font-size: 18px;
      line-height: 1.7;
    }

    .botao-whatsapp {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 16px 30px;
      background: #25D366;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      font-size: 17px;
      font-weight: bold;
      transition: 0.3s;
    }

    .botao-whatsapp:hover {
      background: #1ebe5d;
      transform: translateY(-3px);
    }


   

    footer {
      background: #000;
      padding: 30px 20px;
      text-align: center;
      color: #777;
      border-top: 1px solid #222;
    }

    footer span {
      color: #ff3c00;
    }




    @media (max-width: 768px) {

      .contato-topo {
        justify-content: center;
        padding: 10px;
      }

      .contato-topo a {
        width: 38px;
        height: 38px;
        font-size: 18px;
      }

      .carrossel {
        margin: 30px auto;
      }

      .titulo h1 {
        font-size: 23px;
      }

      .titulo h2 {
        font-size: 28px;
      }

      .card {
        height: 450px;
      }

      .conteudo {
        left: 25px;
        right: 25px;
        bottom: 30px;
      }

      .conteudo h3 {
        font-size: 30px;
      }

      .conteudo p {
        font-size: 16px;
      }

      .seta {
        width: 40px;
        height: 40px;
        font-size: 20px;
      }

      .anterior {
        left: 15px;
      }

      .proximo {
        right: 15px;
      }

      .sobre {
        padding: 60px 20px;
      }

      .sobre-container {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .sobre-imagem {
        height: 350px;
      }

      .sobre-texto h2 {
        font-size: 32px;
      }

      .beneficios {
        padding: 60px 20px;
      }

      .beneficios-grid {
        grid-template-columns: 1fr;
      }

      .secao-titulo h2 {
        font-size: 30px;
      }

      .chamada {
        padding: 70px 20px;
      }

      .chamada h2 {
        font-size: 32px;
      }

      .chamada p {
        font-size: 16px;
      }

    }

  </style>
</head>

<body>

  <section class="carrossel">

    <div class="titulo">

      <h1>SEJA BEM VINDO!</h1>

      <h2>
        Conheça nossos <span>treinos</span>
      </h2>

    </div>


    <div class="slider">

      <div class="slides" id="slides">



        <div class="card">

          <img
            src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=1400&q=80"
            alt="Treino de musculação"
          >

          <div class="conteudo">

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
            alt="Treino cardio"
          >

          <div class="conteudo">

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
            alt="Cross Training"
          >

          <div class="conteudo">

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
            alt="Treino funcional"
          >

          <div class="conteudo">

            <h3>Funcional</h3>

            <p>
              Desenvolva equilíbrio, mobilidade, coordenação e força
              utilizando movimentos que ajudam no desempenho do dia a dia.
            </p>

          </div>

        </div>

      </div>

    </div>



    <button
      class="seta anterior"
      onclick="mudarSlide(-1)"
      aria-label="Slide anterior"
    >
      &#10094;
    </button>

    <button
      class="seta proximo"
      onclick="mudarSlide(1)"
      aria-label="Próximo slide"
    >
      &#10095;
    </button>



    <div class="indicadores">

      <button
        class="indicador ativo"
        onclick="irParaSlide(0)"
        aria-label="Ir para Musculação">
      </button>

      <button
        class="indicador"
        onclick="irParaSlide(1)"
        aria-label="Ir para Cardio">
      </button>

      <button
        class="indicador"
        onclick="irParaSlide(2)"
        aria-label="Ir para Cross Training">
      </button>

      <button
        class="indicador"
        onclick="irParaSlide(3)"
        aria-label="Ir para Funcional">
      </button>

    </div>

  </section>




  <section class="sobre">

    <div class="sobre-container">

      <div class="sobre-imagem">

        <img
          src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1200&q=80"
          alt="Academia e equipamentos"
        >

      </div>


      <div class="sobre-texto">

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

        <a href="#contato" class="botao">
          Quero conhecer
        </a>

      </div>

    </div>

  </section>



       

  <section class="beneficios">

    <div class="secao-titulo">

      <h2>
        Por que treinar <span>conosco?</span>
      </h2>

      <p>
        Tudo o que você precisa para evoluir está aqui.
      </p>

    </div>


    <div class="beneficios-grid">


      <div class="beneficio">

        <i class="fa-solid fa-dumbbell"></i>

        <h3>Equipamentos</h3>

        <p>
          Equipamentos modernos para proporcionar segurança,
          conforto e melhores resultados durante seus treinos.
        </p>

      </div>


      <div class="beneficio">

        <i class="fa-solid fa-user-tie"></i>

        <h3>Profissionais</h3>

        <p>
          Profissionais preparados para orientar seus exercícios
          e ajudar você a alcançar seus objetivos.
        </p>

      </div>


      <div class="beneficio">

        <i class="fa-solid fa-heart-pulse"></i>

        <h3>Qualidade de Vida</h3>

        <p>
          Treine para cuidar do corpo, melhorar sua saúde e
          conquistar mais disposição para o seu dia.
        </p>

      </div>


      <div class="beneficio">

        <i class="fa-solid fa-fire"></i>

        <h3>Motivação</h3>

        <p>
          Um ambiente preparado para manter você motivado
          durante toda sua jornada.
        </p>

      </div>


      <div class="beneficio">

        <i class="fa-solid fa-users"></i>

        <h3>Ambiente</h3>

        <p>
          Um espaço agradável para você treinar, evoluir
          e fazer parte de uma comunidade.
        </p>

      </div>


      <div class="beneficio">

        <i class="fa-solid fa-trophy"></i>

        <h3>Resultados</h3>

        <p>
          Defina suas metas, mantenha o foco e acompanhe
          sua evolução a cada treino.
        </p>

      </div>

    </div>

  </section>



 

  <section class="chamada" id="contato">

    <h2>
      Pronto para <span>começar?</span>
    </h2>

    <p>
      Não espere mais para cuidar da sua saúde e transformar
      seus objetivos em realidade. Venha conhecer nossa academia
      e faça parte da nossa equipe.
    </p>

    <a
      href="https://wa.me/5551999999999"
      target="_blank"
      class="botao-whatsapp"
    >

      <i class="fa-brands fa-whatsapp"></i>

      Fale conosco pelo WhatsApp

    </a>

  </section>



 

  <script>

    let slideAtual = 0;

    const slides = document.getElementById("slides");

    const indicadores =
      document.querySelectorAll(".indicador");


    function atualizarSlide() {

      slides.style.transform =
        `translateX(-${slideAtual * 100}%)`;


      indicadores.forEach((indicador, index) => {

        indicador.classList.toggle(
          "ativo",
          index === slideAtual
        );

      });

    }


    function mudarSlide(direcao) {

      slideAtual += direcao;


      if (slideAtual < 0) {
        slideAtual = 3;
      }


      if (slideAtual > 3) {
        slideAtual = 0;
      }


      atualizarSlide();

    }


    function irParaSlide(numero) {

      slideAtual = numero;

      atualizarSlide();

    }



    setInterval(() => {

      mudarSlide(1);

    }, 5000);

  </script>


</body>
</html>
