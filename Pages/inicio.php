

<?php

include_once '../Classes/Evento.php';

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

</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>
    <?php if($usuarioLogado)
        echo "<p>Olá " . $_SESSION['user']['nome'] . " </p>";
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
    <div>
            <h2>Eventos</h2>
            <?php foreach ($eventos as $evento): ?>
                <div class="evento">
                    <h3><?php echo htmlspecialchars($evento->getTitulo()); ?></h3>
                    <p><strong>Descrição:</strong> <?php echo htmlspecialchars($evento->getDescricao()); ?></p>
                    <p><strong>Data de Início:</strong> <?php echo htmlspecialchars($evento->getDataInicio()); ?></p>
                    <p><strong>Data de Fim:</strong> <?php echo htmlspecialchars($evento->getDataFim()); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div>
            <h2>Cursos</h2>
        </div>


    </article>

    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>