-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/05/2026 às 20:06
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
-- Banco de dados: `cms`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_publicacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `titulo` varchar(255) NOT NULL,
  `resumo` text NOT NULL,
  `conteudo` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`id`, `usuario_id`, `data_publicacao`, `titulo`, `resumo`, `conteudo`) VALUES
(3, 0, '2026-05-22 17:41:43', 'Por que PHP é tão interessante?', 'PHP é uma linguagem de programação muito utilizada no desenvolvimento web, especialmente para criar sites dinâmicos e sistemas interativos.', 'PHP é uma linguagem de programação muito utilizada no desenvolvimento web, especialmente para criar sites dinâmicos e sistemas interativos. \r\nEla funciona no lado do servidor, processando informações antes de enviar o conteúdo para o navegador do usuário. Por possuir sintaxe relativamente simples e grande compatibilidade com bancos de dados, o PHP é bastante popular entre iniciantes e empresas. A linguagem também conta com uma vasta comunidade e diversos frameworks que facilitam o desenvolvimento. \r\nAtualmente, o PHP continua sendo amplamente usado em aplicações web modernas e sistemas de gerenciamento de conteúdo.'),
(4, 1, '2026-05-22 22:47:07', 'Por que PHP é tão interessante?', 'PHP é uma linguagem de programação muito utilizada no desenvolvimento web, especialmente para criar sites dinâmicos e sistemas interativos.', 'PHP é uma linguagem de programação muito utilizada no desenvolvimento web, especialmente para criar sites dinâmicos e sistemas interativos. Ela funciona no lado do servidor, processando informações antes de enviar o conteúdo para o navegador do usuário. Por possuir sintaxe relativamente simples e grande compatibilidade com bancos de dados, o PHP é bastante popular entre iniciantes e empresas. A linguagem também conta com uma vasta comunidade e diversos frameworks que facilitam o desenvolvimento. Atualmente, o PHP continua sendo amplamente usado em aplicações web modernas e sistemas de gerenciamento de conteúdo.'),
(5, 2, '2026-05-22 22:54:24', 'Por que PHP é tão interessante?', 'PHP é legal sim', 'PHP é uma linguagem de programação muito utilizada no desenvolvimento web, especialmente para criar sites dinâmicos e sistemas interativos. Ela funciona no lado do servidor, processando informações antes de enviar o conteúdo para o navegador do usuário. Por possuir sintaxe relativamente simples e grande compatibilidade com bancos de dados, o PHP é bastante popular entre iniciantes e empresas. A linguagem também conta com uma vasta comunidade e diversos frameworks que facilitam o desenvolvimento. Atualmente, o PHP continua sendo amplamente usado em aplicações web modernas e sistemas de gerenciamento de conteúdo.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `username`, `email`, `senha`, `criado_em`) VALUES
(2, 'Maria Luísa Barreto', 'mlbcr', 'malubacari@gmail.com', '$2y$10$MjxFIsx/Q.Rg8wz0OM/NhOBXJ1SB.qBdG8YgXlIoaGGz6hNW4taMK', '2026-05-22 17:54:04'),
(3, 'Teste', 'teste', 'teste@gmail.com', '$2y$10$mk3RQZKWTHvNKA/Nzn4I7.8jVmhGuuXnhZa7dbNW5buNPL19VA9w6', '2026-05-22 17:54:52');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
