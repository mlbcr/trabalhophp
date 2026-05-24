<?php
require_once("../includes/trava.php");
require_once("../config/database.php");

$id_post = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$id_logado = $_SESSION["usuario_id"];

if ($id_post) {
    // Deleta apenas se o post for do usuário logado
    $sql = "DELETE FROM posts WHERE id = ? AND usuario_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $id_post, $id_logado);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Refresh na página
$url_anterior = $_SERVER['HTTP_REFERER'] ?? '../admin/index.php';
header("Location: " . $url_anterior);
exit;
?>