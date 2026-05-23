<?php
require_once("../includes/trava.php"); 
require_once("../config/database.php");

// DICA: Certifique-se de importar o perfil.css no header.php 
// ou adicionar a tag <link rel="stylesheet" href="../assets/css/perfil.css">
include("../includes/header.php");

// 1. Pegamos o ID logado na sessão (esse sempre existe)
$id_logado = $_SESSION["usuario_id"];

// 2. Pegamos o username vindo da URL (ex: perfil.php?u=joao)
$username_url = trim($_GET['u'] ?? '');

if (empty($username_url)) {
    $sql_usuario = "SELECT id, username, nome, foto, banner, biografia, data_nascimento, genero FROM usuarios WHERE id = ? LIMIT 1";
    $stmt_user = mysqli_prepare($conn, $sql_usuario);
    if (!$stmt_user) die("Erro ao preparar consulta.");
    mysqli_stmt_bind_param($stmt_user, "i", $id_logado);
} else {
    $sql_usuario = "SELECT id, username, nome, foto, banner, biografia, data_nascimento, genero FROM usuarios WHERE username = ? LIMIT 1";
    $stmt_user = mysqli_prepare($conn, $sql_usuario);
    if (!$stmt_user) die("Erro ao preparar consulta.");
    mysqli_stmt_bind_param($stmt_user, "s", $username_url);
}

mysqli_stmt_execute($stmt_user);
$resultado_user = mysqli_stmt_get_result($stmt_user);
$perfil_usuario = mysqli_fetch_assoc($stmt_user ? $resultado_user : null);
mysqli_stmt_close($stmt_user);

if (!$perfil_usuario) {
    die("Usuário não encontrado.");
}

$id_perfil = $perfil_usuario['id'];
$nome_perfil = $perfil_usuario['nome'];
$username_perfil = $perfil_usuario['username'];

$foto_perfil = $perfil_usuario['foto'];
$banner_perfil = $perfil_usuario['banner'];
$biografia = $perfil_usuario['biografia'];
$data_nascimento = $perfil_usuario['data_nascimento'];
$genero = $perfil_usuario['genero'];

// 4. Buscamos apenas os posts do dono deste perfil
$sql_posts = "SELECT posts.*, usuarios.username AS autor_username 
              FROM posts 
              INNER JOIN usuarios ON posts.usuario_id = usuarios.id 
              WHERE posts.usuario_id = ?
              ORDER BY posts.id DESC";

$stmt_posts = mysqli_prepare($conn, $sql_posts);
if (!$stmt_posts) die("Erro ao preparar consulta de posts.");

mysqli_stmt_bind_param($stmt_posts, "i", $id_perfil);
mysqli_stmt_execute($stmt_posts);
$resultado_posts = mysqli_stmt_get_result($stmt_posts);
$total_posts = mysqli_num_rows($resultado_posts);
mysqli_stmt_close($stmt_posts);

$sql_check = "SELECT 1 FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $id_logado, $id_perfil);
mysqli_stmt_execute($stmt_check);
$ja_segue = mysqli_fetch_row(mysqli_stmt_get_result($stmt_check));

$sql_contagem = "SELECT COUNT(*) as total FROM seguidores WHERE seguido_id = ?";
$stmt_contagem = mysqli_prepare($conn, $sql_contagem);
mysqli_stmt_bind_param($stmt_contagem, "i", $id_perfil);
mysqli_stmt_execute($stmt_contagem);
$resultado_contagem = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_contagem));
$total_seguidores = $resultado_contagem['total'];
mysqli_stmt_close($stmt_contagem);

$sql_seguindo = "SELECT COUNT(*) as total FROM seguidores WHERE seguidor_id = ?";
$stmt_g = mysqli_prepare($conn, $sql_seguindo);
mysqli_stmt_bind_param($stmt_g, "i", $id_perfil);
mysqli_stmt_execute($stmt_g);
$total_seguindo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_g))['total'];
mysqli_stmt_close($stmt_g);

$eh_o_proprio_dono = ($id_perfil == $id_logado);
?>

