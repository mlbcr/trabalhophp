<?php
// Inicia a sessão se ainda não tiver sido iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se o usuário não tá conectado, ele é enviado diretamente para a página de login
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../login.php");
    exit;
}
?>