<?php 

session_start();
include '../Classes/Usuario.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$errorMessage = '';


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $tipo = 'aluno';

    $user = new Usuario(null, $nome, $email, $matricula, $senhaHash, $tipo);
    $errorMessage = $user->cadastrarUser();

    if (empty($errorMessage)) {
        header('Location: ../index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../CSS/estilo1.css">
</head>
<body>
    <header><h1>Gerenciador de Eventos</h1></header>
    <h2>Crie já sua conta</h2>
    <h3>Dados pessoais</h3>
    <form action="cadastro.php" method="post">
        <?php if (!empty($errorMessage)): ?>
            <p class="error">
                <?php echo htmlspecialchars($errorMessage); ?>
            </p>
        <?php endif; ?>
        <p>Email</p>
        <input type="email" name="email" id="email" required>
        <p>Nome</p>
        <input type="text" name="nome" id="nome" required>
        <p>Matrícula</p>
        <input type="text" name="matricula" id="matricula" required>
        <p>Senha</p>
        <input type="password" name="senha" id="senha" required><br><br>
        <button type="submit">Criar conta</button>
    </form>
    <p>Já possui uma conta?</p>
    <button onclick="window.location.href='../index.php'">Fazer login</button>
    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>

