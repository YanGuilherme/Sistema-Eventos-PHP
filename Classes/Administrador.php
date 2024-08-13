<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Evento.php';

include_once 'Usuario.php';

include_once '../Service/connect.inc.php';
class Administrador extends Usuario{

    public static function cadastrarEvento($evento) {
        $conn = getConnection();
    
        $sql = "INSERT INTO eventos (titulo, descricao, data_inicio, data_fim) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        // Use os getters do objeto Evento
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







    function editarEvento(){}



    function editarCurso(){}
    function gerarRelatorios(){}
    function gerenciarRanking(){}
    
}