<?php
require_once("../includes/trava.php");
require_once("../config/database.php");

// Pega o id do comentário e remove espaços em branco
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$comentario = trim($_POST['comentario']);
$usuario_id = $_SESSION["usuario_id"];

// Verifica se as informações existem antes de botar o comentário no banco de dados
if ($post_id && $comentario) {
    $sql = "INSERT INTO comentarios (post_id, usuario_id, comentario) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Coloca as informações, insere no banco de dados e depois fecha
    mysqli_stmt_bind_param($stmt, "iis", $post_id, $usuario_id, $comentario);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Reload na página
header("Location: ../admin/visualizar-post.php?id=" . $post_id);
exit;