<?php
require_once("../includes/trava.php"); 
require_once("../config/database.php");

include("../includes/header.php");

$id_post = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id_post) {
    header("Location: index.php");
    exit;
}

$sql = "SELECT posts.*, usuarios.username, usuarios.nome AS autor_nome 
        FROM posts 
        INNER JOIN usuarios ON posts.usuario_id = usuarios.id 
        WHERE posts.id = ? 
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_post);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $post = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);
}

if (!$post) {
    header("Location: index.php");
    exit;
}
?>

<main class="conteudo-admin">
    <div style="margin-bottom: 24px;">
        <a href="index.php" class="btn-voltar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Voltar para o feed
        </a>
    </div>

    <article class="artigo-container">
        <header class="artigo-header">
            <div class="artigo-meta">
                <span class="artigo-autor">@<?= htmlspecialchars($post['username']); ?></span>
                <span class="artigo-separador">•</span>
                <span class="artigo-data"><?= date('d/m/Y às H:i', strtotime($post['data_publicacao'])); ?></span>
            </div>
            
            <h1 class="artigo-titulo"><?= htmlspecialchars($post['titulo']); ?></h1>
            <p class="artigo-resumo"><?= htmlspecialchars($post['resumo']); ?></p>
        </header>

        <div class="artigo-conteudo">
            <?= nl2br(htmlspecialchars($post['conteudo'])); ?>
        </div>

        <?php if ($post['usuario_id'] == $_SESSION["usuario_id"]): ?>
            <footer class="artigo-footer-acoes">
                <a href="editar-post.php?id=<?= $post['id']; ?>" class="botao-editar-autor">Editar Publicação</a>
            </footer>
        <?php endif; ?>
    </article>
</main>

<?php include("../includes/footer.php"); ?>