
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    header('Location: /tcc/login/index.php');
    exit;
}
