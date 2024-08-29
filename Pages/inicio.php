<?php

include_once '../Classes/Evento.php';
include_once '../Classes/Curso.php';
include_once '../Classes/Usuario.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Inicia a sessão

if (isset($_SESSION['mensagem'])) {
    echo "<p>{$_SESSION['mensagem']}</p>";
    unset($_SESSION['mensagem']);
}

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
$tipoUsuario = $usuarioLogado ? $usuarioLogado['tipo'] : '';

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$itemsPerPage = 4; // Número de cursos por página
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;

// Filtrar cursos pelo nome se houver uma pesquisa
if ($searchQuery) {
    $cursosFiltrados = Curso::pesquisarCursos($searchQuery, $offset, $itemsPerPage);
    $totalCursos = Curso::contarCursosFiltrados($searchQuery);
} else {
    $cursosFiltrados = Curso::listarCursosPaginados($offset, $itemsPerPage);
    $totalCursos = Curso::contarTodosCursos();
}

$totalPages = ceil($totalCursos / $itemsPerPage);

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
    <title>Home</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_home.css">
</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>
    <nav>
        <a href="inicio.php">Inicio</a>
        <a href="sobre.php"> Sobre</a>
        <?php if ($usuarioLogado): ?>
            <?php if ($tipoUsuario === 'aluno'): ?>
                <a href="eventos_user.php">Meus eventos</a>
            <?php endif; ?>

            <?php if ($tipoUsuario === 'administrador'): ?>
                <a href="curso_concluido.php?curso_id=1">Marcar Conclusão de Curso</a>
                <a href="dashboard_adm.php">Dashboard Admin</a>
                <a href="relatorios.php">Relatórios</a>

            <?php endif; ?>
            <a href="ranking.php">Ranking de Participação</a>
            <a href="perfil.php">Perfil</a>
            <a href="?action=logout">Deslogar</a>
        <?php else: ?>
            <a href="login.php">Logar</a>
            <a href="cadastro.php">Fazer cadastro</a>
        <?php endif; ?>
    </nav><br>

    <!-- Barra de pesquisa -->
    <form method="get" action="inicio.php">
        <input type="text" name="search" id="search" placeholder="Buscar por um curso" value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <article>
        <h2>Eventos e cursos</h2>
        <div class="wrapper">
            <?php
            if (!empty($cursosFiltrados) && is_array($cursosFiltrados)) {
                foreach ($cursosFiltrados as $curso): 
            ?>
                <div class="curso">
                    <h3><?php echo htmlspecialchars($curso->getTitulo()); ?></h3>
                    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($curso->getDescricao()); ?></p>
                    <p><strong>Data:</strong> <?php echo htmlspecialchars($curso->getData()); ?></p>
                    <p><strong>Horário:</strong> <?php echo htmlspecialchars($curso->getHorario()); ?></p>
                    <?php if($tipoUsuario === 'administrador'): ?>
                        <form action="./editar_curso.php" method="post">
                            <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($curso->getId()); ?>">
                            <button type="submit">Editar curso</button>
                        </form><br>
                        <form action="../Service/excluir_curso.php" method="post">
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
            <?php 
                endforeach;
            } else {
                echo "<p>Nenhum curso encontrado.</p>";
            }
            ?>
        </div>
    </article>

    <!-- Paginação -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchQuery); ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchQuery); ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchQuery); ?>">Próximo</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>
