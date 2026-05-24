<?php
session_start();
require_once("../config/database.php");

$post_id = $_POST['post_id'];
$tipo = $_POST['tipo']; // 'like' ou 'dislike'
$usuario_id = $_SESSION['usuario_id'];

// Remove voto anterior (se existir) e insere o novo
$sql = "REPLACE INTO likes (post_id, usuario_id, tipo) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iis", $post_id, $usuario_id, $tipo);
mysqli_stmt_execute($stmt);

echo "sucesso";