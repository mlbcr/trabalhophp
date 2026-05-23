<?php
require_once("../includes/trava.php"); 
require_once("../config/database.php");

$id_logado = $_SESSION["usuario_id"];
$erro = "";
$sucesso = "";

// 1. Cria a pasta de uploads caso ela não exista
$diretorio_uploads = "../uploads/";
if (!is_dir($diretorio_uploads)) {
    mkdir($diretorio_uploads, 0777, true);
}

// 2. Processa o formulário quando o botão "Salvar Alterações" é clicado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $biografia = trim($_POST['biografia'] ?? '');
    $data_nascimento = !empty($_POST['data_nascimento']) ? $_POST['data_nascimento'] : null;
    $genero = trim($_POST['genero'] ?? '');

    if (empty($nome)) {
        $erro = "O nome não pode ficar em branco.";
    } else {
        // Busca as imagens atuais para não apagá-las caso o usuário não envie imagens novas
        $sql_atual = "SELECT foto, banner FROM usuarios WHERE id = ?";
        $stmt_atual = mysqli_prepare($conn, $sql_atual);
        mysqli_stmt_bind_param($stmt_atual, "i", $id_logado);
        mysqli_stmt_execute($stmt_atual);
        $user_atual = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_atual));
        mysqli_stmt_close($stmt_atual);
        
        $caminho_foto = $user_atual['foto'];
        $caminho_banner = $user_atual['banner'];

        // Processa o upload da Foto de Perfil
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $extensao_foto = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $nome_arquivo_foto = "foto_" . $id_logado . "_" . time() . "." . $extensao_foto;
            $destino_foto = $diretorio_uploads . $nome_arquivo_foto;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino_foto)) {
                $caminho_foto = $destino_foto;
            }
        }

        // Processa o upload do Banner
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $extensao_banner = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
            $nome_arquivo_banner = "banner_" . $id_logado . "_" . time() . "." . $extensao_banner;
            $destino_banner = $diretorio_uploads . $nome_arquivo_banner;
            
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $destino_banner)) {
                $caminho_banner = $destino_banner;
            }
        }

        // 3. Atualiza os dados no banco
        $sql_update = "UPDATE usuarios SET nome = ?, biografia = ?, data_nascimento = ?, genero = ?, foto = ?, banner = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "ssssssi", $nome, $biografia, $data_nascimento, $genero, $caminho_foto, $caminho_banner, $id_logado);
        
        if (mysqli_stmt_execute($stmt_update)) {
            $sucesso = "Seu perfil foi atualizado com sucesso!";
        } else {
            $erro = "Ocorreu um erro ao atualizar o perfil. Tente novamente.";
        }
        mysqli_stmt_close($stmt_update);
    }
}

// 4. Busca os dados atuais do usuário para preencher os campos do formulário
$sql_user = "SELECT nome, biografia, data_nascimento, genero, foto, banner FROM usuarios WHERE id = ?";
$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $id_logado);
mysqli_stmt_execute($stmt_user);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_user));
mysqli_stmt_close($stmt_user);

include("../includes/header.php");
?>

<main class="conteudo-admin">

    <div class="topo-pagina">
        <div>
            <h2>Editar Perfil</h2>
            <p>Atualize suas informações pessoais, foto e banner.</p>
        </div>
        <a href="perfil.php" class="btn-voltar">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Voltar ao Perfil
        </a>
    </div>

    <!-- Mensagens de Feedback -->
    <?php if (!empty($erro)): ?>
        <div style="background: #7f1d1d; color: #fecaca; border: 1px solid #dc2626; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <?= htmlspecialchars($erro); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($sucesso)): ?>
        <div style="background: #14532d; color: #bbf7d0; border: 1px solid #16a34a; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <?= htmlspecialchars($sucesso); ?>
        </div>
    <?php endif; ?>

    <div class="card-formulario" style="max-width: 800px;">
        <!-- Importante: o enctype multipart/form-data permite o envio de arquivos -->
        <form action="editar-perfil.php" method="POST" enctype="multipart/form-data">
            
            <div class="grupo-formulario">
                <label for="nome">Nome de Exibição</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required>
            </div>

            <div class="grupo-formulario">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($usuario['data_nascimento'] ?? '') ?>">
            </div>

            <div class="grupo-formulario">
                <label for="genero">Gênero</label>
                <!-- Usando estilos inline para herdar a identidade visual dos inputs -->
                <select id="genero" name="genero" style="background: #0f172a; border: 1px solid #334155; border-radius: 12px; padding: 16px; color: #e2e8f0; font-size: 15px; width: 100%; outline: none; cursor: pointer;">
                    <option value="">Prefiro não informar</option>
                    <option value="Masculino" <?= ($usuario['genero'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                    <option value="Feminino" <?= ($usuario['genero'] == 'Feminino') ? 'selected' : '' ?>>Feminino</option>
                    <option value="Não-Binário" <?= ($usuario['genero'] == 'Não-Binário') ? 'selected' : '' ?>>Não-Binário</option>
                    <option value="Outro" <?= ($usuario['genero'] == 'Outro') ? 'selected' : '' ?>>Outro</option>
                </select>
            </div>

            <div class="grupo-formulario">
                <label for="biografia">Sua Biografia</label>
                <textarea id="biografia" name="biografia" placeholder="Conte um pouco sobre você..."><?= htmlspecialchars($usuario['biografia'] ?? '') ?></textarea>
            </div>

            <!-- Imagens do Perfil -->
            <div style="border-top: 1px solid #1e293b; margin: 32px 0 24px 0; padding-top: 24px;">
                <h3 style="color: #f8fafc; font-size: 18px; margin-bottom: 16px;">Imagens do Perfil</h3>
                
                <div class="grupo-formulario">
                    <label for="foto">Nova Foto de Perfil</label>
                    <input type="file" id="foto" name="foto" accept="image/*" style="padding: 12px;">
                    <small style="color: #94a3b8; margin-top: 8px;">Deixe em branco se não quiser alterar a foto atual.</small>
                </div>

                <div class="grupo-formulario">
                    <label for="banner">Novo Banner</label>
                    <input type="file" id="banner" name="banner" accept="image/*" style="padding: 12px;">
                    <small style="color: #94a3b8; margin-top: 8px;">Deixe em branco se não quiser alterar o banner atual.</small>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 16px; margin-top: 32px;">
                <button type="submit" class="botao-primario">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</main>

<?php include("../includes/footer.php"); ?>