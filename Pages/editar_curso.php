<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../Classes/Curso.php';
include '../Classes/Administrador.php';

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';
$eventos = Administrador::obterEventos();

// Função de logout
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

$errorMessage = '';
$dadosCurso = null;
$cursoId = isset($_POST['id_curso']) ? $_POST['id_curso'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Buscar os dados do curso se o ID foi fornecido
    if ($cursoId) {
        $dadosCurso = Curso::buscarCursoById($cursoId);
    }
    // Se o formulário foi enviado para salvar a edição
    if (isset($_POST['id_curso']) && isset($_POST['titulo']) && isset($_POST['descricao']) && isset($_POST['data']) && isset($_POST['horario']) && isset($_POST['evento'])) {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $data = $_POST['data'];
        $horario = $_POST['horario'];
        $eventoId = $_POST['evento'];


        // Atualizar o curso no banco de dados
        $resultado = Administrador::editarCurso($cursoId, $titulo, $descricao, $data, $horario, $eventoId);
        if (strpos($resultado, "sucesso") !== false) {
            echo "Curso atualizado com sucesso!";
            header('Location: inicio.php');
            exit();
        } else {
            $errorMessage = $resultado;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar curso</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_dash.css">

</head>
<body>

    <?php if ($errorMessage): ?>
        <p style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php endif; ?>

    <header><h1>Gerenciador de Eventos</h1></header>

    <nav>
        <a href="inicio.php">Inicio</a>
        <a href="sobre.php"> Sobre</a>

        <?php if ($usuarioLogado): ?>
            <?php if ($tipoUsuario === 'aluno'): ?>
                <a href="eventos_user.php">Meus eventos</a>
            <?php endif; ?>

            <?php if ($tipoUsuario === 'administrador'): ?>
                <a href="dashboard_adm.php">Dashboard Admin</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="curso_concluido.php?curso_id=1">Marcar Conclusão de Curso</a>

            <?php endif; ?>
            <a href="perfil.php">Perfil</a>
            <a href="?action=logout">Deslogar</a>
        <?php else: ?>
            <a href="login.php">Logar</a>
            <a href="cadastro.php">Fazer cadastro</a>
        <?php endif; ?>
    </nav>

    <h2>Editar curso</h2>
    <main class="main-container">
        <div class="form-container">
            <div class="form-item">
                <form action="editar_curso.php" method="post">
                    <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($cursoId); ?>">
                    <label for="titulo">Título</label><br>
                    <input type="text" name="titulo" id="titulo" required value="<?php echo htmlspecialchars($dadosCurso->titulo ?? ''); ?>"><br>
                    <label for="descricao">Descrição</label><br>
                    <textarea required id="descricao" name="descricao" class="textarea-descricao" placeholder="Digite a descrição"><?php echo htmlspecialchars($dadosCurso->descricao ?? ''); ?></textarea><br>
                    <label for="data">Data</label><br>
                    <input required type="date" name="data" id="data" value="<?php echo htmlspecialchars($dadosCurso->data ?? ''); ?>"><br>
                    <label for="time">Horario</label><br>
                    <input required type="time" name="horario" id="horario" value="<?php echo htmlspecialchars($dadosCurso->horario ?? ''); ?>"><br>
        
                    <label>Selecione o evento:</label><br>
                            <select name="evento" id="evento" required>
                            <?php foreach ($eventos as $evento): ?>
                            <option value="<?php echo htmlspecialchars($evento['id']); ?>"
                                <?php if ($evento['id'] == $dadosCurso->evento_id) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($evento['titulo']); ?>
                            </option>
                            <?php endforeach; ?>
                            </select><br><br>
                    <button type="submit">Atualizar evento</button>
                </form>
            </div>
        </div>
    </main>

    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>
