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

if (empty($titulo) || empty($resumo) || empty($conteudo)) {
    die("Preencha todos os campos.");
}

$data_atual = date("Y-m-d H:i:s");

$sql = "INSERT INTO posts (
    usuario_id,
    titulo,
    resumo,
    conteudo,
    data_publicacao
) VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Erro ao preparar consulta.");
}

mysqli_stmt_bind_param(
    $stmt,
    "issss",
    $usuario_id,
    $titulo,
    $resumo,
    $conteudo,
    $data_atual
);

$sucesso = mysqli_stmt_execute($stmt);

if (!$sucesso) {
    die("Erro ao cadastrar post.");
}

mysqli_stmt_close($stmt);

header("Location: ../admin/index.php");
exit;