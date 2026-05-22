<?php

include("../includes/header.php");

?>
<main class="conteudo-admin">
    <div class="topo-pagina">
        <div>
            <h2>Criar novo post</h2>
            <p>Publique um novo artigo no blog.</p>
        </div>
    </div>

    <div class="card-formulario">

        <form action="../processes/criar-post.php" method="POST">

            <div class="grupo-formulario">

                <label for="titulo">
                    Título do artigo
                </label>

                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    placeholder="Digite o título do artigo"
                    required
                >

            </div>

            <div class="grupo-formulario">

                <label for="resumo">
                    Resumo
                </label>

                <textarea
                    id="resumo"
                    name="resumo"
                    rows="4"
                    placeholder="Escreva um pequeno resumo"
                    required
                ></textarea>

            </div>

            <div class="grupo-formulario">

                <label for="conteudo">
                    Conteúdo do artigo
                </label>

                <textarea
                    id="conteudo"
                    name="conteudo"
                    rows="12"
                    placeholder="Escreva o conteúdo completo do artigo"
                    required
                ></textarea>

            </div>

            <button type="submit" class="botao-primario">
                Publicar artigo
            </button>

        </form>

    </div>

</main>

<?php include("../includes/footer.php"); ?>