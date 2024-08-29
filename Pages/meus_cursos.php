<?php
require_once('../Classes/Curso.php');
require_once('../Classes/Usuario.php');
session_start();

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';

$user_id = $_SESSION['user']['id'];
$cursosUsuario = Curso::listarCursosUsuario($user_id);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Cursos</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_home.css">
</head>
<body>

    <header><h1>Meus Cursos</h1></header>

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
        <?php if (!empty($cursosUsuario)): ?>
            <?php foreach ($cursosUsuario as $curso): ?>
                <div class="curso">
                    <h3><?php echo htmlspecialchars($curso->getTitulo()); ?></h3>
                    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($curso->getDescricao()); ?></p>
                    <p><strong>Data:</strong> <?php echo htmlspecialchars($curso->getData()); ?></p>
                    <p><strong>Horário:</strong> <?php echo htmlspecialchars($curso->getHorario()); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Você não está inscrito em nenhum curso.</p>
        <?php endif; ?>
    </div>
</body>
</html>
