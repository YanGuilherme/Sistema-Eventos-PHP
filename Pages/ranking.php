<?php
require_once('../Classes/Usuario.php');
session_start();

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';

$rankingUsuarios = Usuario::listarRankingUsuarios(); // Assumindo que o método já está implementado

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
    <title>Ranking de Participação</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_home.css">
</head>
<body>
    <header><h1>Ranking de Participação</h1></header>
    
    <nav>
        <a href="./inicio.php">Inicio</a>
        <a href="sobre.php"> Sobre</a>

        <?php if ($usuarioLogado): ?>
            <a href="eventos_user.php">Meus eventos</a>
            <a href="ranking.php">Ranking de Participação</a> <!-- Aba para Ranking -->
            <a href="./perfil.php">Perfil</a>
            <a href="?action=logout">Deslogar</a>
            
            <?php if ($tipoUsuario === 'administrador'): ?>
                <a href="./dashboard_adm.php">Dashboard Admin</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="curso_concluido.php?curso_id=1">Marcar Conclusão de Curso</a>

            <?php endif; ?>
        <?php else: ?>
            <a href="./login.php">Logar</a>
            <a href="./cadastro.php">Fazer cadastro</a>
        <?php endif; ?>
    </nav>

    <div class="wrapper">
        <?php if (!empty($rankingUsuarios)): ?>
            <ol>
                <?php foreach ($rankingUsuarios as $usuario): ?>
                    <li><?php echo htmlspecialchars($usuario['nome']); ?> - <?php echo htmlspecialchars($usuario['pontos']); ?> pontos</li>
                <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <p>Não há usuários no ranking.</p>
        <?php endif; ?>
    </div>
</body>
</html>
