<?php

require_once '/opt/lampp/htdocs/REPOSITORIO-Sistema-Eventos-PHP/Service/connect.inc.php';
class Evento{
    private $id;
    private $titulo;
    private $descricao;
    private $dataInicio;
    private $dataFim;

    public static function listarEventos(){

        $conn = getConnection();
        $eventos = [];

        $sql = "SELECT * FROM eventos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();


        while ($row = $result->fetch_assoc()) {
            $evento = new Evento(
                $row['id'],
                $row['titulo'],
                $row['descricao'],
                $row['data_inicio'],
                $row['data_fim']
            );
            $eventos[] = $evento;
        }

        $stmt->close();
        $conn->close();

        return $eventos;

    }
    function obterDetalhesEvento(){}

    public function __construct($id = null, $titulo = '', $descricao = '', $dataInicio = '', $dataFim = '') {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->dataInicio = $dataInicio;
        $this->dataFim = $dataFim;
    }
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDataInicio() {
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicio) {
        $this->dataInicio = $dataInicio;
    }

    public function getDataFim() {
        return $this->dataFim;
    }

    public function setDataFim($dataFim) {
        $this->dataFim = $dataFim;
    }


}