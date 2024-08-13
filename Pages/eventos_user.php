<?php 

session_start(); // Inicia a sessão

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';


function logout() {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>
    <?php if($usuarioLogado)
    ?>
        
    <nav>
        <a href="./inicio.php">Inicio</a>
        <?php if ($usuarioLogado): ?>
            <a href="./perfil.php">Perfil</a>
            <a href="?action=logout">Deslogar</a>
            <?php if ($tipoUsuario === 'aluno'): ?>
                <a href="eventos_user.php">Meus eventos</a>
            <?php elseif ($tipoUsuario === 'administrador'): ?>
                <a href="./dashboard_adm.php">Dashboard Admin</a>
            <?php endif; ?>
            <?php else: ?>
                <a href="./login.php">Logar</a>
                <a href="./cadastro.php">Fazer cadastro</a>
        <?php endif; ?>
    </nav>
        <main>
            <h2>Meus eventos</h2>

        </main>

    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>