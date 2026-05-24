<?php
require_once("../includes/trava.php");
require_once("../config/database.php");

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$titulo = $_POST['titulo'];
$resumo = $_POST['resumo'];
$conteudo = $_POST['conteudo']; // Contém o HTML do Quill

$sql = "UPDATE posts SET titulo = ?, resumo = ?, conteudo = ? WHERE id = ? AND usuario_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssii", $titulo, $resumo, $conteudo, $id, $_SESSION['usuario_id']);
mysqli_stmt_execute($stmt);

header("Location: ../admin/visualizar-post.php?id=" . $id);
exit;