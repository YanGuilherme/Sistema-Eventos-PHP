<?php

require_once '/opt/lampp/htdocs/REPOSITORIO-Sistema-Eventos-PHP/Service/connect.inc.php';



class Usuario{

    private $id;
    private $nome;
    private $email;
    private $matricula;
    private $senha;

    private $tipo;

    public function __construct($id, $nome, $email, $matricula, $senha, $tipo){
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->matricula = $matricula;
        $this->senha = $senha;
        $this->tipo = $tipo;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getId() {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public static function autenticar($email, $senha){
        $conn = getConnection();
    
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
    
        
        if ($user && password_verify($senha, $user['senha'])) {
            return [
                'id' => $user['id'],
                'nome' => $user['nome'],
                'email' => $user['email'],
                'matricula' => $user['matricula'],  
                'tipo' => $user['tipo']
            ];

        } else {
            return false;
        }
    }

    function editarUser($id) {
        if (!$id) {
            return "ID do usuário não fornecido.";
        }
        $conn = getConnection();
    
        // Verifica se o usuário existe
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 0) {
            $stmt->close();
            $conn->close();
            return "Usuário não encontrado.";
        }
    
        $stmt->close();
    
        // Atualiza os dados do usuário
        $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ?, tipo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $this->nome, $this->email, $this->senha, $this->tipo, $id);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return "Usuário atualizado com sucesso.";
        } else {
            $stmt->close();
            $conn->close();
            return "Erro ao atualizar usuário: " . $conn->error;
        }
    }
    
    
    function cadastrarUser(){

        $conn = getConnection();

        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $stmt->close();
            $conn->close();
            return "E-mail já cadastrado.";
        }
    
        $stmt->close();

        $sql = "INSERT INTO usuarios (nome, email, matricula, senha, tipo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $this->nome, $this->email, $this->matricula, $this->senha, $this->tipo);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
        } else {
            $stmt->close();
            $conn->close();
            return "Erro ao cadastrar usuário: " . $conn->error;
        }
    }

    public static function buscarPorEmail($email) {
        $conn = getConnection(); // Supondo que getConnection() retorna uma conexão válida
    
        if ($conn === false) {
            die("Erro ao conectar ao banco de dados");
        }
    
        $sql = "SELECT id, nome, email, matricula, tipo FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die("Erro na preparação da consulta: " . $conn->error);
        }
    
        $stmt->bind_param("s", $email); // Bind para o email como string
    
        if (!$stmt->execute()) {
            die("Erro na execução da consulta: " . $stmt->error);
        }
    
        $result = $stmt->get_result();
    
        if ($result === false) {
            die("Erro ao obter o resultado: " . $stmt->error);
        }
    
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $usuario = new Usuario(
                $row['id'],
                $row['nome'],
                $row['email'],
                $row['matricula'],
                '', // Senha não é retornada por questões de segurança
                $row['tipo']
            );
            $stmt->close();
            $conn->close();
            return $usuario;
        } else {
            $stmt->close();
            $conn->close();
            return null; // Nenhum usuário encontrado com esse email
        }
    }
    

    public static function getUserById($id) {
        $conn = getConnection();
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_object(); // Retorna o usuário como objeto
    }
    
    
    
    

    public function getUsuarioPorEmail() {
        $conn = getConnection(); // Assumindo que você tem uma função para obter a conexão
    
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        $stmt->close();
        $conn->close();
        
        return $user; 
    }
    

    function participarEvento(){}
    function listarInscricoes(){}
    
}
