<?php
require_once("../includes/trava.php"); 
require_once("../config/database.php");
include("../includes/header.php");

$id_logado = $_SESSION["usuario_id"];
$username_url = trim($_GET['u'] ?? '');

// 1. Busca do Usuário
if (empty($username_url)) {
    $sql_usuario = "SELECT id, username, nome, foto, banner, biografia, data_nascimento, genero FROM usuarios WHERE id = ? LIMIT 1";
    $stmt_user = mysqli_prepare($conn, $sql_usuario);
    mysqli_stmt_bind_param($stmt_user, "i", $id_logado);
} else {
    $sql_usuario = "SELECT id, username, nome, foto, banner, biografia, data_nascimento, genero FROM usuarios WHERE username = ? LIMIT 1";
    $stmt_user = mysqli_prepare($conn, $sql_usuario);
    mysqli_stmt_bind_param($stmt_user, "s", $username_url);
}

mysqli_stmt_execute($stmt_user);
$resultado_user = mysqli_stmt_get_result($stmt_user);
$perfil_usuario = mysqli_fetch_assoc($resultado_user);
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

// 2. Posts
$sql_posts = "SELECT posts.*, usuarios.username AS autor_username 
              FROM posts 
              INNER JOIN usuarios ON posts.usuario_id = usuarios.id 
              WHERE posts.usuario_id = ?
              ORDER BY posts.id DESC";

$stmt_posts = mysqli_prepare($conn, $sql_posts);
mysqli_stmt_bind_param($stmt_posts, "i", $id_perfil);
mysqli_stmt_execute($stmt_posts);
$resultado_posts = mysqli_stmt_get_result($stmt_posts);
$total_posts = mysqli_num_rows($resultado_posts);
mysqli_stmt_close($stmt_posts);

// 3. Status Seguidores
$sql_check = "SELECT 1 FROM seguidores WHERE seguidor_id = ? AND seguido_id = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $id_logado, $id_perfil);
mysqli_stmt_execute($stmt_check);
$ja_segue = mysqli_fetch_row(mysqli_stmt_get_result($stmt_check));
mysqli_stmt_close($stmt_check);

$sql_contagem = "SELECT COUNT(*) as total FROM seguidores WHERE seguido_id = ?";
$stmt_contagem = mysqli_prepare($conn, $sql_contagem);
mysqli_stmt_bind_param($stmt_contagem, "i", $id_perfil);
mysqli_stmt_execute($stmt_contagem);
$total_seguidores = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_contagem))['total'];
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
    <div class="perfil-banner-container" style="background: #cbd5e1 url('<?= !empty($banner_perfil) ? htmlspecialchars($banner_perfil) : "../assets/img/default-banner.jpg"; ?>') no-repeat center/cover;"></div>

    <div class="perfil-info-wrapper">
        <div class="perfil-header-flex">
            <div class="perfil-usuario-bloco">
                <?php if (!empty($foto_perfil)): ?>
                    <img src="<?= htmlspecialchars($foto_perfil); ?>" alt="Foto de <?= htmlspecialchars($nome_perfil); ?>" class="perfil-foto">
                <?php else: ?>
                    <div class="user-avatar"><?= strtoupper(substr($nome_perfil, 0, 1)); ?></div>
                <?php endif; ?>

                <div class="perfil-nome-container">
                    <h2 class="perfil-nome"><?= htmlspecialchars($nome_perfil); ?></h2>
                    <p class="perfil-username">@<?= htmlspecialchars($username_perfil); ?></p>
                    <p style="color: #94a3b8; font-size: 14px;"><strong><?= $total_seguindo; ?></strong> seguindo</p>
                    <p class="perfil-seguidores-contador" style="color: #94a3b8; font-size: 14px; margin-top: 4px;"><strong><?= $total_seguidores; ?></strong> seguidores</p>
                </div>
            </div>

            <div class="perfil-acao-container">
                <?php if ($eh_o_proprio_dono): ?>
                    <a href="editar-perfil.php" class="botao-primario botao-editar-perfil">Editar Perfil</a>
                    <a href="criar-post.php" class="botao-primario">Escrever Artigo</a>
                <?php else: ?>
                    <a href="#" id="btn-seguir-acao" 
                        data-id="<?= $id_perfil ?>" 
                        onclick="alternarSeguir(<?= $id_perfil ?>, '<?= $ja_segue ? 'deixar' : 'seguir' ?>'); return false;" 
                        class="<?= $ja_segue ? 'botao-secundario' : 'botao-primario' ?>">
                        <?= $ja_segue ? 'Deixar de Seguir' : 'Seguir' ?>
                    </a>

                    <script>
                    function alternarSeguir(id, acao) {
                        let formData = new FormData();
                        formData.append('id', id);
                        formData.append('acao', acao);

                        fetch('../processes/seguir.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "sucesso") {
                                let btn = document.querySelector(`[data-id="${id}"]`);
                                
                                // 1. Alternar texto
                                btn.innerText = (data.acao === 'seguir') ? 'Deixar de seguir' : 'Seguir';
                                
                                // 2. Alternar classes de estilo (importante!)
                                btn.classList.toggle('botao-primario');
                                btn.classList.toggle('botao-secundario');
                                
                                // 3. Atualizar o onclick para a próxima ação
                                let novaAcao = (data.acao === 'seguir') ? 'deixar' : 'seguir';
                                btn.setAttribute('onclick', `alternarSeguir(${id}, '${novaAcao}'); return false;`);
                            } else {
                                console.error("Erro:", data.mensagem);
                            }
                        })
                        .catch(error => console.error('Erro na requisição:', error));
                    }
                    </script>
                <?php endif; ?>
            </div>
        </div>

        <div class="perfil-detalhes-card">
            <div class="perfil-status-grid">
                <div><span class="perfil-label">Posts Criados</span><strong class="perfil-valor"><?= $total_posts; ?> publicações</strong></div>
                <div><span class="perfil-label">Data de Aniversário</span><strong class="perfil-valor"><?= !empty($data_nascimento) ? date('d/m/Y', strtotime($data_nascimento)) : "Não informado"; ?></strong></div>
                <div><span class="perfil-label">Gênero</span><strong class="perfil-valor"><?= !empty($genero) ? htmlspecialchars($genero) : "Não informado"; ?></strong></div>
            </div>
            <div>
                <span class="perfil-label-bio">Biografia</span>
                <p class="perfil-bio-texto"><?= !empty($biografia) ? nl2br(htmlspecialchars($biografia)) : "Este usuário ainda não escreveu uma biografia."; ?></p>
            </div>
        </div>
    </div>

    <div class="perfil-posts-container">
        <h3 class="perfil-posts-titulo"><?= $eh_o_proprio_dono ? "Minhas Publicações" : "Artigos de " . htmlspecialchars($nome_perfil); ?></h3>
        <div class="posts-grid">
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