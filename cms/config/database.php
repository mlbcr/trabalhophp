<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "cms";

// Desativa a exibição de erros técnicos na tela
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect($host, $usuario, $senha, $banco);

if (!$conn) {
    // Registra o erro real em um arquivo log (para você saber o que aconteceu)
    error_log("Falha na conexão MySQL: " . mysqli_connect_error());
    
    // Mostra uma página amigável e interrompe o carregamento
    die('
        <div style="font-family: sans-serif; text-align: center; padding: 50px; background: #0f172a; color: #e2e8f0; height: 100vh;">
            <h1 style="color: #ff0000;">Ops! Algo deu errado</h1>
            <p>Não foi possível conectar ao banco de dados no momento.</p>
            <p>Tente novamente mais tarde.</p>
            <a href="/" style="color: #3b82f6; text-decoration: none;">Voltar para a página inicial</a>
        </div>
    ');
}
?>