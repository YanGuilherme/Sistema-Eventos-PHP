<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../Classes/Administrador.php';

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';

$titulo = $descricao = $data_inicio = $data_fim = '';
$errorMessage = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim = $_POST['data_fim'] ?? '';

    if (empty($titulo)) {
        $errorMessage .= 'Por favor, informe o título.<br>';
    }
    if (empty($descricao)) {
        $errorMessage .= 'Por favor, informe a descrição.<br>';
    }

    if (empty($data_inicio)) {
        $errorMessage .= 'Por favor, informe a data de início.<br>';
    }
    if (empty($data_fim)) {
        $errorMessage .= 'Por favor, informe a data de término.<br>';
    }

    if(empty($errorMessage)){
        $evento = new Evento(null, $titulo, $descricao, $data_inicio, $data_fim);
        Administrador::cadastrarEvento($evento);
        header('Location: ../Pages/dashboard_adm.php');
        exit();
    }


}

?>
