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

$sql_autor = "SELECT nome, foto, banner, biografia FROM usuarios WHERE id = ?";
$stmt_autor = mysqli_prepare($conn, $sql_autor);
mysqli_stmt_bind_param($stmt_autor, "i", $post['usuario_id']);
mysqli_stmt_execute($stmt_autor);
$autor = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_autor));
mysqli_stmt_close($stmt_autor);
?>

<main class="conteudo-admin" style="display: grid; grid-template-columns: 300px 1fr; gap: 40px; align-items: start;">
    
    <!-- Sidebar do Perfil -->
    <aside class="sidebar-autor" style="background: #111827; border: 1px solid #1e293b; border-radius: 20px; overflow: hidden;">
        <div style="height: 100px; background: #334155; background-image: url('<?= !empty($autor['banner']) ? htmlspecialchars($autor['banner']) : ''; ?>'); background-size: cover; background-position: center;"></div>
        
        <div style="padding: 0 20px 20px 20px; text-align: center;">
            <div style="width: 80px; height: 80px; background: #2563eb; border-radius: 50%; margin: -40px auto 16px auto; border: 4px solid #111827; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px; overflow: hidden;">
                <?php if (!empty($autor['foto'])): ?>
                    <img src="<?= htmlspecialchars($autor['foto']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <?= strtoupper(substr($autor['nome'], 0, 1)); ?>
                <?php endif; ?>
            </div>
            <h3 style="color: #f8fafc; margin-bottom: 4px;"><?= htmlspecialchars($autor['nome']); ?></h3>
            <p style="color: #94a3b8; font-size: 13px; margin-bottom: 16px;">@<?= htmlspecialchars($post['username']); ?></p>
            <p style="color: #cbd5e1; font-size: 14px; line-height: 1.5; text-align: left; border-top: 1px solid #1e293b; padding-top: 16px;">
                <?= !empty($autor['biografia']) ? htmlspecialchars($autor['biografia']) : "Este autor não possui biografia."; ?>
            </p>
            <a href="perfil.php?u=<?= urlencode($post['username']); ?>" class="botao-primario" style="width: 100%; justify-content: center; margin-top: 16px; padding: 10px;">Ver Perfil</a>
        </div>
    </aside>

    <!-- Artigo -->
    <article class="artigo-container" style="margin: 0; max-width: none;">
        <header class="artigo-header">
            <div class="artigo-meta">
                <span class="artigo-data"><?= date('d/m/Y às H:i', strtotime($post['data_publicacao'])); ?></span>
            </div>
            <h1 class="artigo-titulo"><?= htmlspecialchars($post['titulo']); ?></h1>
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