<?php
// 1. Adicionamos a trava para garantir que apenas usuários logados votem
require_once("../includes/trava.php");
require_once("../config/database.php");

// 2. Validação básica de entrada
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$tipo = $_POST['tipo'] ?? ''; // 'like' ou 'dislike'
$usuario_id = $_SESSION["usuario_id"];

// 3. Validação de segurança dos dados recebidos
if (!$post_id || !in_array($tipo, ['like', 'dislike'])) {
    http_response_code(400); 
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos"]);
    exit;
}

// 4. Operação atômica no banco
$sql = "REPLACE INTO likes (post_id, usuario_id, tipo) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iis", $post_id, $usuario_id, $tipo);

if (mysqli_stmt_execute($stmt)) {
    // 5. Retorno de sucesso em formato JSON
    echo json_encode(["status" => "sucesso"]);
} else {
    http_response_code(500); 
    echo json_encode(["status" => "erro", "mensagem" => "Falha no banco de dados"]);
}
exit;