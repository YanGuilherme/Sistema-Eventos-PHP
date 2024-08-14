<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include '../Classes/Administrador.php';

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';

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

$dadosEvento = null;
$idEvento = isset($_POST['id_evento']) ? $_POST['id_evento'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se o ID do evento for passado via POST, buscar os dados do evento
    if ($idEvento) {
        $dadosEvento = Evento::buscarEventoById($idEvento);
    }

    // Se o formulário foi enviado para salvar a edição
    if (isset($_POST['titulo']) && isset($_POST['descricao']) && isset($_POST['data_inicio']) && isset($_POST['data_fim'])) {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $dataInicio = $_POST['data_inicio'];
        $dataFim = $_POST['data_fim'];

        // Atualizar o evento no banco de dados
        $resultado = Administrador::editarEvento($idEvento, $titulo, $descricao, $dataInicio, $dataFim);

        if ($resultado) {
            echo "Evento atualizado com sucesso!";
            header('Location: inicio.php');
            exit();
        } else {
            $errorMessage = "Erro ao atualizar o evento.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar evento</title>
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
    </nav>
    <h2>Editar evento</h2>
    <main class="main-container">
        <div class="form-container">
            <div class="form-item">
                <form action="editar_evento.php" method="post">
                    <input type="hidden" name="id_evento" value="<?php echo htmlspecialchars($idEvento); ?>">
                    <label for="titulo">Título</label><br>
                    <input type="text" name="titulo" id="titulo" required value="<?php echo htmlspecialchars($dadosEvento->titulo ?? ''); ?>"><br>
                    <label for="descricao">Descrição</label><br>
                    <textarea required id="descricao" name="descricao" class="textarea-descricao" placeholder="Digite a descrição"><?php echo htmlspecialchars($dadosEvento->descricao ?? ''); ?></textarea><br>
                    <label for="data_inicio">Data de início</label><br>
                    <input required type="date" name="data_inicio" id="data_inicio" value="<?php echo htmlspecialchars($dadosEvento->data_inicio ?? ''); ?>"><br>
                    <label for="data_fim">Data de término</label><br>
                    <input required type="date" name="data_fim" id="data_fim" value="<?php echo htmlspecialchars($dadosEvento->data_fim ?? ''); ?>"><br><br>
                    <button type="submit">Atualizar evento</button>
                </form>
            </div>
        </div>
    </main>

    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>
