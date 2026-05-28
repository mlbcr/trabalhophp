<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blogbox</title>
    <link rel="shortcut icon" href="assets/ico/icone.ico" type="image/x-icon">
    <link rel="stylesheet" href="/cms/assets/css/global.css">
    <link rel="stylesheet" href="/cms/assets/css/admin.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>

<body>
    <?php 

    // Se o usuário não estiver logado, a sua sessão é iniciada
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    
    // O topo fica conectado ao header
    include("topo.php"); ?>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>

    // O Quill abaixo serve para fazer o editor de texto. Ele não possui limites e sempre começa vazio
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: { toolbar: '#toolbar' }
    });

    // Envia o conteúdo para o campo oculto antes de enviar
    var form = document.querySelector('form');
    form.onsubmit = function() {
        var conteudoInput = document.querySelector('#conteudo-input');
        
        if (quill.getText().trim().length === 0) {
            conteudoInput.value = ''; 
        } else {
            conteudoInput.value = quill.root.innerHTML; 
        }
    };
</script>
</body>
</html>