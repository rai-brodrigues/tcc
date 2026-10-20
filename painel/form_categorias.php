
<!DOCTYPE html>
<html lang="en">
    <head>
        <title> INICIO | Terceirão 3K26 Play </title>
        <!------------ HEAD -------------->
        <?php include 'includes_painel/head.php'; ?>
        <!-- ------------------------- -->
    </head>
    <body>
        <!------------ HEADER -------------->
        <?php include 'includes_painel/header.php'; ?>
        <!-- ------------------------- -->

        <h1>  ESSA E A PAGINA DE Formulario </h1>

    <form id="formID" name="formID" action="cad_categorias.php" method="POST">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Nome da Categoria</label>
            <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" placeholder="ação">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Sobre a Categoria</label>
            <textarea class="form-control" id="info_categoria" name="info_categoria" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">
        CADASTRAR
        </button>
        <button type="button" class="btn btn-primary">
         CANCELAR </button>
    </form>
        <!------------ FOOTER -------------->
        <?php include 'includes_painel/footer.php'; ?>
        <!-- ------------------------- -->
    </body>
</html>