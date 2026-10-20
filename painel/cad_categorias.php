<?php
include('verifica_login.php');
include('conexao.php');

$nome_categoria = $_POST['nome_categoria'];
$info_categoria = $_POST['info_categoria'];

$sql_insert ="INSERT INTO categorias (nome_categoria, info_categoria, dt_cad_categoria) VALUES ('$nome_categoria', '$info_categoria', NOW() )";

mysqli_query($conexao, $sql_insert);

?>