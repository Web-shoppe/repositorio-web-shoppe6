-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para siscrud
CREATE DATABASE IF NOT EXISTS `siscrud` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `siscrud`;

-- Copiando estrutura para tabela siscrud.produto
CREATE TABLE IF NOT EXISTS `produto` (
  `id_prod` int(11) NOT NULL AUTO_INCREMENT,
  `nome_prod` varchar(255) NOT NULL,
  `valor_prod` decimal(10,2) NOT NULL,
  `dt_valid_prod` date NOT NULL,
  PRIMARY KEY (`id_prod`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela siscrud.produto: ~2 rows (aproximadamente)
REPLACE INTO `produto` (`id_prod`, `nome_prod`, `valor_prod`, `dt_valid_prod`) VALUES
	(1, 'Moto G84', 1700.00, '2030-07-06'),
	(2, 'Contrabaixo Tagima', 1200.00, '2030-12-06');

-- Copiando estrutura para tabela siscrud.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(25) DEFAULT NULL,
  `senha` varchar(40) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nivel` int(1) unsigned DEFAULT 1,
  `ativo` tinyint(1) DEFAULT 1,
  `cadastro` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  KEY `nivel` (`nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Copiando dados para a tabela siscrud.usuario: 4 rows
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
REPLACE INTO `usuario` (`id`, `usuario`, `senha`, `email`, `nivel`, `ativo`, `cadastro`) VALUES
	(1, 'admin1', '123', 'admin1@gmail.com', 1, 1, '2024-09-06'),
	(2, 'admin2', '123', 'admin2@gmail.com', 2, 1, '2024-09-06'),
	(3, 'admin3', '123', 'admin3@gmail.com', 3, 1, '2024-09-06'),
	(4, 'Admin4', '1234', 'admin4@gmail.com', 2, 1, '2024-09-06');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
