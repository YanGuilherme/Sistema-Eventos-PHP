<?php
require_once '../Classes/Evento.php';
require_once '../Classes/Curso.php';
require_once '../Classes/Usuario.php';
session_start();

// Verifique se o usuário é um administrador
if ($_SESSION['user']['tipo'] !== 'administrador') {
    echo "Acesso negado.";
    exit();
}

$mensagem = '';

// Lidar com a marcação de conclusão do curso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_id = isset($_POST['curso_id']) ? intval($_POST['curso_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    if ($curso_id !== 0 && $user_id !== 0) {
        $usuario = Usuario::getUserById($user_id); // Obter a instância do usuário
        if ($usuario) {
            $mensagem = $usuario->concluirCurso($curso_id); // Chamar o método corretamente
        } else {
            $mensagem = "Usuário não encontrado.";
        }
    } else {
        $mensagem = "Curso ou aluno não especificado.";
    }
}

// Listar todos os eventos
$eventos = Evento::listarEventos();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Marcar Conclusão de Curso</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>
    
    <nav>
        <a href="inicio.php">Inicio</a>
        <?php if ($_SESSION['user']['tipo'] === 'aluno'): ?>
            <a href="eventos_user.php">Meus eventos</a>
        <?php endif; ?>

        <?php if ($_SESSION['user']['tipo'] === 'administrador'): ?>
            <a href="dashboard_adm.php">Dashboard Admin</a>
            <a href="curso_concluido.php">Gerenciar conclusão cursos</a>
        <?php endif; ?>
        <a href="perfil.php">Perfil</a>
        <a href="ranking.php">Ranking de Participação</a>
        <a href="?action=logout">Deslogar</a>
    </nav>

    <h1>Marcar Conclusão de Cursos</h1>

    <?php if (!empty($mensagem)): ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>

    <?php foreach ($eventos as $evento): ?>
        <h2><?php echo htmlspecialchars($evento->getTitulo()); ?></h2>
        <p><?php echo htmlspecialchars($evento->getDescricao()); ?></p>
        <p><strong>Data de Início:</strong> <?php echo htmlspecialchars($evento->getDataInicio()); ?></p>
        <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars($evento->getDataFim()); ?></p>

        <?php
        $cursos = Curso::listarCursosPorEvento($evento->getId());
        foreach ($cursos as $curso):
            $alunosInscritos = Usuario::listarAlunosPorCurso($curso->getId());
        ?>
            <h3><?php echo htmlspecialchars($curso->getTitulo()); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alunosInscritos)): ?>
                        <?php foreach ($alunosInscritos as $aluno): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                <td><?php echo htmlspecialchars($aluno['status']); ?></td>
                                <td>
                                    <?php if ($aluno['status'] === 'pendente'): ?>
                                        <form method="POST" action="">
                                            <input type="hidden" name="curso_id" value="<?php echo htmlspecialchars($curso->getId()); ?>">
                                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($aluno['id']); ?>">
                                            <button type="submit">Marcar como Concluído</button>
                                        </form>
                                    <?php else: ?>
                                        Concluído
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Nenhum aluno inscrito neste curso.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endforeach; ?>
</body>
</html>
