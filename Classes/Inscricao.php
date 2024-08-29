<?php 

require_once '../Service/connect.inc.php';

class Inscricao {
    private $id;
    private $cursoId; // Foreign key
    private $usuarioId;
    private $dataInscricao;

    public function __construct($id, $cursoId, $usuarioId, $dataInscricao) {
        $this->id = $id;
        $this->cursoId = $cursoId;
        $this->usuarioId = $usuarioId;
        $this->dataInscricao = $dataInscricao;
    }

    public function getId() {
        return $this->id;
    }

    public function getCursoId() {
        return $this->cursoId;
    }

    public function getUsuarioId() {
        return $this->usuarioId;
    }

    public function getDataInscricao() {
        return $this->dataInscricao;
    }

    public static function inscrever($cursoId, $usuarioId, $dataInscricao) {
        $conn = getConnection();
        if (self::verificarConflitoHorario($cursoId, $usuarioId)) {
            $sql = "INSERT INTO inscricoes (curso_id, usuario_id, data_inscricao) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $cursoId, $usuarioId, $dataInscricao);
        
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                return true;
            } else {
                $stmt->close();
                $conn->close();
                return "Erro ao inscrever no curso: " . $conn->error;
            }
        } else {
            return false;
        }
    }

    public static function verificarConflitoHorario($cursoId, $usuarioId) {
        $conn = getConnection();
        
        // Consulta para obter o horário do curso
        $sql = "SELECT data, horario FROM cursos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cursoId);
        $stmt->execute();
        $resultadoCurso = $stmt->get_result();
        
        // Extrair os dados do curso
        if ($curso = $resultadoCurso->fetch_assoc()) {
            $dataCurso = $curso['data'];
            $horarioCurso = $curso['horario'];
        } else {
            // Se não houver curso com o ID fornecido, retorna false
            return false;
        }
    
        // Consulta para obter os horários já inscritos do usuário
        $sql = "SELECT data, horario FROM inscricoes i 
                JOIN cursos c ON i.curso_id = c.id 
                WHERE i.usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $resultadoInscricoes = $stmt->get_result();
        
        // Verificar conflito de horários
        while ($inscricao = $resultadoInscricoes->fetch_assoc()) {
            $dataInscricao = $inscricao['data'];
            $horarioInscricao = $inscricao['horario'];
            
            // Verifica se a data e o horário coincidem
            if ($dataCurso == $dataInscricao && $horarioCurso == $horarioInscricao) {
                return false; // Conflito de horário encontrado
            }
        }
    
        return true; // Nenhum conflito encontrado
    }

    public static function listarTodasInscricoes() {
        $conn = getConnection();
        $inscricoes = [];

        $sql = "SELECT i.usuario_id, u.nome as nome_usuario, i.curso_id, c.titulo as titulo_curso, i.data_inscricao 
                FROM inscricoes i
                JOIN usuarios u ON i.usuario_id = u.id
                JOIN cursos c ON i.curso_id = c.id";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $inscricoes[] = $row;
            }
        }

        $conn->close();
        return $inscricoes;
    }

    public static function listarInscricoesUsuario($usuarioId) {
        $conn = getConnection();
        $inscricoes = [];

        $sql = "SELECT i.curso_id, c.titulo as titulo_curso, i.data_inscricao 
                FROM inscricoes i
                JOIN cursos c ON i.curso_id = c.id 
                WHERE i.usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $inscricoes[] = $row;
            }
        }

        $stmt->close();
        $conn->close();

        return $inscricoes;
    }
}
