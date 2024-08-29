<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../Classes/Usuario.php';

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
$matricula = $tipoUsuario = $nome = $email = '';

// Se o usuário estiver logado, recupera os dados do usuário
if ($usuarioLogado) {
    $idUsuario = $_SESSION['user']['id'] ?? null;
    $matricula = $_SESSION['user']['matricula'] ?? '';
    $tipoUsuario = $_SESSION['user']['tipo'] ?? '';
    $nome = $_SESSION['user']['nome'] ?? '';
    $email = $_SESSION['user']['email'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idUsuario = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $user = new Usuario($idUsuario, $nome, $email, $matricula, $senhaHash, $tipoUsuario);
    $resultado = $user->editarUser($idUsuario);

    if ($resultado === "Usuário atualizado com sucesso.") {
        session_unset();
        session_destroy();
        session_start(); 

        // Recarrega os dados atualizados
        $user = Usuario::getUserById($idUsuario); // Supondo que você tenha um método para obter o usuário por ID
        $_SESSION['user'] = array(
            'id' => $user->getId(), 
            'nome' => $user->getNome(),
            'email' => $user->getEmail(),
            'tipo' => $user->getTipo(),
        );

        header('Location: perfil.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>

    <nav>
        <a href="inicio.php">Inicio</a>
        <?php if ($usuarioLogado): ?>
            <?php if ($tipoUsuario === 'aluno'): ?>
                <a href="eventos_user.php">Meus eventos</a>
            <?php endif; ?>

            <?php if ($tipoUsuario === 'administrador'): ?>
                <a href="dashboard_adm.php">Dashboard Admin</a>
                <a href="curso_concluido.php">Gerenciar conclusão cursos</a>
            <?php endif; ?>
            <a href="perfil.php">Perfil</a>
            <a href="ranking.php">Ranking de Participação</a>
            <a href="?action=logout">Deslogar</a>
        <?php else: ?>
            <a href="login.php">Logar</a>
            <a href="cadastro.php">Fazer cadastro</a>
        <?php endif; ?>
    </nav>

    <h2>Alterar dados do usuário</h2>
    <h3>Dados pessoais</h3>
    <form action="editar_perfil.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($idUsuario); ?>">
        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email) ?>"><br>
        <label for="name">Nome</label><br>
        <input type="text" name="nome" id="nome" required value="<?php echo htmlspecialchars($nome) ?>"><br>
        <label>Matrícula</label><br>
        <input type="text" name="matricula" id="matricula" value="<?php echo htmlspecialchars($matricula) ?>" disabled><br>
        <label for="password">Nova senha</label><br>
        <input type="password" name="senha" id="senha"><br><br>
        <button type="submit">Editar perfil</button>
    </form>
</body>
</html>
