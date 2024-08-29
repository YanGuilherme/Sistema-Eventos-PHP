<?php 

require_once '../Classes/Administrador.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Inicia a sessão
$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';

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

// Verifica se o usuário é administrador
if (!$usuarioLogado || $tipoUsuario !== 'administrador') {
    echo "Acesso negado.";
    exit();
}

$eventos = Administrador::obterEventos();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
    <link rel="stylesheet" href="../CSS/estilo_dash.css">
</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>

    <nav>
        <a href="./inicio.php">Início</a>
        <a href="dashboard_adm.php">Dashboard Admin</a>
        <a href="perfil.php">Perfil</a>
        <a href="?action=logout">Deslogar</a>
    </nav>

    <main class="main-container">
        <div class="form-container">
            <div class="form-item">
                <h2>Eventos</h2>
                <h3>Criar novo evento</h3>
                <form action="../Service/criar_evento.php" method="post">
                    <label for="title">Título</label><br>
                    <input type="text" name="titulo" id="titulo" required><br>
                    <label for="description">Descrição</label><br>
                    <textarea required id="descricao" name="descricao" class="textarea-descricao" placeholder="Digite a descrição"></textarea><br>
                    <label for="date">Data de início</label><br>
                    <input required type="date" name="data_inicio" id="data_inicio"><br>
                    <label for="date">Data de término</label><br>
                    <input required type="date" name="data_fim" id="data_fim"><br><br>
                    <button type="submit">Criar evento</button>
                </form>
            </div>

            <div class="form-item">
                <h2>Cursos</h2>
                <h3>Criar novo curso</h3>
                <form action="../Service/criar_curso.php" method="post">
                    <label for="title">Título</label><br>
                    <input type="text" name="titulo" id="titulo" required><br>
                    <label for="description">Descrição</label><br>
                    <textarea required id="descricao" name="descricao" class="textarea-descricao" placeholder="Digite a descrição"></textarea><br>
                    <label for="date">Data</label><br>
                    <input required type="date" name="data" id="data"><br>
                    <label for="time">Horário</label><br>
                    <input required type="time" name="horario" id="horario"><br>
                    <label>Selecione o evento:</label><br>
                    <select name="evento" id="evento" required>
                        <?php if (!empty($eventos)): ?>
                            <?php foreach ($eventos as $evento): ?>
                                <option value="<?php echo htmlspecialchars($evento['id']); ?>">
                                    <?php echo htmlspecialchars($evento['titulo']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Nenhum evento disponível</option>
                        <?php endif; ?>
                    </select><br><br>
                    <button type="submit">Criar curso</button>
                </form>
            </div>
        </div>
    </main>

    <footer><p>Projeto prático SIN 132</p></footer>

</body>
</html>
