<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'Classes/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $errorMessage = '';

    if (empty($email)) {
        $errorMessage .= 'Por favor, informe o e-mail.<br>';
    }
    if (empty($senha)) {
        $errorMessage .= 'Por favor, informe a senha.<br>';
    }

    if (empty($errorMessage)) {
        if ($email && $senha) {
            $user = Usuario::autenticar($email, $senha);

            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'nome' => $user->getNome(),
                    'email' => $user->getEmail(),
                    'matricula' => $user->getMatricula(), 
                    'tipo' => $user->getTipo(),
                ];
                header('Location: Pages/inicio.php');
                exit();
            } else {
                $errorMessage = 'Credenciais inválidas.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/estilo1.css">
</head>
<body>
    
    <header><h1>Gerenciador de Eventos</h1></header>
    
    <form action="" method="post">
        <div>
            <h2>Login</h2>
            <?php if (!empty($errorMessage)): ?>
            <p class="error">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>
            <label for="email">Email</label><br><input type="email" name="email" id="email" required><br><br>
            <label for="password">Senha</label><br><input type="password" name="senha" id="senha" required><br><br>
            <button type="submit">Login</button>
        </div>
    </form>
    <div>
            <p>Ainda não possui uma conta?</p>
            <button onclick="window.location.href='Pages/cadastro.php'">Cadastre-se</button>
    </div>
    <footer><p>Projeto prático SIN 132</p></footer>
</body>
</html>
