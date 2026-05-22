<?php
session_start();
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

// Recebe o dado único do input híbrido
$login_input = trim($_POST["login_input"] ?? "");
$senha = trim($_POST["senha"] ?? "");

if (empty($login_input) || empty($senha)) {
    header("Location: ../login.php?erro=1");
    exit;
}

$sql = "SELECT id, nome, senha FROM usuarios WHERE email = ? OR username = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $login_input, $login_input);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if ($user = mysqli_fetch_assoc($resultado)) {
        if (password_verify($senha, $user["senha"])) {
            $_SESSION["usuario_id"] = $user["id"];
            $_SESSION["usuario_nome"] = $user["nome"];
            
            header("Location: ../admin/index.php");
            exit;
        }
    }
}

header("Location: ../login.php?erro=1");
exit;