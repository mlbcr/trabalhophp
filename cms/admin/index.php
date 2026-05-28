<?php
require_once("../includes/trava.php"); 
require_once("../config/database.php");

include("../includes/header.php");

$id_logado = $_SESSION["usuario_id"];

// Adquire do banco de dados todos os posts que ele possui e suas informações e usa JOIN para pegar o nome do autor
$sql = "SELECT posts.*, usuarios.username AS autor_username 
        FROM posts 
        INNER JOIN usuarios ON posts.usuario_id = usuarios.id 
        ORDER BY posts.id DESC";

$resultado = mysqli_query($conn, $sql);
?>

<main class="conteudo-admin">
    <div class="topo-pagina-atualizada">
        <div>
            <h2>Feed da Comunidade</h2>
            <p>Explore as últimas publicações dos membros do blog.</p>
        </div>
        
        <a href="criar-post.php" class="botao-primario acao-novo-artigo">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Escrever Artigo
        </a>
    </div>

    <div class="posts-grid">
        <?php if (mysqli_num_rows($resultado) == 0): ?>
            <!-- Se não existirem posts no banco de dados -->
            <div class="card-formulario-vazio">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>
                <p>Nenhum artigo foi publicado na comunidade ainda.</p>
                <a href="criar-post.php" class="links-uteis" style="color: #3b82f6; font-size: 14px; font-weight: 500; margin-top: 8px; display: inline-block;">Seja o primeiro a escrever →</a>
            </div>
        <?php endif; ?>

        <?php while ($post = mysqli_fetch_assoc($resultado)) : ?>
            <!-- O $post itera os posts no banco de dados enquanto você estiver na página -->
            <div class="post-card">
                <div class="post-card-body">
                    <div class="post-header-info">
                        <span class="post-autor">@<?= htmlspecialchars($post['autor_username']); ?></span>
                        <span class="post-meta"><?= date('d/m/Y', strtotime($post['data_publicacao'])); ?></span>
                    </div>
                    
                    <h3 class="post-titulo"><?= htmlspecialchars($post['titulo']); ?></h3>
                    <p class="post-resumo"><?= htmlspecialchars($post['resumo']); ?></p>
                    <?php if (!empty($post['tags'])): ?>
                        <div class="post-tags">
                            <?php 
                            $lista_tags = explode(',', $post['tags']);
                            foreach ($lista_tags as $tag): 
                                $tag_limpa = trim($tag);
                                if (!empty($tag_limpa)): ?>
                                    <span class="tag-exibicao" >
                                        <?= htmlspecialchars(ucfirst($tag_limpa)); ?>
                                    </span>
                                <?php endif; 
                            endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Botões de Editar e Excluir só aparecem se o post for do usuário logado. Do contrário, aparece apenas o Visualizar -->
                <div class="post-card-acoes">
                    <a href="visualizar-post.php?id=<?= $post['id']; ?>" class="btn-acao btn-ver">
                        Visualizar
                    </a>

                    <?php if ($post['usuario_id'] == $id_logado): ?>
                        <a href="editar-post.php?id=<?= $post['id']; ?>" class="btn-acao btn-editar">
                            Editar
                        </a>
                        <a href="../processes/excluir-post.php?id=<?= $post['id']; ?>" class="btn-acao btn-excluir" onclick="return confirm('Tem certeza que deseja excluir seu artigo?')">
                            Excluir
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php include("../includes/footer.php"); ?>