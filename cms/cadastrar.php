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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Blog</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #0f172a;
            color: #e2e8f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card-cadastro {
            background: #111827;
            border: 1px solid #1e293b;
            border-radius: 20px;
            padding: 40px 32px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        }

        .topo-cadastro {
            margin-bottom: 32px;
            text-align: center;
        }

        .topo-cadastro h2 {
            font-size: 28px;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 8px;
        }

        .topo-cadastro p {
            color: #94a3b8;
            font-size: 15px;
        }

        .grupo-formulario {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .grupo-formulario label {
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
            color: #cbd5e1;
        }

        .grupo-formulario input {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 14px 16px;
            color: #e2e8f0;
            font-size: 15px;
            transition: all 0.2s;
        }

        .grupo-formulario input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .botao-primario {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px 22px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s, transform 0.1s;
            margin-bottom: 16px;
        }

        .botao-primario:hover {
            background: #1d4ed8;
        }

        .botao-primario:active {
            transform: scale(0.98);
        }

        .links-uteis {
            text-align: center;
            font-size: 14px;
            color: #94a3b8;
        }

        .links-uteis a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .links-uteis a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            text-align: center;
        }

        .alert-erro {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #f87171;
        }

        .alert-sucesso {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid #22c55e;
            color: #4ade80;
        }
    </style>
</head>
<body>

    <div class="card-cadastro">
        <div class="topo-cadastro">
            <h2>Criar Conta</h2>
            <p>Cadastre-se para começar a publicar</p>
        </div>

        <?php if (isset($_GET["erro"])): ?>
            <div class="alert alert-erro">
                <?php 
                    if ($_GET["erro"] == "email_existe") echo "Este e-mail já está cadastrado.";
                    elseif ($_GET["erro"] == "campos_vazios") echo "Por favor, preencha todos os campos.";
                    elseif ($_GET["erro"] == "username_existe") echo "Este nome de usuário já está em uso.";
                    else echo "Algo deu errado. Tente novamente.";
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET["sucesso"])): ?>
            <div class="alert alert-sucesso">
                Conta criada com sucesso! <a href="login.php" style="color: #4ade80; font-weight: bold; text-decoration: underline;">Fazer Login</a>
            </div>
        <?php endif; ?>

        <form action="processes/salvar-usuario.php" method="POST">
            <div class="grupo-formulario">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Seu nome" required>
            </div>

            <div class="grupo-formulario">
                <label for="username">Nome de usuário único (Username)</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <span style="position: absolute; left: 16px; color: #64748b; font-weight: 600; pointer-events: none;">@</span>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="johndoe" 
                        style="padding-left: 32px; width: 100%;" 
                        pattern="[a-zA-Z0-9._-]+" 
                        title="Apenas letras, números, pontos (.), underlines (_) ou hifens (-)."
                        required
                    >
                </div>
            </div>

            <div class="grupo-formulario">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>
            
            <div class="grupo-formulario">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Crie uma senha forte" required>
            </div>
            
            <button type="submit" class="botao-primario">Criar minha conta</button>
        </form>

        <div class="links-uteis">
            Já tem uma conta? <a href="login.php">Fazer login</a>
        </div>
    </div>

</body>
</html>