<?php
require_once("../config/database.php");

// O script só é aceito se for através do formulário POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastrar.php");
    exit;
}

// Verifica as informações enviadas
$nome = trim($_POST["nome"] ?? "");
$username = strtolower(trim($_POST["username"] ?? "")); 
$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

// Caso estejam vazias, dá erro
if (empty($nome) || empty($username) || empty($email) || empty($senha)) {
    header("Location: ../cadastrar.php?erro=campos_vazios");
    exit;
}

// Verifica se existe um usuário com o mesmo nome e dá erro se existir
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

// Verifica se existe um e-mail igual e dá erro se existir
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

// Passa a senha para hash
$senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

// Faz a inserção
$sql_insert = "INSERT INTO usuarios (nome, username, email, senha) VALUES (?, ?, ?, ?)";
$stmt_insert = mysqli_prepare($conn, $sql_insert);

// Verifica se a inserção deu certo e informa o usuário do sucesso
if ($stmt_insert) {
    mysqli_stmt_bind_param($stmt_insert, "ssss", $nome, $username, $email, $senha_criptografada);
    $sucesso = mysqli_stmt_execute($stmt_insert);
    mysqli_stmt_close($stmt_insert);
    
    if ($sucesso) {
        header("Location: ../cadastrar.php?sucesso=1");
        exit;
    }
}

// Para caso de qualquer outro tipo de erro
header("Location: ../cadastrar.php?erro=geral");
exit;