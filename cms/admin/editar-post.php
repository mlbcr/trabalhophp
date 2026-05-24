<?php
require_once("../includes/trava.php");
require_once("../config/database.php");
include("../includes/header.php");

$id_post = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$id_logado = $_SESSION["usuario_id"];

// Busca o post e verifica se pertence ao usuário logado
$sql = "SELECT * FROM posts WHERE id = ? AND usuario_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id_post, $id_logado);
mysqli_stmt_execute($stmt);
$post = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$post) {
    header("Location: index.php");
    exit;
}
?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<main class="conteudo-admin">
    <div class="topo-pagina">
        <h2>Editar Artigo</h2>
    </div>

    <div class="card-formulario">
        <form action="../processes/editar-postp.php" method="POST" id="form-post" onsubmit="prepararFormulario()">
            <input type="hidden" name="id" value="<?= $post['id']; ?>">
            
            <div class="grupo-formulario">
                <label for="titulo">Título do artigo</label>
                <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($post['titulo']); ?>" required>
            </div>

            <div class="grupo-formulario">
                <label for="resumo">Resumo</label>
                <textarea id="resumo" name="resumo" rows="4" required><?= htmlspecialchars($post['resumo']); ?></textarea>
            </div>

            <div class="grupo-formulario">
                <label>Conteúdo do artigo</label>
                <div id="toolbar" style="background: #e2e8f0; border-radius: 8px 8px 0 0;">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                    <select class="ql-color">
                        <option value="red"></option>
                        <option value="blue"></option>
                        <option value="green"></option>
                        <option value="yellow"></option>
                        <option value="purple"></option>
                        <option value="cyan"></option>
                        <option value="orange"></option>
                        <option value="black" selected></option>
                        <option value="white"></option>
                    </select>
                    <button class="ql-align" value=""></button>
                    <button class="ql-align" value="center"></button>
                    <button class="ql-align" value="justify"></button>
                    <button class="ql-align" value="right"></button>
                </div>
                <div id="editor" style="height: 300px; background: #1e293b; color: #ffffff; border-radius: 0 0 8px 8px;"></div>
                <input type="hidden" name="conteudo" id="conteudo-input">
            </div>

            <button type="submit" class="botao-primario">Salvar Alterações</button>
        </form>
    </div>
</main>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        modules: { toolbar: '#toolbar' },
        theme: 'snow'
    });

    // Carrega o conteúdo original do banco para dentro do editor
    quill.root.innerHTML = `<?= str_replace('`', '\`', $post['conteudo']); ?>`;

    function prepararFormulario() {
        document.getElementById('conteudo-input').value = quill.root.innerHTML;
    }
</script>

<?php include("../includes/footer.php"); ?>