<?php
session_start();
if (isset($_SESSION["usuario_id"])) {
    header("Location: admin/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Blog</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card-login {
            width: 100%;
            max-width: 420px;
        }
        .error-alert {
            background: #ef444420;
            border: 1px solid #ef4444;
            color: #f87171;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .links-uteis {
            text-align: center;
            font-size: 14px;
            color: #94a3b8;
            margin-top: 20px;
        }
        .links-uteis a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }
        .links-uteis a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card-formulario card-login">
            <div class="topo-pagina">
                <h2>Bem-vindo de volta</h2>
                <p>Faça login para gerenciar suas publicações</p>
            </div>

            <?php if (isset($_GET["erro"])): ?>
                <!-- Mensagem genérica ideal para segurança -->
                <div class="error-alert">Dados de acesso incorretos.</div>
            <?php endif; ?>

            <form action="processes/autenticar.php" method="POST">
                <div class="grupo-formulario">
                    <!-- Alterado para aceitar email ou username -->
                    <label for="login_input">E-mail ou Nome de usuário</label>
                    <input type="text" id="login_input" name="login_input" placeholder="seu@email.com ou seu_username" required>
                </div>
                <div class="grupo-formulario">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                </div>
                <button type="submit" class="botao-primario" style="width: 100%;">Entrar</button>
            </form>

            <div class="links-uteis">
                Não tem uma conta? <a href="cadastrar.php">Cadastre-se aqui</a>
            </div>
        </div>
    </div>
</body>
</html>