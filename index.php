<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>INÍCIO | Academia</title>
    <?php include 'includes_site/head.php'; ?>
</head>

<body>

    <?php include 'includes_site/header.php'; ?>

    <?php include 'includes_site/inicio.php'; ?>

    <?php include 'includes_site/footer.php'; ?>

</body>
</html>
