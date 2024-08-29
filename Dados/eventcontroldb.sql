-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/08/2024 às 16:06
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `eventcontroldb`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(100) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `data` date NOT NULL,
  `horario` time NOT NULL,
  `evento_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`id`, `titulo`, `descricao`, `data`, `horario`, `evento_id`) VALUES
(2, 'Curso de drinks', 'fazer bebida', '2024-08-30', '05:01:00', 2),
(3, 'Corotada', 'teste', '2024-08-30', '06:40:00', 3),
(4, 'teste', 'teste', '2024-08-30', '10:31:00', 4),
(5, 'viajar', 'teste', '2024-08-31', '16:32:00', 5),
(6, 'aaaaaaaaaa', 'aaaaaaaaaa', '2024-09-05', '15:32:00', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `id` int(100) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `descricao`, `data_inicio`, `data_fim`) VALUES
(2, 'Festa da faculdade', 'festinha entre profs e alunos', '2024-08-30', '2024-08-31'),
(3, 'Corotada', 'teste', '2024-08-29', '2024-08-31'),
(4, 'teste1', 'teste', '2024-08-30', '2024-08-31'),
(5, 'teste 2222', 'teste', '2024-08-30', '2024-08-31'),
(6, 'teste mais teste', 'teste', '2024-09-04', '2024-09-05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `inscricoes`
--

CREATE TABLE `inscricoes` (
  `id` int(100) NOT NULL,
  `curso_id` int(100) NOT NULL,
  `usuario_id` int(100) NOT NULL,
  `data_inscricao` date NOT NULL,
  `status` varchar(20) DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `inscricoes`
--

INSERT INTO `inscricoes` (`id`, `curso_id`, `usuario_id`, `data_inscricao`, `status`) VALUES
(1, 2, 3, '2024-08-29', 'concluido'),
(2, 2, 4, '2024-08-29', 'concluido'),
(3, 3, 4, '2024-08-29', 'concluido'),
(4, 2, 5, '2024-08-29', 'concluido'),
(5, 3, 5, '2024-08-29', 'pendente'),
(6, 3, 6, '2024-08-29', 'pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(100) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(70) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `matricula` varchar(10) NOT NULL,
  `tipo` enum('aluno','administrador') DEFAULT 'aluno',
  `pontos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `matricula`, `tipo`, `pontos`) VALUES
(1, 'Yan G', 'yanvzp@gmail.com', '$2y$10$9Pv2Tpryz6EiVUx8Px8u6OQ4kWu/7aW2MHDjt02fIp06VkeCWIXfK', '5992', 'administrador', 0),
(2, 'Joao', 'joao@joao.com', '$2y$10$vD18mpMhjs/nCMq/j6pO2u/TsfI/y25NeiShmJu8ShrbWZVpS75wy', '7000', 'administrador', 0),
(3, 'Lucas', 'lucas@lucas.com', '$2y$10$s6KCY1MIG88z91gPbzrDm.9nEowjEmh5hN9nPfoRQpIE0Pm4HUGsy', '8000', 'aluno', 10),
(4, 'loes', 'loesgamer@gmail.com', '$2y$10$KcRL5CFOE6qvJX.zG1DbTu6M4ny7iyp9Ji1bm1MnDtKkI8S7zVy/G', '1000', 'aluno', 20),
(5, 'Pedro', 'pedro@pedro.com', '$2y$10$wEEdrpzQMIiIP882lo/qMe1G/dEQpOROVI6CylifoJ5nc7so9TfzC', '1000', 'aluno', 10),
(6, 'Alexandre', 'xande@xande.com', '$2y$10$GrcUc1rJBY8cjOdAd47nkeqA/g/0EeSQ6zAXVm.zng237YwlahH06', '7019', 'aluno', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventoID` (`evento_id`);

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `inscricoes`
--
ALTER TABLE `inscricoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_id` (`curso_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `inscricoes`
--
ALTER TABLE `inscricoes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`);

--
-- Restrições para tabelas `inscricoes`
--
ALTER TABLE `inscricoes`
  ADD CONSTRAINT `inscricoes_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `inscricoes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
