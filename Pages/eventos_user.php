<?php
include_once '../Classes/Evento.php';
include_once '../Classes/Curso.php';
session_start();

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';
$user_id = $_SESSION['user']['id'];

$eventosUsuario = Evento::listarEventosUsuario($user_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Eventos</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_home.css">
</head>
<body>
    <header><h1>Meus Eventos</h1></header>
    <nav>
        <a href="inicio.php">Inicio</a>
        <a href="sobre.php"> Sobre</a>

        <?php if ($usuarioLogado): ?>
            <a href="eventos_user.php">Meus eventos</a>
            <a href="ranking.php">Ranking de Participação</a>
            <a href="perfil.php">Perfil</a>
            <a href="?action=logout">Deslogar</a>
        <?php else: ?>
            <a href="login.php">Logar</a>
            <a href="cadastro.php">Fazer cadastro</a>
        <?php endif; ?>
    </nav>
    
    <article>
        <h2>Meus Eventos</h2>
        <div class="wrapper">
            <?php if (!empty($eventosUsuario)): ?>
                <?php foreach ($eventosUsuario as $evento): ?>
                    <div class="evento">
                        <h3><?php echo htmlspecialchars($evento->getTitulo()); ?></h3>
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($evento->getDescricao()); ?></p>
                        <p><strong>Data de Início:</strong> <?php echo htmlspecialchars($evento->getDataInicio()); ?></p>
                        <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars($evento->getDataFim()); ?></p>

                        <!-- Listando cursos relacionados ao evento -->
                        <?php
                        $cursos = Curso::listarCursosPorEvento($evento->getId());
                        if (!empty($cursos)): ?>
                            <h4>Cursos:</h4>
                            <div class="cursos">
                                <?php foreach ($cursos as $curso): ?>
                                    <div class="curso">
                                        <h5><?php echo htmlspecialchars($curso->getTitulo()); ?></h5>
                                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($curso->getDescricao()); ?></p>
                                        <p><strong>Data:</strong> <?php echo htmlspecialchars($curso->getData()); ?></p>
                                        <p><strong>Horário:</strong> <?php echo htmlspecialchars($curso->getHorario()); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Nenhum curso disponível para este evento.</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Você não está inscrito em nenhum evento.</p>
            <?php endif; ?>
        </div>
    </article>

    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>
