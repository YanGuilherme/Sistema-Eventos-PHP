<?php 

class Curso{
    private $id;
    private $titulo;
    private $descricao;
    private $data;
    private $horario;
    private $eventoId; #foreign key



    public static function listarCursosPorEvento($eventoId) {
        $conn = getConnection();
        $cursos = [];

        $sql = "SELECT * FROM cursos WHERE evento_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $eventoId); 

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $curso = new Curso(
                $row['id'],
                $row['titulo'],
                $row['descricao'],
                $row['data'],
                $row['horario'],
                $row['evento_id']
            );
            $cursos[] = $curso;
        }

        $stmt->close();
        $conn->close();

        return $cursos;
    }
    function verificarConflitoHorario(){}

        // Construtor da classe
    public function __construct($id = null, $titulo = '', $descricao = '', $data = '', $horario = '', $eventoId = null) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->horario = $horario;
        $this->eventoId = $eventoId;
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

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getHorario() {
        return $this->horario;
    }

    public function setHorario($horario) {
        $this->horario = $horario;
    }

    public function getEventoId() {
        return $this->eventoId;
    }

    public function setEventoId($eventoId) {
        $this->eventoId = $eventoId;
    }
    
}