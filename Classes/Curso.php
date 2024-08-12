<?php 

class Curso{
    private $id;
    private $titulo;
    private $descricao;
    private $data;
    private $horario;
    private $eventoId; #foreign key

    function listarCursosPorEvento(){}
    function verificarConflitoHorario(){}
}