<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "cms";

$conn = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conn) {
    error_log("Erro na conexão com banco: " . mysqli_connect_error());

    die("Erro interno ao conectar com o banco de dados.");
}