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

// CORREÇÃO 1: Adicionado 'username' e 'foto' no SELECT
$sql = "SELECT id, nome, username, senha, foto FROM usuarios WHERE email = ? OR username = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $login_input, $login_input);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if ($user = mysqli_fetch_assoc($resultado)) {
        if (password_verify($senha, $user["senha"])) {
            // CORREÇÃO 2: Salvando todas as informações necessárias na sessão
            $_SESSION["usuario_id"]       = $user["id"];
            $_SESSION["usuario_nome"]     = $user["nome"];
            $_SESSION["usuario_username"] = $user["username"];
            $_SESSION["usuario_foto"]     = $user["foto"]; 
            
            header("Location: ../admin/index.php");
            exit;
        }
    }
}

// Se o usuário não existir ou a senha estiver incorreta
header("Location: ../login.php?erro=1");
exit;