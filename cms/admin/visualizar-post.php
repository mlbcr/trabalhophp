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

$sql_votos = "SELECT 
    SUM(CASE WHEN tipo = 'like' THEN 1 ELSE 0 END) as total_likes,
    SUM(CASE WHEN tipo = 'dislike' THEN 1 ELSE 0 END) as total_dislikes
    FROM likes WHERE post_id = ?";
$stmt = mysqli_prepare($conn, $sql_votos);
mysqli_stmt_bind_param($stmt, "i", $id_post);
mysqli_stmt_execute($stmt);
$votos = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$total = $votos['total_likes'] + $votos['total_dislikes'];
$porcentagem_likes = ($total > 0) ? ($votos['total_likes'] / $total) * 100 : 50;

date_default_timezone_set('America/Sao_Paulo');

?>

<style>
    .artigo-conteudo .ql-align-center { text-align: center; }
    .artigo-conteudo .ql-align-justify { text-align: justify; }
    .artigo-conteudo .ql-align-right { text-align: right; }
    
    /* Caso queira garantir que negrito e itálico também apareçam corretamente */
    .artigo-conteudo strong { font-weight: bold; }
    .artigo-conteudo em { font-style: italic; }
    .artigo-conteudo u { text-decoration: underline; }
</style>

<main class="conteudo-admin" style="display: grid; grid-template-columns: 300px 1fr; gap: 40px; align-items: start;">
    
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
    

<article class="artigo-container" style="margin: 0; max-width: none;">

        <?php if (isset($_GET['sucesso'])): ?>
            <div style="background: #064e3b; color: #d1fae5; padding: 16px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #065f46;">
                Artigo atualizado com sucesso!
            </div>
        <?php endif; ?>
        
        <header class="artigo-header" style="border-bottom: 1px solid #1e293b; margin-bottom: 24px; padding-bottom: 16px;">
            <div class="artigo-meta" style="color: #94a3b8; font-size: 14px; margin-bottom: 8px;">
                <span class="artigo-data"><?= date('d/m/Y \à\s H:i', strtotime($post['data_publicacao'])); ?></span>
            </div>
            <h1 class="artigo-titulo" style="font-size: 2.5rem; color: #f8fafc;"><?= htmlspecialchars($post['titulo']); ?></h1>
        </header>

        <div class="tags-exibicao">
            <?php 
            $lista_tags = explode(',', $post['tags']);
            foreach ($lista_tags as $tag): 
            if (!empty($tag)): ?>
                <span style="background: #2563eb; padding: 6px 10px; border-radius: 12px; font-size: 15px; color: white;">
                    <?= ucfirst($tag); ?>
                </span>
            <?php endif; endforeach; ?>
        </div>

        <div class="artigo-conteudo" style="font-size: 1.1rem; line-height: 1.8; color: #cbd5e1; margin-bottom: 40px;">
            <?= $post['conteudo']; ?>
        </div>

        <?php if ($post['usuario_id'] == $_SESSION["usuario_id"]): ?>
            <footer class="artigo-footer-acoes" style="margin-bottom: 40px; padding-top: 20px; border-top: 1px solid #1e293b;">
                <a href="editar-post.php?id=<?= $post['id']; ?>" class="botao-editar-autor">Editar Publicação</a>
            </footer>
        <?php endif; ?>

        <div class="container-votos" style="margin: 20px 0;">
            <div style="display: flex; height: 10px; border-radius: 5px; overflow: hidden; background: #ef4444;">
            <div style="width: <?= $porcentagem_likes ?>%; background: #22c55e; transition: 0.3s;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #94a3b8; margin-top: 5px;">
                <span><?= $votos['total_likes'] ?> Likes</span>
                <span><?= $votos['total_dislikes'] ?> Dislikes</span>
            </div>
    
        <div class="acoes-post" style="display: flex; gap: 10px; margin-bottom: 20px">
            <button class="post-like btn-like" onclick="votar(<?= $id_post ?>, 'like')">
                👍 Like
            </button>
    
            <button class="post-like btn-dislike" onclick="votar(<?= $id_post ?>, 'dislike')">
                👎 Dislike
            </button>
        </div>

        <section class="secao-comentarios" style="background: #1e293b; padding: 25px; border-radius: 16px; color: #f8fafc;">
            <h3 style="margin-bottom: 20px;">Comentários</h3>
            
            <form action="../processes/comentario.php" method="POST" style="margin-bottom: 30px;">
                <input type="hidden" name="post_id" value="<?= $id_post; ?>">
                <textarea name="comentario" rows="3" style="width: 100%; background: #334155; border: 1px solid #475569; color: white; padding: 12px; border-radius: 10px;" placeholder="Escreva um comentário..." required></textarea>
                <button type="submit" class="botao-primario" style="margin-top: 10px;">Comentar</button>
            </form>

            <?php
            $sql_com = "SELECT c.*, u.nome, u.foto, u.username 
            FROM comentarios c 
            JOIN usuarios u ON c.usuario_id = u.id 
            WHERE c.post_id = ? 
            ORDER BY c.data_criacao DESC";
            $stmt_com = mysqli_prepare($conn, $sql_com);
            mysqli_stmt_bind_param($stmt_com, "i", $id_post);
            mysqli_stmt_execute($stmt_com);
            $res_com = mysqli_stmt_get_result($stmt_com);
            
            while ($com = mysqli_fetch_assoc($res_com)): 
    $foto_comentarista = !empty($com['foto']) ? htmlspecialchars($com['foto']) : null;
    // Precisamos buscar o username também, então ajuste seu SELECT SQL acima para incluir 'u.username'
?>
    <div style="display: flex; gap: 15px; margin-bottom: 20px; background: #334155; padding: 15px; border-radius: 12px;">
        <div style="width: 45px; height: 45px; border-radius: 50%; overflow: hidden; background: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; flex-shrink: 0;">
            <?php if ($foto_comentarista): ?>
                <img src="<?= $foto_comentarista; ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <?= strtoupper(substr($com['nome'], 0, 1)); ?>
            <?php endif; ?>
        </div>
        <div>
            <a href="perfil.php?u=<?= urlencode($com['username']); ?>" style="text-decoration: none;">
                <strong style="display: block; font-size: 14px; color: #ffffff;"><?= htmlspecialchars($com['nome']); ?></strong>
            </a>
        
            <p style="font-size: 14px; color: #e2e8f0; margin: 4px 0; white-space: pre-wrap; word-wrap: break-word;"><?= htmlspecialchars(trim($com['comentario']), ENT_QUOTES, 'UTF-8'); ?></p>
        
            <small style="color: #94a3b8; font-size: 11px;"><?= date('d/m/Y H:i', strtotime($com['data_criacao'])); ?></small>
        </div>
    </div>

    <?php endwhile; ?>
    </section>

    </article>

    <script>
    function votar(post_id, tipo) {
        let formData = new FormData();
        formData.append('post_id', post_id);
        formData.append('tipo', tipo);

        fetch('../processes/votar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "sucesso") {
                location.reload(); 
            } else {
                alert("Erro ao votar: " + data.mensagem);
            }
        })
        .catch(error => console.error('Erro:', error));
    }
</script>

</main>

<?php include("../includes/footer.php"); ?>