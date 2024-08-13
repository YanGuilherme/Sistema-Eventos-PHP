<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Classes/Administrador.php';
include_once '../Classes/Curso.php';

$usuarioLogado = isset($_SESSION['user']) && is_array($_SESSION['user']);
$tipoUsuario = $usuarioLogado ? $_SESSION['user']['tipo'] : '';

$titulo = $descricao = $data_inicio = $data_fim = '';
$errorMessage = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $evento = $_POST['evento'] ?? '';

    if (empty($titulo)) {
        $errorMessage .= 'Por favor, informe o título.<br>';
    }
    if (empty($descricao)) {
        $errorMessage .= 'Por favor, informe a descrição.<br>';
    }

    if (empty($data)) {
        $errorMessage .= 'Por favor, informe a data.<br>';
    }
    if (empty($horario)) {
        $errorMessage .= 'Por favor, informe horário.<br>';
    }

    echo $errorMessage;



    if(empty($errorMessage)){
        $curso = new Curso(null, $titulo, $descricao, $data, $horario, $evento);
        Administrador::cadastrarCurso($curso);
        header('Location: ../Pages/dashboard_adm.php');
        exit();
    }


}

?>