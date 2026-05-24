<?php
require_once("../includes/trava.php");
require_once("../config/database.php");

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$comentario = trim($_POST['comentario']);
$usuario_id = $_SESSION["usuario_id"];

if ($post_id && $comentario) {
    $sql = "INSERT INTO comentarios (post_id, usuario_id, comentario) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $post_id, $usuario_id, $comentario);
    mysqli_stmt_execute($stmt);
}

header("Location: ../admin/visualizar-post.php?id=" . $post_id);
exit;