<main class="conteudo-admin"> 
    <div class="perfil-banner-container" style="background: #cbd5e1 url('<?= !empty($banner_perfil) ? htmlspecialchars($banner_perfil) : "../assets/img/default-banner.jpg"; ?>') no-repeat center/cover;">
    </div>

    <div class="perfil-info-wrapper">
        <div class="perfil-header-flex">
            
            <div class="perfil-usuario-bloco">
                <?php if (!empty($foto_perfil)): ?>
                    <img src="<?= htmlspecialchars($foto_perfil); ?>" alt="Foto de <?= htmlspecialchars($nome_perfil); ?>" class="perfil-foto">
                <?php else: ?>
                    <div class="user-avatar">
                        <?= strtoupper(substr($nome_perfil, 0, 1)); ?>
                    </div>
                <?php endif; ?>

                <div class="perfil-nome-container">
                    <h2 class="perfil-nome">
                        <?= htmlspecialchars($nome_perfil); ?>
                    </h2>
                    <p class="perfil-username">@<?= htmlspecialchars($username_perfil); ?></p>

                    <p style="color: #94a3b8; font-size: 14px;">
                        <strong style="color: #f8fafc;"><?= $total_seguindo; ?></strong> seguindo
                    </p>

                    <p class="perfil-seguidores-contador" style="color: #94a3b8; font-size: 14px; margin-top: 4px;">
                        <strong><?= $total_seguidores; ?></strong> seguidores
                    </p>
                </div>
            </div>

            <div class="perfil-acao-container">
                <?php if ($eh_o_proprio_dono): ?>
                    <a href="editar-perfil.php" class="botao-primario botao-editar-perfil">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Editar Perfil
                    </a>
                    <a href="criar-post.php" class="botao-primario">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Escrever Artigo
                    </a>
                <?php else: ?>
                    <?php if ($ja_segue): ?>
                        <a href="../processes/seguir.php?id=<?= $id_perfil ?>&acao=deixar" class="botao-secundario">Deixar de Seguir</a>
                    <?php else: ?>
                        <a href="../processes/seguir.php?id=<?= $id_perfil ?>&acao=seguir" class="botao-primario">Seguir</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="perfil-detalhes-card">
            <div class="perfil-status-grid">
                <div>
                    <span class="perfil-label">Posts Criados</span>
                    <strong class="perfil-valor"><?= $total_posts; ?> publicações</strong>
                </div>
                <div>
                    <span class="perfil-label">Data de Aniversário</span>
                    <strong class="perfil-valor">
                        <?= !empty($data_nascimento) ? date('d/m/Y', strtotime($data_nascimento)) : "Não informado"; ?>
                    </strong>
                </div>
                <div>
                    <span class="perfil-label">Gênero</span>
                    <strong class="perfil-valor">
                        <?= !empty($genero) ? htmlspecialchars($genero) : "Não informado"; ?>
                    </strong>
                </div>
            </div>

            <div>
                <span class="perfil-label-bio">Biografia</span>
                <p class="perfil-bio-texto">
                    <?= !empty($biografia) ? nl2br(htmlspecialchars($biografia)) : "Este usuário ainda não escreveu uma biografia."; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="perfil-posts-container">
        <h3 class="perfil-posts-titulo">
            <?= $eh_o_proprio_dono ? "Minhas Publicações" : "Artigos de " . htmlspecialchars($nome_perfil); ?>
        </h3>

        <div class="posts-grid">
            <?php if ($total_posts == 0): ?>
                <div class="card-formulario-vazio">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#97aecf" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>
                    <p>Nenhum artigo foi publicado por este usuário ainda.</p>
                    <?php if ($eh_o_proprio_dono): ?>
                        <a href="criar-post.php" class="links-uteis link-primeiro-artigo">Escrever seu primeiro artigo →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php while ($post = mysqli_fetch_assoc($resultado_posts)) : ?>
                <div class="post-card">
                    <div class="post-card-body">
                        <div class="post-header-info">
                            <span class="post-autor">@<?= htmlspecialchars($post['autor_username']); ?></span>
                            <span class="post-meta"><?= date('d/m/Y', strtotime($post['data_publicacao'])); ?></span>
                        </div>
                        
                        <h3 class="post-titulo"><?= htmlspecialchars($post['titulo']); ?></h3>
                        <p class="post-resumo"><?= htmlspecialchars($post['resumo']); ?></p>
                    </div>
                    
                    <div class="post-card-acoes">
                        <a href="visualizar-post.php?id=<?= $post['id']; ?>" class="btn-acao btn-ver">Visualizar</a>

                        <?php if ($post['usuario_id'] == $id_logado): ?>
                            <a href="editar-post.php?id=<?= $post['id']; ?>" class="btn-acao btn-editar">Editar</a>
                            <a href="excluir-post.php?id=<?= $post['id']; ?>" class="btn-acao btn-excluir" onclick="return confirm('Tem certeza que deseja excluir seu artigo?')">Excluir</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>