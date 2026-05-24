<?php
require_once("../includes/trava.php");
require_once("../config/database.php");
include("../includes/header.php");
?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<main class="conteudo-admin">
    <div class="topo-pagina">
        <div>
            <h2>Criar novo post</h2>
            <p>Publique um novo artigo no blog.</p>
        </div>
    </div>

    <div class="card-formulario">
        <form action="../processes/criar-post.php" method="POST" id="form-post" onsubmit="prepararFormulario()">
            
            <div class="grupo-formulario">
                <label for="titulo">Título do artigo</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título do artigo" required>
            </div>

            <div class="grupo-formulario">
                <label for="resumo">Resumo</label>
                <textarea id="resumo" name="resumo" rows="4" placeholder="Escreva um pequeno resumo" required></textarea>
            </div>

            <div class="grupo-formulario">
                <label>Conteúdo do artigo</label>
                
                <div id="toolbar" style="background: #e2e8f0; border-radius: 8px 8px 0 0;">
                    <button class="ql-bold" title="Negrito"></button>
                    <button class="ql-italic" title="Itálico"></button>
                    <button class="ql-underline" title="Sublinhado"></button>
                    <select class="ql-color" title="Cor do texto">
                        <option value="red"></option>
                        <option value="blue"></option>
                        <option value="green"></option>
                        <option value="yellow"></option>
                        <option value="purple"></option>
                        <option value="cyan"></option>
                        <option value="orange"></option>
                        <option value="white"></option>
                    </select>
                    <button class="ql-align" value="" title="Alinhar à esquerda"></button>
                    <button class="ql-align" value="center" title="Centralizar"></button>
                    <button class="ql-align" value="justify" title="Justificar"></button>
                    <button class="ql-align" value="right" title="Alinhar à direita"></button>
                </div>
                
                <div id="editor" style="height: 300px; background: #1e293b; color: #ffffff; border-radius: 0 0 8px 8px;"></div>
                
                <input type="hidden" name="conteudo" id="conteudo-input">
            </div>

            <div class="grupo-formulario">
                <label>Tags</label>
                <div class="tags-container" style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;">
                    <?php 
                    $tags_lista = ['esporte', 'culinária', 'jogos', 'tecnologia', 'opinião', 'notícia', 'humor', 'life hack'];
                    foreach ($tags_lista as $tag): ?>
                    <label style="cursor: pointer;">
                        <input type="checkbox" name="tags[]" value="<?= $tag; ?>" style="display: none;">
                            <span class="tag-botao" style="padding: 8px 16px; background: #334155; border-radius: 20px; color: #cbd5e1; border: 1px solid #475569; display: inline-block;">
                            <?= ucfirst($tag); ?>
                            </span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <style>
            /* Estilo para quando a tag for selecionada */
            input[type="checkbox"]:checked + .tag-botao {
            background: #2563eb !important;
            color: white !important;
            border-color: #2563eb !important;
    }       </style>

            <button type="submit" class="botao-primario">Publicar artigo</button>
        </form>
    </div>
</main>

<script>
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: { toolbar: '#toolbar' }
    });

    // Pega o formulário pelo ID (Certifique-se de que seu <form> tem id="form-post")
    var form = document.getElementById('form-post'); 

    form.onsubmit = function(event) {
        var conteudoInput = document.getElementById('conteudo-input');
        
        // Pega o conteúdo (getText().trim() remove espaços vazios)
        var textoLimpo = quill.getText().trim();
        
        if (textoLimpo === "") {
            // Impede o envio do formulário
            event.preventDefault(); 
            alert("Por favor, preencha o conteúdo do artigo.");
            return false;
        }

        // Se passar na validação, coloca o HTML no input hidden
        conteudoInput.value = quill.root.innerHTML;
        return true;
    };
</script>

<?php include("../includes/footer.php"); ?>