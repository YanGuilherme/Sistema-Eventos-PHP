<?php

class Curso {
    private $id;
    private $titulo;
    private $descricao;
    private $data;
    private $horario;
    private $eventoId; // foreign key

    // Construtor da classe
    public function __construct($id = null, $titulo = '', $descricao = '', $data = '', $horario = '', $eventoId = null) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->data = $data;
        $this->horario = $horario;
        $this->eventoId = $eventoId;
    }

    // Getters e Setters
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

    // Listar cursos por evento
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

    // Listar cursos nos quais o usuário está inscrito
    public static function listarCursosUsuario($user_id) {
        $conn = getConnection();
        $cursos = [];
    
        $sql = "SELECT c.* FROM cursos c 
                INNER JOIN inscricoes i ON c.id = i.curso_id 
                WHERE i.usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
    
        if ($stmt->execute()) {
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
        } else {
            $stmt->close();
            $conn->close();
            return [];
        }
    }

    // Buscar curso por ID
    public static function buscarCursoById($id) {
        $conn = getConnection();
        $sql = "SELECT * FROM cursos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object();
    }

    public static function pesquisarCursos($searchQuery, $offset, $limit) {
        $conn = getConnection();
        $sql = "SELECT * FROM cursos WHERE titulo LIKE ? LIMIT ?, ?";
        $searchQuery = "%{$searchQuery}%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $searchQuery, $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $cursos = [];
        while ($row = $result->fetch_assoc()) {
            $cursos[] = new Curso(
                $row['id'],
                $row['titulo'],
                $row['descricao'],
                $row['data'],
                $row['horario'],
                $row['evento_id']
            );
        }

        $stmt->close();
        $conn->close();

        return $cursos;
    }


    // Marcar curso como concluído e adicionar pontos ao aluno
    public static function concluirCurso($user_id, $curso_id) {
        $conn = getConnection();

        // Verificar se o curso já foi concluído
        $sqlCheck = "SELECT status FROM inscricoes WHERE usuario_id = ? AND curso_id = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $user_id, $curso_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $row = $resultCheck->fetch_assoc();

        if ($row && $row['status'] === 'concluido') {
            $stmtCheck->close();
            $conn->close();
            return "Este curso já foi concluído por este aluno.";
        }

        $stmtCheck->close();

        // Marcar o curso como concluído e adicionar os pontos ao usuário
        $sqlUpdate = "UPDATE inscricoes SET status = 'concluido' WHERE usuario_id = ? AND curso_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ii", $user_id, $curso_id);

        if ($stmtUpdate->execute()) {
            $sqlUpdatePoints = "UPDATE usuarios SET pontos = pontos + 10 WHERE id = ?";
            $stmtPoints = $conn->prepare($sqlUpdatePoints);
            $stmtPoints->bind_param("i", $user_id);
            $stmtPoints->execute();
            $stmtPoints->close();
        }

        $stmtUpdate->close();
        $conn->close();

        return "Curso concluído e pontos atribuídos ao aluno.";
    }

    public static function listarTodosCursos() {
        $conn = getConnection();
        $cursos = [];
    
        $sql = "SELECT * FROM cursos";
        $stmt = $conn->prepare($sql);
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

    public static function listarCursosPaginados($offset, $limit) {
        $conn = getConnection();
        $sql = "SELECT * FROM cursos LIMIT ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $cursos = [];
        while ($row = $result->fetch_assoc()) {
            $cursos[] = new Curso(
                $row['id'],
                $row['titulo'],
                $row['descricao'],
                $row['data'],
                $row['horario'],
                $row['evento_id']
            );
        }

        $stmt->close();
        $conn->close();

        return $cursos;
    }

    public static function contarTodosCursos() {
        $conn = getConnection();
        $sql = "SELECT COUNT(*) as total FROM cursos";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public static function contarCursosFiltrados($searchQuery) {
        $conn = getConnection();
        $sql = "SELECT COUNT(*) as total FROM cursos WHERE titulo LIKE ?";
        $searchQuery = "%{$searchQuery}%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    

    function verificarConflitoHorario() {}
}


