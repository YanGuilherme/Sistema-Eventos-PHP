<?php

require_once __DIR__ . '/../Service/connect.inc.php';

class Usuario {

    private $id;
    private $nome;
    private $email;
    private $matricula;
    private $senha;
    private $tipo;
    private $pontos;

    public function __construct($id, $nome, $email, $matricula, $senha, $tipo, $pontos = 0) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->matricula = $matricula;
        $this->senha = $senha;
        $this->tipo = $tipo;
        $this->pontos = $pontos;
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getPontos() {
        return $this->pontos;
    }

    public static function autenticar($email, $senha) {
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
            return new Usuario(
                $user['id'],
                $user['nome'],
                $user['email'],
                $user['matricula'],  // Capturando a matrícula corretamente
                $user['senha'],
                $user['tipo'],
                $user['pontos']
            );
        } else {
            return false;
        }
    }

    public static function getUserById($id) {
        $conn = getConnection();
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $usuario = new Usuario(
                $row['id'],
                $row['nome'],
                $row['email'],
                $row['matricula'],  // Capturando a matrícula corretamente
                '',
                $row['tipo'],
                $row['pontos']
            );
            $stmt->close();
            $conn->close();
            return $usuario;
        } else {
            $stmt->close();
            $conn->close();
            return null;
        }
    }

    public function editarUser($id) {
        if (!$id) {
            return "ID do usuário não fornecido.";
        }
        $conn = getConnection();

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

    public function cadastrarUser() {
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

    public static function listarAlunosPorCurso($curso_id) {
        $conn = getConnection();
        $alunos = [];

        $sql = "SELECT u.id, u.nome, i.status FROM usuarios u
                INNER JOIN inscricoes i ON u.id = i.usuario_id
                WHERE i.curso_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $curso_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $alunos[] = $row;
        }

        $stmt->close();
        $conn->close();

        return $alunos;
    }

    public static function listarTodosUsuarios() {
        $conn = getConnection();
        $sql = "SELECT * FROM usuarios";
        $result = $conn->query($sql);
        
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = new Usuario(
                $row['id'],
                $row['nome'],
                $row['email'],
                $row['matricula'],
                $row['senha'],
                $row['tipo'],
                $row['pontos']
            );
        }
    
        $conn->close();
        return $usuarios;
    }
    

    public function concluirCurso($curso_id) {
        $conn = getConnection();

        // Verificar se o curso já foi concluído
        $sqlCheck = "SELECT status FROM inscricoes WHERE usuario_id = ? AND curso_id = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $this->id, $curso_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $row = $resultCheck->fetch_assoc();

        if ($row['status'] === 'concluido') {
            $stmtCheck->close();
            $conn->close();
            return "Este curso já foi concluído por este aluno.";
        }

        $stmtCheck->close();

        // Marcar o curso como concluído e adicionar os pontos ao usuário
        $sqlUpdate = "UPDATE inscricoes SET status = 'concluido' WHERE usuario_id = ? AND curso_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ii", $this->id, $curso_id);

        if ($stmtUpdate->execute()) {
            $sqlUpdatePoints = "UPDATE usuarios SET pontos = pontos + 10 WHERE id = ?";
            $stmtPoints = $conn->prepare($sqlUpdatePoints);
            $stmtPoints->bind_param("i", $this->id);
            $stmtPoints->execute();
            $stmtPoints->close();
        }

        $stmtUpdate->close();
        $conn->close();

        return "Curso concluído e pontos atribuídos ao aluno.";
    }

    public static function listarRankingUsuarios() {
        $conn = getConnection();

        $sql = "SELECT nome, pontos FROM usuarios ORDER BY pontos DESC LIMIT 10";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $ranking = [];

            while ($row = $result->fetch_assoc()) {
                $ranking[] = $row;
            }

            $stmt->close();
            $conn->close();

            return $ranking;
        } else {
            $stmt->close();
            $conn->close();
            return [];
        }
    }
}
