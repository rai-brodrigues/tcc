<?php
include('verifica_login.php');
include('conexao.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<?php include 'includes_painel/head.php'; ?>

</head>


<body>


<?php include 'includes_painel/header.php'; ?>


<h1>Jogos</h1>

<div class="form-group  col-8" >
 
<div class="card" style="width: 18rem;">

    <img src="./img.jfif" 
         class="card-img-top"
         alt="Venge">


    <div class="card-body">

        <h5 class="card-title">
            Venge
        </h5>


        <p class="card-text">
            <div class="row g-3"> 
            <b>Categoria:</b> Jogo de Ação

            <br><br>

            <b>Descrição:</b>

            Venge.io é um jogo de tiro em primeira pessoa (FPS) 
            multijogador online e gratuito, que funciona direto 
            no navegador de internet ou em dispositivos móveis. 
            Ele mistura combates rápidos em arena com mecânicas 
            de captura de pontos e uso de cartas de habilidades especiais.

        </p>


        <a href="https://venge.io/" 
           class="btn btn-primary"
           target="_blank">

            Jogar Venge

        </a>
    </div>
       
    </div>

</div>



<?php include 'includes_painel/footer.php'; ?>


</body>

</html>
