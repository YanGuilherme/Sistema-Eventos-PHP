<?php
require_once '../Classes/Usuario.php';
require_once '../Classes/Evento.php';
require_once '../Classes/Curso.php';
require_once '../Classes/Inscricao.php';
session_start();

// Verifique se o usuário é um administrador
if ($_SESSION['user']['tipo'] !== 'administrador') {
    echo "Acesso negado.";
    exit();
}

// Obtenha os relatórios
$usuarios = Usuario::listarTodosUsuarios();
$eventos = Evento::listarEventos();
$cursos = Curso::listarTodosCursos();
$inscricoes = Inscricao::listarTodasInscricoes(); // Supondo que exista uma classe Inscricao

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
    <title>Relatórios</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_dash.css">
</head>
<body>
    <header><h1>Relatórios do Sistema</h1></header>
    <nav>
        <a href="inicio.php">Inicio</a>
        <a href="dashboard_adm.php">Dashboard Admin</a>
        <a href="?action=logout">Deslogar</a>
    </nav>

    <main class="main-container">
        <section>
            <h2>Relatório de Usuários</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Matrícula</th>
                        <th>Tipo</th>
                        <th>Pontos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($usuario->getId()); ?></td>
                        <td><?php echo htmlspecialchars($usuario->getNome()); ?></td>
                        <td><?php echo htmlspecialchars($usuario->getEmail()); ?></td>
                        <td><?php echo htmlspecialchars($usuario->getMatricula()); ?></td>
                        <td><?php echo htmlspecialchars($usuario->getTipo()); ?></td>
                        <td><?php echo htmlspecialchars($usuario->getPontos()); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Relatório de Eventos e Cursos</h2>
            <h3>Eventos</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data de Início</th>
                        <th>Data de Fim</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($evento->getId()); ?></td>
                        <td><?php echo htmlspecialchars($evento->getTitulo()); ?></td>
                        <td><?php echo htmlspecialchars($evento->getDescricao()); ?></td>
                        <td><?php echo htmlspecialchars($evento->getDataInicio()); ?></td>
                        <td><?php echo htmlspecialchars($evento->getDataFim()); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Cursos</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>ID do Evento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($curso->getId()); ?></td>
                        <td><?php echo htmlspecialchars($curso->getTitulo()); ?></td>
                        <td><?php echo htmlspecialchars($curso->getDescricao()); ?></td>
                        <td><?php echo htmlspecialchars($curso->getData()); ?></td>
                        <td><?php echo htmlspecialchars($curso->getHorario()); ?></td>
                        <td><?php echo htmlspecialchars($curso->getEventoId()); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Relatório de Inscrições</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID do Aluno</th>
                        <th>Nome do Aluno</th>
                        <th>ID do Curso</th>
                        <th>Título do Curso</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscricoes as $inscricao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inscricao['usuario_id']); ?></td>
                        <td><?php echo htmlspecialchars($inscricao['nome_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($inscricao['curso_id']); ?></td>
                        <td><?php echo htmlspecialchars($inscricao['titulo_curso']); ?></td>
                        <td><?php echo isset($inscricao['status']) ? htmlspecialchars($inscricao['status']) : 'Não especificado'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>
