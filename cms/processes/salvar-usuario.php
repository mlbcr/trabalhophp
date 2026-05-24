<?php
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastrar.php");
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$username = strtolower(trim($_POST["username"] ?? "")); 
$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

if (empty($nome) || empty($username) || empty($email) || empty($senha)) {
    header("Location: ../cadastrar.php?erro=campos_vazios");
    exit;
}

$sql_user_check = "SELECT id FROM usuarios WHERE username = ? LIMIT 1";
$stmt_user = mysqli_prepare($conn, $sql_user_check);
if ($stmt_user) {
    mysqli_stmt_bind_param($stmt_user, "s", $username);
    mysqli_stmt_execute($stmt_user);
    mysqli_stmt_store_result($stmt_user);
    if (mysqli_stmt_num_rows($stmt_user) > 0) {
        header("Location: ../cadastrar.php?erro=username_existe");
        exit;
    }
    mysqli_stmt_close($stmt_user);
}

$sql_email_check = "SELECT id FROM usuarios WHERE email = ? LIMIT 1";
$stmt_email = mysqli_prepare($conn, $sql_email_check);
if ($stmt_email) {
    mysqli_stmt_bind_param($stmt_email, "s", $email);
    mysqli_stmt_execute($stmt_email);
    mysqli_stmt_store_result($stmt_email);
    if (mysqli_stmt_num_rows($stmt_email) > 0) {
        header("Location: ../cadastrar.php?erro=email_existe");
        exit;
    }
    mysqli_stmt_close($stmt_email);
}

$senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

$sql_insert = "INSERT INTO usuarios (nome, username, email, senha) VALUES (?, ?, ?, ?)";
$stmt_insert = mysqli_prepare($conn, $sql_insert);

if ($stmt_insert) {
    mysqli_stmt_bind_param($stmt_insert, "ssss", $nome, $username, $email, $senha_criptografada);
    $sucesso = mysqli_stmt_execute($stmt_insert);
    mysqli_stmt_close($stmt_insert);
    
    if ($sucesso) {
        header("Location: ../cadastrar.php?sucesso=1");
        exit;
    }
}

header("Location: ../cadastrar.php?erro=geral");
exit;