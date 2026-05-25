<?php
require_once("../includes/trava.php"); 
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin/index.php");
    exit;
}

$titulo = trim($_POST["titulo"] ?? "");
$resumo = trim($_POST["resumo"] ?? "");
$conteudo = trim($_POST["conteudo"] ?? "");
$usuario_id = $_SESSION["usuario_id"]; 

$tags_array = $_POST['tags'] ?? [];
$tags_string = implode(',', $tags_array); 

if (empty($titulo) || empty($resumo) || empty($conteudo)) {
    header("Location: ../admin/criar-post.php?erro=campos_vazios");
    exit;
}

$data_atual = date("Y-m-d H:i:s");

$sql = "INSERT INTO posts (usuario_id, titulo, resumo, conteudo, data_publicacao, tags) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erro ao preparar consulta: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "isssss",
    $usuario_id,
    $titulo,
    $resumo,
    $conteudo,
    $data_atual,
    $tags_string
);

if (mysqli_stmt_execute($stmt)) {
    $novo_id = mysqli_insert_id($conn);
    header("Location: ../admin/visualizar-post.php?id=" . $novo_id);
    exit;
} else {
    header("Location: ../admin/criar-post.php?erro=db_falha");
    exit;
}