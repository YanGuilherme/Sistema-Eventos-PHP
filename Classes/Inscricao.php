<?php 

class Inscricao{
    private $id;
    private $cursoId; #foreign key
    private $usuarioId;
    private $dataInscricao;

    function inscrever(){}
    function listarInscricoesUsuario(){}
    function verificarConflitoHorario(){}
}