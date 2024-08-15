<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Classes/Administrador.php';
session_start();

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmar_exclusao'])) {
        // O usuário confirmou a exclusão
        $eventoId = $_POST['id_evento'];
        $resultado = Administrador::excluirEvento($eventoId);

        // Defina a mensagem na sessão
        $_SESSION['mensagem'] = $resultado ? 'Evento excluído com sucesso.' : 'Erro ao excluir evento.';

        // Redirecione para a página inicial
        header('Location: ../Pages/inicio.php');
        exit();
    } elseif (isset($_POST['cancelar_exclusao'])) {
        // O usuário cancelou a exclusão
        $_SESSION['mensagem'] = 'Exclusão cancelada.';
        header('Location: ../Pages/inicio.php');
        exit();
    }
}

// Exibir o formulário de confirmação se o ID do evento estiver definido
if (isset($_POST['id_evento'])) {
    $eventoId = $_POST['id_evento'];
    echo '
    <h2>Confirmar Exclusão</h2>
    <p>Tem certeza de que deseja excluir o evento?</p>
    <form method="post">
        <input type="hidden" name="id_evento" value="' . htmlspecialchars($eventoId) . '">
        <button type="submit" name="confirmar_exclusao">Confirmar</button>
        <button type="submit" name="cancelar_exclusao">Cancelar</button>
    </form>';
} else {
    // Caso o ID do evento não esteja presente, redirecione ou exiba uma mensagem de erro
    $_SESSION['mensagem'] = 'Nenhum evento especificado para exclusão.';
    header('Location: ../Pages/inicio.php');
    exit();
}
?>
