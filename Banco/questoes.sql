-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 11-Nov-2016 às 01:43
-- Versão do servidor: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `questoes`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `arquivos`
--

CREATE TABLE `arquivos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `nome_assunto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `arquivos`
--

INSERT INTO `arquivos` (`id`, `nome`, `nome_assunto`) VALUES
(267, 'teste2.docx', 'Fonetica');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cadastro`
--

CREATE TABLE `cadastro` (
  `id` int(11) NOT NULL,
  `pergunta` longtext,
  `id_arquivos` int(11) UNSIGNED DEFAULT NULL,
  `nome_assunto` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `cadastro`
--

INSERT INTO `cadastro` (`id`, `pergunta`, `id_arquivos`, `nome_assunto`) VALUES
(8000, '\r\nEXERCÍCIOS DE FONÉTICA  \r\n', 267, NULL),
(8001, ' CEPERJ - 2012 - SEAP-RJ - Inspetor de Segurança - e Administração Penitenciária Na palavra “fazer”, notam-se 5 fonemas. O mesmo número de fonemas ocorre na palavra da seguinte alternativa:  \r\na) tatuar b-quando c) doutor d) ainda e) nada\r\n', 267, NULL),
(8002, ' TJ-SC - 2011 - TJ-SC - Técnico Judiciário - Auxiliar - Secretaria O vocábulo cujo número de letras é igual ao número de fonemas está na alternativa:   \r\na) sucesso; b) hombridade; c) gritos; d- assexuado; ', 267, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `gabarito`
--

CREATE TABLE `gabarito` (
  `id` int(11) NOT NULL,
  `id_arquivo` int(11) NOT NULL,
  `respostas` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `gabarito`
--

INSERT INTO `gabarito` (`id`, `id_arquivo`, `respostas`) VALUES
(64, 267, '1-b2-b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cadastro`
--
ALTER TABLE `cadastro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gabarito`
--
ALTER TABLE `gabarito`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arquivos`
--
ALTER TABLE `arquivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;
--
-- AUTO_INCREMENT for table `cadastro`
--
ALTER TABLE `cadastro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8003;
--
-- AUTO_INCREMENT for table `gabarito`
--
ALTER TABLE `gabarito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
