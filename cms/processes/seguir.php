<?php
require_once("../includes/trava.php");
require_once("../config/database.php");

// 1. Alterar dados
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); 
    exit;
}

$seguidor_id = $_SESSION["usuario_id"];
$seguido_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$acao = $_POST['acao'] ?? ''; // 'seguir' ou 'deixar'

// 2. Validação de segurança
if ($seguido_id <= 0 || $seguido_id == $seguidor_id || !in_array($acao, ['seguir', 'deixar'])) {
    http_response_code(400);
    echo json_encode(["status" => "erro", "mensagem" => "Ação inválida"]);
    exit;
}

// 3. Execução segura
if ($acao === 'seguir') {
    $sql = "INSERT IGNORE INTO seguidores (seguidor_id, seguido_id) VALUES (?, ?)";
} else {
    $sql = "DELETE FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?";
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $seguidor_id, $seguido_id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => "sucesso", "acao" => $acao]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "erro", "mensagem" => "Falha na comunicação com o banco"]);
}
exit;