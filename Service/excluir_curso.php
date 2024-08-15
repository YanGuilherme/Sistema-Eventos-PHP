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
        $cursoId = $_POST['id_curso'];
        $resultado = Administrador::excluirCurso($cursoId);

        // Defina a mensagem na sessão
        $_SESSION['mensagem'] = $resultado ? 'Curso excluído com sucesso.' : 'Erro ao excluir curso.';

        // Redirecione para a página inicial
        header('Location: ../Pages/inicio.php');
        exit();
    } elseif (isset($_POST['cancelar_exclusao'])) {
        // O usuário cancelou a exclusão
        $_SESSION['mensagem'] = 'Exclusão de curso cancelada.';
        header('Location: ../Pages/inicio.php');
        exit();
    }
}

// Exibir o formulário de confirmação se o ID do curso estiver definido
if (isset($_POST['id_curso'])) {
    $cursoId = $_POST['id_curso'];
    echo '
    <h2>Confirmar Exclusão</h2>
    <p>Tem certeza de que deseja excluir o curso?</p>
    <form method="post">
        <input type="hidden" name="id_curso" value="' . htmlspecialchars($cursoId) . '">
        <button type="submit" name="confirmar_exclusao">Confirmar</button>
        <button type="submit" name="cancelar_exclusao">Cancelar</button>
    </form>';
} else {
    echo htmlspecialchars($cursoId);
    // Caso o ID do curso não esteja presente, redirecione ou exiba uma mensagem de erro
    $_SESSION['mensagem'] = 'Nenhum curso especificado para exclusão.';
    header('Location: ../Pages/inicio.php');
    exit();
}
?>
