<?php
session_start();
include_once 'connect.inc.php'; // Arquivo de conexão ao banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    echo $email . " " . $senha;

    // Consulta o banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verifica a senha
        if (password_verify($senha, $user['senha'])) {
            // Senha correta, cria uma sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $_SESSION['user_nome'] = $user['nome'];

            // Redireciona para a página inicial
            header("Location: dashboard.php");
            exit();
        } else {
            // Senha incorreta
            echo "E-mail ou senha inválidos.1";
        }
    } else {
        // E-mail não encontrado
        echo "E-mail ou senha inválidos.2";
    }
}
?>
