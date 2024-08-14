

<?php

include_once '../Classes/Evento.php';
include_once '../Classes/Curso.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Inicia a sessão

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';


$eventos = Evento::listarEventos();
function logout() {
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// Se a ação de logout for solicitada
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_home.css">

</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>
    <?php if($usuarioLogado)
    ?>
    <nav>
    <a href="inicio.php">Inicio</a>
        <?php if ($usuarioLogado): ?>
            <?php if ($tipoUsuario === 'aluno'): ?>
                <a href="eventos_user.php">Meus eventos</a>
            <?php endif; ?>

            <?php if ($tipoUsuario === 'administrador'): ?>
                <a href="dashboard_adm.php">Dashboard Admin</a>
            <?php endif; ?>
            <a href="perfil.php">Perfil</a>
            <a href="?action=logout">Deslogar</a>
        <?php else: ?>
            <a href="login.php">Logar</a>
            <a href="cadastro.php">Fazer cadastro</a>
        <?php endif; ?>
    </nav><br>
    <input type="text" name="busca_evento" id="busca_evento" placeholder="Buscar por um evento">&nbsp;<button>Pesquisar</button>
    
    <article>
    <h2>Eventos e cursos</h2>

    <div class="wrapper">
        <?php
        // Assumindo que as funções listarEventos e listarCursosPorEvento estão sendo chamadas antes desta parte do código
        $eventos = Evento::listarEventos();

        foreach ($eventos as $evento): 
            // Listando cursos associados ao evento
            $cursos = Curso::listarCursosPorEvento($evento->getId());
        ?>
            <div class="evento">
                <h3><?php echo htmlspecialchars($evento->getTitulo()); ?></h3>
                <p><strong style="word-break: break-word;">Descrição:</strong> <?php echo htmlspecialchars($evento->getDescricao()); ?></p>
                <p><strong>Data de Início:</strong> <?php echo htmlspecialchars($evento->getDataInicio()); ?></p>
                <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars($evento->getDataFim()); ?></p>
                <?php if($tipoUsuario === 'administrador'):?>
                <form action="./editar_evento.php" method="post">
                    <input type="hidden" name="id_evento" value="<?php echo htmlspecialchars($evento->getId()); ?>">
                    <button type="submit">Editar evento</button>
                </form><br>
                <form action="../Service/excluir_evento" method="post">
                    <input type="hidden" name="id_evento" value="<?php echo htmlspecialchars($evento->getId()); ?>">
                    <button type="submit">Excluir evento</button>
                </form>
                <?php endif; ?>
                    
                <?php  ?>
                <!-- Listando cursos relacionados ao evento -->
                <?php if (!empty($cursos)): ?>
                    <h4>Cursos:</h4>
                    <div class="cursos">
                        <?php foreach ($cursos as $curso): ?>
                            <div class="curso">
                                <h5><?php echo htmlspecialchars($curso->getTitulo()); ?></h5>
                                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($curso->getDescricao()); ?></p>
                                <p><strong>Data:</strong> <?php echo htmlspecialchars($curso->getData()); ?></p>
                                <p><strong>Horário:</strong> <?php echo htmlspecialchars($curso->getHorario()); ?></p>
                                <?php if($tipoUsuario === 'administrador'):?>
                                    <form action="./editar_curso.php" method="post">
                                        <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($curso->getId()); ?>">
                                        <button type="submit">Editar curso</button>
                                    </form><br>
                                    <form action="../Service/excluir_curso" method="post">
                                        <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($curso->getId()); ?>">
                                        <button type="submit">Excluir curso</button>
                                    </form>

                                    <?php else: ?>
                                        <form action="./inscricao.php" method="post">
                                        <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($curso->getId()); ?>">
                                        <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($_SESSION['user']['id']); ?>">
                                        <button type="submit">Inscrever-se no curso</button>
                                    </form>

                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Nenhum curso disponível para este evento.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</article>


    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>