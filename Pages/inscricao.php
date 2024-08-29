<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once '../Classes/Inscricao.php';

    $dataInscricao = date("Y/m/d");


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idCurso = $_POST['id_curso'];
        $idUser = $_POST['id_user'];
    }

    $inscricao = new Inscricao(null, $idCurso, $idUser, $dataInscricao);
    $resultado = $inscricao::inscrever($idCurso, $idUser, $dataInscricao);

    if ($resultado) {
        echo '<h3>Inscrição realizada com sucesso</h3>';
        echo '<button onclick="window.location.href=\'inicio.php\'">Voltar para a página inicial</button>';
    } else {
        echo '<h3 style="color: red;">Conflito de horário</h3>';
        echo '<button onclick="window.location.href=\'inicio.php\'">Voltar para a página inicial</button>';
    }

?>