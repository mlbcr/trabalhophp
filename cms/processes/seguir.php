<?php
require_once("../includes/trava.php");
require_once("../config/database.php");

$seguidor_id = $_SESSION["usuario_id"];
$seguido_id = intval($_GET['id'] ?? 0);
$acao = $_GET['acao'] ?? '';

if ($seguido_id > 0 && $seguido_id != $seguidor_id) {
    if ($acao === 'seguir') {
        $sql = "INSERT IGNORE INTO seguidores (seguidor_id, seguido_id) VALUES (?, ?)";
    } elseif ($acao === 'deixar') {
        $sql = "DELETE FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?";
    }
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $seguidor_id, $seguido_id);
    mysqli_stmt_execute($stmt);
}

exit;
?>