-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18-Nov-2026 às 15:04
-- Versão do servidor: 10.4.6-MariaDB
-- versão do PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `3k26_games`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `academias`
--

CREATE TABLE `academias` (
  `id_academia` int(11) NOT NULL,
  `nome_academia` varchar(150) NOT NULL,
  `email_academia` varchar(150) DEFAULT NULL,
  `telefone_academia` varchar(30) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `academias`
--

INSERT INTO `academias` (`id_academia`, `nome_academia`, `email_academia`, `telefone_academia`, `data_cadastro`) VALUES
(1, 'SISTEMA DE MONITORAMENTO DE TREINOS PARA ACADEMIAS COM FICHA DE DESEMPENHO', NULL, NULL, '2026-11-13 22:42:30');

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `id_academia` int(11) NOT NULL,
  `id_instrutor` int(11) DEFAULT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `id_operador` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `tipo` enum('treino','avaliacao','atendimento','outro') NOT NULL DEFAULT 'treino',
  `descricao` text DEFAULT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime NOT NULL,
  `status` enum('agendado','concluido','cancelado') NOT NULL DEFAULT 'agendado',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `agenda`
--

INSERT INTO `agenda` (`id`, `id_academia`, `id_instrutor`, `id_aluno`, `id_operador`, `titulo`, `tipo`, `descricao`, `inicio`, `fim`, `status`, `data_cadastro`) VALUES
(2, 1, 3, 10, 6, 'treino de pernas', 'treino', 'treino', '2026-11-17 09:36:00', '2026-11-20 09:00:00', 'agendado', '2026-11-17 12:37:12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `idade` int(11) NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plano` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_operador` int(11) NOT NULL,
  `id_conta` int(11) DEFAULT NULL,
  `id_instrutor` int(11) DEFAULT NULL,
  `id_academia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `alunos`
--

INSERT INTO `alunos` (`id`, `nome`, `idade`, `email`, `telefone`, `plano`, `data_cadastro`, `id_operador`, `id_conta`, `id_instrutor`, `id_academia`) VALUES
(10, 'teste aluno', 23, 'igor2009@gmail.com', '55992101822', 'Mensal -- R$ 80,00', '2026-11-16 23:53:14', 6, 8, 3, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `peso` decimal(6,2) NOT NULL,
  `altura` decimal(4,2) NOT NULL,
  `gordura` decimal(5,2) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_operador` int(11) NOT NULL,
  `id_academia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `avaliacoes`
--

INSERT INTO `avaliacoes` (`id`, `aluno_id`, `peso`, `altura`, `gordura`, `data_cadastro`, `id_operador`, `id_academia`) VALUES
(7, 10, '70.50', '1.76', '50.00', '2026-11-18 13:44:23', 6, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `instrutores`
--

CREATE TABLE `instrutores` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `especialidade` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_operador` int(11) NOT NULL,
  `id_conta` int(11) DEFAULT NULL,
  `id_academia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `instrutores`
--

INSERT INTO `instrutores` (`id`, `nome`, `especialidade`, `data_cadastro`, `id_operador`, `id_conta`, `id_academia`) VALUES
(3, 'carlão silva', 'musculação', '2026-11-16 22:28:44', 6, 7, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `operadores`
--

CREATE TABLE `operadores` (
  `id_operador` int(11) NOT NULL,
  `nome_operador` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_operador` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `senha_operador` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_academia` int(11) NOT NULL,
  `perfil` enum('admin','instrutor','aluno') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `operadores`
--

INSERT INTO `operadores` (`id_operador`, `nome_operador`, `email_operador`, `senha_operador`, `id_academia`, `perfil`) VALUES
(6, 'rai borges', 'rai-2889104@estudante.rs.gov.br', '$2y$10$m5XtUXE9aInZYle5ZGe6zuJ19UWdhtp4uJu/NlcbYEiT.zwKQ4/wS', 1, 'admin'),
(7, 'carlão silva', 'igor-dblau@estudante.rs.gov.br', '$2y$10$1H4DPiUfmJKoEy13WszAbO4rSHZREhILZFTquvTCsz9Y8Nebk5Ib.', 1, 'instrutor'),
(8, 'teste aluno', 'igor2009@gmail.com', '$2y$10$oyP.Cha88is0gRLHOpdbru5.DcwdH0Gv6cwonrXwh5szk9GjE5KJ2', 1, 'aluno'),
(22, 'Jose Silva', 'jose.silva@gmail.com', '$2y$10$sv.dgGgTwlBN4B3F7q4mrug20.VeJ6YLFauRCDnaN8yHvHxHirau6', 1, 'instrutor');

-- --------------------------------------------------------

--
-- Estrutura da tabela `recuperacao_senha`
--

CREATE TABLE `recuperacao_senha` (
  `id` int(11) NOT NULL,
  `id_operador` int(11) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expira_em` datetime NOT NULL,
  `usado_em` datetime DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `recuperacao_senha`
--

INSERT INTO `recuperacao_senha` (`id`, `id_operador`, `token_hash`, `expira_em`, `usado_em`, `criado_em`) VALUES
(10, 6, 'ef36532348ed856120858fd1174b33380762ef865c0f0293f94065155c24bf48', '2026-11-17 18:11:44', NULL, '2026-11-17 13:11:44');

-- --------------------------------------------------------

--
-- Estrutura da tabela `treinos`
--

CREATE TABLE `treinos` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `grupo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `exercicio` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `series` int(11) NOT NULL,
  `repeticoes` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `carga` decimal(6,2) DEFAULT 0.00,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_operador` int(11) NOT NULL,
  `id_academia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `treinos`
--

INSERT INTO `treinos` (`id`, `aluno_id`, `grupo`, `exercicio`, `series`, `repeticoes`, `carga`, `data_cadastro`, `id_operador`, `id_academia`) VALUES
(7, 10, 'Ombros', 'Supino reto', 3, '7', '35.00', '2026-11-17 11:16:27', 6, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `academias`
--
ALTER TABLE `academias`
  ADD PRIMARY KEY (`id_academia`);

--
-- Índices para tabela `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_agenda_academia` (`id_academia`),
  ADD KEY `idx_agenda_instrutor` (`id_instrutor`),
  ADD KEY `idx_agenda_aluno` (`id_aluno`),
  ADD KEY `idx_agenda_periodo` (`inicio`,`fim`);

--
-- Índices para tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_alunos_id_operador` (`id`,`id_operador`),
  ADD UNIQUE KEY `uq_alunos_id_academia` (`id`,`id_academia`),
  ADD UNIQUE KEY `uq_alunos_conta` (`id_conta`),
  ADD KEY `idx_alunos_operador` (`id_operador`),
  ADD KEY `idx_alunos_instrutor` (`id_instrutor`),
  ADD KEY `idx_alunos_academia` (`id_academia`),
  ADD KEY `fk_alunos_conta_academia` (`id_conta`,`id_academia`),
  ADD KEY `fk_alunos_instrutor_academia` (`id_instrutor`,`id_academia`);

--
-- Índices para tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_avaliacoes_operador` (`id_operador`),
  ADD KEY `fk_avaliacoes_aluno_operador` (`aluno_id`,`id_operador`),
  ADD KEY `idx_avaliacoes_academia` (`id_academia`),
  ADD KEY `fk_avaliacoes_aluno_academia` (`aluno_id`,`id_academia`),
  ADD KEY `fk_avaliacoes_operador_academia` (`id_operador`,`id_academia`);

--
-- Índices para tabela `instrutores`
--
ALTER TABLE `instrutores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_instrutores_id_academia` (`id`,`id_academia`),
  ADD UNIQUE KEY `uq_instrutores_conta` (`id_conta`),
  ADD KEY `idx_instrutores_operador` (`id_operador`),
  ADD KEY `idx_instrutores_academia` (`id_academia`),
  ADD KEY `fk_instrutores_conta_academia` (`id_conta`,`id_academia`);

--
-- Índices para tabela `operadores`
--
ALTER TABLE `operadores`
  ADD PRIMARY KEY (`id_operador`),
  ADD UNIQUE KEY `uq_operadores_email` (`email_operador`),
  ADD UNIQUE KEY `uq_operadores_id_academia` (`id_operador`,`id_academia`),
  ADD KEY `fk_operadores_academias` (`id_academia`);

--
-- Índices para tabela `recuperacao_senha`
--
ALTER TABLE `recuperacao_senha`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_hash` (`token_hash`),
  ADD KEY `idx_recuperacao_operador` (`id_operador`);

--
-- Índices para tabela `treinos`
--
ALTER TABLE `treinos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_treinos_operador` (`id_operador`),
  ADD KEY `fk_treinos_aluno_operador` (`aluno_id`,`id_operador`),
  ADD KEY `idx_treinos_academia` (`id_academia`),
  ADD KEY `fk_treinos_aluno_academia` (`aluno_id`,`id_academia`),
  ADD KEY `fk_treinos_operador_academia` (`id_operador`,`id_academia`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `academias`
--
ALTER TABLE `academias`
  MODIFY `id_academia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `instrutores`
--
ALTER TABLE `instrutores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `operadores`
--
ALTER TABLE `operadores`
  MODIFY `id_operador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `recuperacao_senha`
--
ALTER TABLE `recuperacao_senha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `treinos`
--
ALTER TABLE `treinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `fk_agenda_academia` FOREIGN KEY (`id_academia`) REFERENCES `academias` (`id_academia`);

--
-- Limitadores para a tabela `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `fk_alunos_academias` FOREIGN KEY (`id_academia`) REFERENCES `academias` (`id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alunos_conta` FOREIGN KEY (`id_conta`) REFERENCES `operadores` (`id_operador`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alunos_conta_academia` FOREIGN KEY (`id_conta`,`id_academia`) REFERENCES `operadores` (`id_operador`, `id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alunos_instrutor_academia` FOREIGN KEY (`id_instrutor`,`id_academia`) REFERENCES `instrutores` (`id`, `id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alunos_instrutores` FOREIGN KEY (`id_instrutor`) REFERENCES `instrutores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alunos_operadores` FOREIGN KEY (`id_operador`) REFERENCES `operadores` (`id_operador`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `fk_avaliacoes_academias` FOREIGN KEY (`id_academia`) REFERENCES `academias` (`id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avaliacoes_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avaliacoes_aluno_academia` FOREIGN KEY (`aluno_id`,`id_academia`) REFERENCES `alunos` (`id`, `id_academia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avaliacoes_operador_academia` FOREIGN KEY (`id_operador`,`id_academia`) REFERENCES `operadores` (`id_operador`, `id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avaliacoes_operadores` FOREIGN KEY (`id_operador`) REFERENCES `operadores` (`id_operador`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `instrutores`
--
ALTER TABLE `instrutores`
  ADD CONSTRAINT `fk_instrutores_academias` FOREIGN KEY (`id_academia`) REFERENCES `academias` (`id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_instrutores_conta` FOREIGN KEY (`id_conta`) REFERENCES `operadores` (`id_operador`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_instrutores_conta_academia` FOREIGN KEY (`id_conta`,`id_academia`) REFERENCES `operadores` (`id_operador`, `id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_instrutores_operadores` FOREIGN KEY (`id_operador`) REFERENCES `operadores` (`id_operador`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `operadores`
--
ALTER TABLE `operadores`
  ADD CONSTRAINT `fk_operadores_academias` FOREIGN KEY (`id_academia`) REFERENCES `academias` (`id_academia`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `recuperacao_senha`
--
ALTER TABLE `recuperacao_senha`
  ADD CONSTRAINT `fk_recuperacao_operador` FOREIGN KEY (`id_operador`) REFERENCES `operadores` (`id_operador`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `treinos`
--
ALTER TABLE `treinos`
  ADD CONSTRAINT `fk_treinos_academias` FOREIGN KEY (`id_academia`) REFERENCES `academias` (`id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_treinos_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_treinos_aluno_academia` FOREIGN KEY (`aluno_id`,`id_academia`) REFERENCES `alunos` (`id`, `id_academia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_treinos_operador_academia` FOREIGN KEY (`id_operador`,`id_academia`) REFERENCES `operadores` (`id_operador`, `id_academia`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_treinos_operadores` FOREIGN KEY (`id_operador`) REFERENCES `operadores` (`id_operador`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
