<?php
require_once("../includes/trava.php");
require_once("../config/database.php");

// Pega as informações do post
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$titulo = trim($_POST['titulo'] ?? '');
$resumo = trim($_POST['resumo'] ?? '');
$conteudo = trim($_POST['conteudo'] ?? '');

// Se o post não existir, dá erro
if (empty($id) || empty($titulo) || empty($conteudo)) {
    header("Location: ../admin/editar-post.php?id=$id&erro=campos_vazios");
    exit;
}

// Depois de atualizar, pega as novas informações e envia ao banco de dados
$sql = "UPDATE posts SET titulo = ?, resumo = ?, conteudo = ? WHERE id = ? AND usuario_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssii", $titulo, $resumo, $conteudo, $id, $_SESSION['usuario_id']);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/visualizar-post.php?id=$id&sucesso=1");
} else {
    header("Location: ../admin/editar-post.php?id=$id&erro=db_falha");
}
exit;