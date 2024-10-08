<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Evento.php';

include_once 'Usuario.php';

require_once '../Service/connect.inc.php';
class Administrador{

    public static function cadastrarEvento($evento) {
        $conn = getConnection();
    
        $sql = "INSERT INTO eventos (titulo, descricao, data_inicio, data_fim) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param("ssss", 
            $evento->getTitulo(), 
            $evento->getDescricao(), 
            $evento->getDataInicio(), 
            $evento->getDataFim()
        );
    
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
        } else {
            $stmt->close();
            $conn->close();
            return "Erro ao cadastrar evento: " . $conn->error;
        }
    }

    public static function obterEventos() {
    $conn = getConnection();
    $sql = "SELECT id, titulo FROM eventos"; 
    $result = $conn->query($sql);

    $eventos = [];
    while ($row = $result->fetch_assoc()) {
        $eventos[] = $row;
    }

    $conn->close();
    return $eventos;
    }

    public static function cadastrarCurso($curso){
        $conn = getConnection();
    
        $sql = "INSERT INTO cursos (titulo, descricao, data, horario, evento_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param("sssss", 
            $curso->getTitulo(), 
            $curso->getDescricao(), 
            $curso->getData(), 
            $curso->getHorario(),
            $curso->getEventoId()
        );
    
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
        } else {
            $stmt->close();
            $conn->close();
            return "Erro ao cadastrar o curso: " . $conn->error;
        }
    }
    public static function editarEvento($idEvento, $titulo, $descricao, $dataInicio, $dataFim){
        if (!$idEvento) {
            return "ID do evento não fornecido.";
        }
        $conn = getConnection();
    
        // Verifica se o evento existe
        $sql = "SELECT * FROM eventos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idEvento);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 0) {
            $stmt->close();
            $conn->close();
            return "Evento não encontrado.";
        }
    
        $stmt->close();
    
        $sql = "UPDATE eventos SET titulo = ?, descricao = ?, data_inicio = ?, data_fim = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $titulo, $descricao,$dataInicio,$dataFim, $idEvento);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "Evento atualizado com sucesso.";
        } else {
            $stmt->close();
            $conn->close();
            return "Erro ao atualizar evento: " . $conn->error;
        }
    }

    public static function editarCurso($cursoId, $titulo, $descricao, $data, $horario, $eventoId){

        echo $cursoId;

        if (!$cursoId) {
            return "ID do curso não fornecido. aq??";
        }
        $conn = getConnection();
    
        $sql = "SELECT * FROM cursos WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cursoId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 0) {
            $stmt->close();
            $conn->close();
            return "Curso não encontrado.";
        }
    
        $stmt->close();
    
        $sql = "UPDATE cursos SET evento_id = ?, titulo = ?, descricao = ?, data = ?, horario = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssi", $eventoId, $titulo, $descricao, $data, $horario, $cursoId);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "Curso atualizado com sucesso.";
        } else {
            $stmt->close();
            $conn->close();
            return "Erro ao atualizar curso: " . $conn->error;
        }
    }

    public static function excluirCurso($idCurso){
        $conn = getConnection();

        $sqlCursos = "DELETE FROM cursos WHERE id = ?";
        $stmtCursos = $conn->prepare($sqlCursos);
        $stmtCursos->bind_param("i", $idCurso);
        $stmtCursos->execute();
        $stmtCursos->close();

    }

    public static function excluirEvento($idEvento) {
        // Conexão com o banco de dados
        $conn = getConnection();
        
        $conn->begin_transaction();
    
        try {
            $sqlCursos = "DELETE FROM cursos WHERE evento_id = ?";
            $stmtCursos = $conn->prepare($sqlCursos);
            $stmtCursos->bind_param("i", $idEvento);
            $stmtCursos->execute();
            $stmtCursos->close();
    
            $sqlEvento = "DELETE FROM eventos WHERE id = ?";
            $stmtEvento = $conn->prepare($sqlEvento);
            $stmtEvento->bind_param("i", $idEvento);
            $stmtEvento->execute();
            $stmtEvento->close();
    
            $conn->commit();
    
            return "Evento e seus cursos associados foram excluídos com sucesso.";
        } catch (Exception $e) {
            $conn->rollback();
    
            return "Erro ao excluir o evento: " . $e->getMessage();
        } finally {
            $conn->close();
        }
    }

}