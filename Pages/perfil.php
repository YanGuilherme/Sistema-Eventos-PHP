<?php 

session_start(); // Inicia a sessão

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);

$matricula = $tipoUsuario = $nome = $email =   '';

if ($usuarioLogado) {
    $matricula = $_SESSION['user']['matricula'];
    $tipoUsuario = $_SESSION['user']['tipo'];
    $nome = $_SESSION['user']['nome'];
    $email = $_SESSION['user']['email'];
}


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
    <title>Perfil</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
</head>
<body>
    <header><h1>Perfil</h1></header>
    <?php if($usuarioLogado)
    ?>
        
        <nav><a href="./inicio.php">Inicio</a>
        <a href="sobre.php"> Sobre</a>

            <?php if ($tipoUsuario === 'aluno'): ?>
                <a href="eventos_user.php">Meus eventos</a>
            <?php endif; ?>
            <?php if ($tipoUsuario === 'administrador'): ?>
                <a href="dashboard_adm.php">Dashboard Admin</a>
                <a href="relatorios.php">Relatórios</a>
                <a href="curso_concluido.php?curso_id=1">Marcar Conclusão de Curso</a>

            <?php endif; ?>
            <a href="ranking.php">Ranking de Participação</a>
            <a href="perfil.php">Perfil</a>
            <a href="?action=logout">Deslogar</a>
        </nav>
        <main>
            <h2>Perfil</h2>
            <h3>Dados pessoais</h3>
            <p><strong>Nome: </strong> <?php echo htmlspecialchars($nome); ?></p>
            <p><strong>Email: </strong><?php echo htmlspecialchars($email); ?></p>
            <p><strong>Matrícula: </strong><?php echo htmlspecialchars($matricula); ?></p>
            <p><strong>Tipo de usuário: </strong><?php echo htmlspecialchars($tipoUsuario); ?></p>
            <button onclick="window.location.href='editar_perfil.php'">Editar perfil</button>
        </main>

    <footer><p>Projeto prático SIN 132</p></footer>

</body>
</html>