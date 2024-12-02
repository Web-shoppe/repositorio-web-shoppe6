-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para web_shoppe
DROP DATABASE IF EXISTS `web_shoppe`;
CREATE DATABASE IF NOT EXISTS `web_shoppe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `web_shoppe`;

-- Copiando estrutura para tabela web_shoppe.carrinho
DROP TABLE IF EXISTS `carrinho`;
CREATE TABLE IF NOT EXISTS `carrinho` (
  `cod_carrinho` int(11) NOT NULL AUTO_INCREMENT,
  `cod_prod` int(11) NOT NULL DEFAULT 0,
  `cod_usuario` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT 1,
  PRIMARY KEY (`cod_carrinho`),
  KEY `fk_prod5` (`cod_prod`),
  KEY `cod_usuario` (`cod_usuario`),
  CONSTRAINT `FK2_usuario` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`cod_usuario`),
  CONSTRAINT `fk_prod5` FOREIGN KEY (`cod_prod`) REFERENCES `produto` (`cod_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.carrinho: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.categorias
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `nome` varchar(50) DEFAULT NULL,
  `id_prod` int(11) DEFAULT NULL,
  `cod_categoria` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`cod_categoria`),
  KEY `FK1_idProd` (`id_prod`),
  CONSTRAINT `FK1_idProd` FOREIGN KEY (`id_prod`) REFERENCES `produto` (`cod_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.categorias: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.compras
DROP TABLE IF EXISTS `compras`;
CREATE TABLE IF NOT EXISTS `compras` (
  `cod_compra` int(11) NOT NULL AUTO_INCREMENT,
  `cod_usuario` int(11) NOT NULL,
  `data_reserva` datetime NOT NULL,
  `subtotal` decimal(20,6) NOT NULL DEFAULT 0.000000,
  `frete` decimal(20,6) NOT NULL DEFAULT 0.000000,
  `total` decimal(20,6) NOT NULL DEFAULT 0.000000,
  `produtos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`produtos`)),
  `lojas` longtext DEFAULT NULL,
  PRIMARY KEY (`cod_compra`),
  KEY `cod_usuario` (`cod_usuario`),
  CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`cod_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.compras: ~0 rows (aproximadamente)
INSERT INTO `compras` (`cod_compra`, `cod_usuario`, `data_reserva`, `subtotal`, `frete`, `total`, `produtos`, `lojas`) VALUES
	(34, 29, '2024-12-02 12:42:20', 449.930000, 0.000000, 445.930000, '[{"nome":"Escova secadora","quantidade":1}]', '[17]'),
	(35, 29, '2024-12-02 12:42:53', 50.000000, 0.000000, 29.000000, '[{"nome":"NIVEA Hidratante","quantidade":1}]', '[17]');

-- Copiando estrutura para tabela web_shoppe.feedback
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `cod_feed` int(11) NOT NULL AUTO_INCREMENT,
  `comentario` varchar(150) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `id_loja` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`cod_feed`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_produto` (`id_produto`),
  KEY `id_loja` (`id_loja`),
  CONSTRAINT `FK1_usu` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`cod_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK2_pros` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`cod_produto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK3_loja` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`cod_loja`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.feedback: ~1 rows (aproximadamente)
INSERT INTO `feedback` (`cod_feed`, `comentario`, `nota`, `id_usuario`, `id_produto`, `id_loja`, `data`) VALUES
	(40, 'Ta otimo', 5, 26, 1, 17, '2024-12-02');

-- Copiando estrutura para tabela web_shoppe.forma_pagamento
DROP TABLE IF EXISTS `forma_pagamento`;
CREATE TABLE IF NOT EXISTS `forma_pagamento` (
  `form_pmgto` int(11) NOT NULL,
  `form_pagamento` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`form_pmgto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.forma_pagamento: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.historico_pag
DROP TABLE IF EXISTS `historico_pag`;
CREATE TABLE IF NOT EXISTS `historico_pag` (
  `cod_historico_pag` int(11) NOT NULL AUTO_INCREMENT,
  `cod_transacoes` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_historico_pag`),
  KEY `fk_transacoes1` (`cod_transacoes`),
  CONSTRAINT `fk_transacoes1` FOREIGN KEY (`cod_transacoes`) REFERENCES `transacoes` (`cod_transacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.historico_pag: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.historico_visualizacao
DROP TABLE IF EXISTS `historico_visualizacao`;
CREATE TABLE IF NOT EXISTS `historico_visualizacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_usuario` int(11) NOT NULL,
  `cod_produto` int(11) NOT NULL,
  `data_visualizacao` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cod_usuario` (`cod_usuario`),
  KEY `cod_produto` (`cod_produto`),
  CONSTRAINT `historico_visualizacao_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`cod_usuario`),
  CONSTRAINT `historico_visualizacao_ibfk_2` FOREIGN KEY (`cod_produto`) REFERENCES `produto` (`cod_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.historico_visualizacao: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.logradouro
DROP TABLE IF EXISTS `logradouro`;
CREATE TABLE IF NOT EXISTS `logradouro` (
  `id_logradouro` int(10) NOT NULL AUTO_INCREMENT,
  `complemento` varchar(100) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cep` varchar(50) DEFAULT NULL,
  `rua` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_logradouro`),
  KEY `cep` (`cep`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.logradouro: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.loja
DROP TABLE IF EXISTS `loja`;
CREATE TABLE IF NOT EXISTS `loja` (
  `cod_loja` int(11) NOT NULL AUTO_INCREMENT,
  `cnpj` varchar(50) NOT NULL DEFAULT '',
  `nome` varchar(50) DEFAULT NULL,
  `foto_loja` varchar(50) DEFAULT NULL,
  `banner` varchar(50) DEFAULT NULL,
  `nome_lojista` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `tel1` varchar(50) DEFAULT NULL,
  `tel2` varchar(50) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `cpf` int(11) DEFAULT NULL,
  `cep` int(11) DEFAULT NULL,
  `rua` varchar(50) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `id_usu` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_loja`) USING BTREE,
  KEY `FK1_USUA` (`id_usu`),
  CONSTRAINT `FK_loja_usuario` FOREIGN KEY (`id_usu`) REFERENCES `usuario` (`cod_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.loja: ~1 rows (aproximadamente)
INSERT INTO `loja` (`cod_loja`, `cnpj`, `nome`, `foto_loja`, `banner`, `nome_lojista`, `email`, `tel1`, `tel2`, `data_nasc`, `cpf`, `cep`, `rua`, `numero`, `complemento`, `estado`, `cidade`, `bairro`, `id_usu`) VALUES
	(17, '2222152142213', 'Old', 'transferir.jpg', 'banner.jpg', 'kopper', 'kopper@gmail.com', NULL, '219629371923', NULL, 2147483647, 21321260, 'Rua São Marciano ', 9, '12', 'Rio de Janeiro', 'RJ', 'Praça Seca', 26);

-- Copiando estrutura para tabela web_shoppe.notificacoes
DROP TABLE IF EXISTS `notificacoes`;
CREATE TABLE IF NOT EXISTS `notificacoes` (
  `cod_not` int(11) NOT NULL AUTO_INCREMENT,
  `notificacao` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cod_not`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.notificacoes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.pedidos
DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_completo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `cep` varchar(10) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `metodo_pagamento` varchar(50) NOT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `cod_loja` int(11) DEFAULT NULL,
  `cod_compra` int(11) DEFAULT NULL,
  `dados_pagamento` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_loja` (`cod_loja`) USING BTREE,
  KEY `FK_compra` (`cod_compra`) USING BTREE,
  CONSTRAINT `FK_compra` FOREIGN KEY (`cod_compra`) REFERENCES `compras` (`cod_compra`),
  CONSTRAINT `FK_loja` FOREIGN KEY (`cod_loja`) REFERENCES `loja` (`cod_loja`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.pedidos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.produto
DROP TABLE IF EXISTS `produto`;
CREATE TABLE IF NOT EXISTS `produto` (
  `cod_produto` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_produto` varchar(1000) DEFAULT NULL,
  `valor_desconto` varchar(50) DEFAULT '0',
  `fabricante_produto` varchar(50) DEFAULT NULL,
  `garantia_produto` varchar(50) DEFAULT NULL,
  `qt_produto_estocado` varchar(50) DEFAULT NULL,
  `valor_venda_produto` varchar(50) DEFAULT NULL,
  `foto_prod` varchar(50) DEFAULT NULL,
  `foto_prod2` varchar(50) DEFAULT NULL,
  `foto_prod3` varchar(50) DEFAULT NULL,
  `foto_prod4` varchar(50) DEFAULT NULL,
  `foto_prod5` varchar(50) DEFAULT NULL,
  `cod_feed` int(11) DEFAULT NULL,
  `cod_transacoes` int(11) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `qt_parcela` int(11) DEFAULT NULL,
  `id_loja` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_produto`),
  KEY `fk_feed` (`cod_feed`),
  KEY `fk_transacoes` (`cod_transacoes`),
  KEY `fkey_loja` (`id_loja`),
  CONSTRAINT `FK_id_loja` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`cod_loja`),
  CONSTRAINT `fk_feed` FOREIGN KEY (`cod_feed`) REFERENCES `feedback` (`cod_feed`),
  CONSTRAINT `fk_transacoes` FOREIGN KEY (`cod_transacoes`) REFERENCES `transacoes` (`cod_transacao`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.produto: ~4 rows (aproximadamente)
INSERT INTO `produto` (`cod_produto`, `descricao_produto`, `valor_desconto`, `fabricante_produto`, `garantia_produto`, `qt_produto_estocado`, `valor_venda_produto`, `foto_prod`, `foto_prod2`, `foto_prod3`, `foto_prod4`, `foto_prod5`, `cod_feed`, `cod_transacoes`, `nome`, `qt_parcela`, `id_loja`) VALUES
	(1, 'Escova Secadora: Praticidade e Estilo em um Único Produto\n\nA escova secadora é a combinação perfeita de secador de cabelo e escova modeladora, projetada para facilitar sua rotina de cuidados com os cabelos. Com um design ergonômico e leve, ela oferece uma experiência prática e eficiente, permitindo secar, alisar, modelar e dar volume aos fios em poucos minutos.', '4', 'Polishop', '4 ', '20', '449.93', 'escova secadora.webp', NULL, NULL, NULL, NULL, NULL, NULL, 'Escova secadora', 3, 17),
	(2, 'ouca Gorro: Conforto e Estilo em Todas as Estações\n\nA touca gorro é um acessório versátil e indispensável, perfeito para quem busca conforto, proteção e estilo em um único item. Feita com materiais macios e duráveis, ela proporciona aquecimento nos dias frios, além de ser um complemento fashion para qualquer look.', '10', 'Amazon', '2 ', '12', '18.00', 'touca gorro.webp', NULL, NULL, NULL, NULL, NULL, NULL, 'Touca Gorro', 2, 17),
	(3, 'Yves Saint Laurent (YSL): Elegância e Inovação no Mundo da Moda e Beleza\n\nYves Saint Laurent, frequentemente abreviado como YSL, é uma das marcas de luxo mais icônicas do mundo, reconhecida por sua combinação de sofisticação clássica e ousadia moderna. Fundada em 1961 pelo visionário estilista francês Yves Saint Laurent e seu parceiro Pierre Bergé, a marca redefiniu a moda com criações revolucionárias e atemporais.', '20', 'Yves Saint Laurent ', '2 ', '24', '459.00', 'perfume.webp', NULL, NULL, NULL, NULL, NULL, NULL, 'Yves Saint Laurent ', 9, 17),
	(4, 'NIVEA Hidratante: Cuidado e Hidratação para Sua Pele\n\nO hidratante NIVEA é sinônimo de cuidado e bem-estar, oferecendo fórmulas eficazes que atendem às necessidades de todos os tipos de pele. Desenvolvido com ingredientes de alta qualidade, ele proporciona hidratação intensa e duradoura, deixando a pele macia, suave e protegida ao longo do dia.', '21', 'NIVEA', '3', '10', '50', '51kTt7mfZ8L._AC_SX425_.jpg', NULL, NULL, NULL, NULL, NULL, NULL, 'NIVEA Hidratante', 12, 17);

-- Copiando estrutura para tabela web_shoppe.reservas
DROP TABLE IF EXISTS `reservas`;
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_usuario` int(11) NOT NULL,
  `data_reserva` datetime DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) NOT NULL,
  `frete` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `produtos` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cod_usuario` (`cod_usuario`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`cod_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.reservas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.seguranca
DROP TABLE IF EXISTS `seguranca`;
CREATE TABLE IF NOT EXISTS `seguranca` (
  `cod_seg` int(11) NOT NULL AUTO_INCREMENT,
  `reg_acessos` varchar(50) DEFAULT NULL,
  `tentativa_login` varchar(50) DEFAULT NULL,
  `cod_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_seg`),
  KEY `fk_usu2` (`cod_usuario`),
  CONSTRAINT `fk_usu2` FOREIGN KEY (`cod_usuario`) REFERENCES `usuario` (`cod_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.seguranca: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.transacoes
DROP TABLE IF EXISTS `transacoes`;
CREATE TABLE IF NOT EXISTS `transacoes` (
  `cod_transacao` int(11) NOT NULL AUTO_INCREMENT,
  `transacoes` varchar(50) NOT NULL DEFAULT '0',
  `cod_pag` int(11) DEFAULT NULL,
  PRIMARY KEY (`cod_transacao`) USING BTREE,
  KEY `fk_pag2` (`cod_pag`),
  CONSTRAINT `fk_pag2` FOREIGN KEY (`cod_pag`) REFERENCES `forma_pagamento` (`form_pmgto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.transacoes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela web_shoppe.usuario
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `cod_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `cod_not` int(11) DEFAULT NULL,
  `senha` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `nivel` int(1) unsigned DEFAULT 1,
  `ativo` tinyint(1) DEFAULT 1,
  `cadastro` date DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `cpf` varchar(50) DEFAULT NULL,
  `tel` varchar(50) NOT NULL DEFAULT '',
  `nome` varchar(50) DEFAULT NULL,
  `cep` int(11) DEFAULT NULL,
  `endereco` varchar(50) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cod_usuario`),
  KEY `fk_not` (`cod_not`),
  CONSTRAINT `fk_not` FOREIGN KEY (`cod_not`) REFERENCES `notificacoes` (`cod_not`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela web_shoppe.usuario: ~6 rows (aproximadamente)
INSERT INTO `usuario` (`cod_usuario`, `cod_not`, `senha`, `email`, `nivel`, `ativo`, `cadastro`, `foto`, `cpf`, `tel`, `nome`, `cep`, `endereco`, `numero`, `cidade`) VALUES
	(25, NULL, '123', 'kaua@gmail.com', 2, 1, '0000-00-00', NULL, '15662367701', '21969236318', '', 21630160, 'Rua Itajobi', 263, 'Rio de Janeiro '),
	(26, NULL, '123', 'raphael@gmail.com', 2, 1, '2024-12-02', NULL, '13708524799', '21986146122', 'Raphael', 26525160, 'Rua Agamenom Magalhães', 9, 'Nilopolis'),
	(27, NULL, '123', 'joao.silva@gmail.com', 2, 1, '2024-12-02', NULL, '123.456.789-00', '(11) 91234-5678', 'João Silva\n', 1234567, 'Rua das Flores', 123, 'São Paulo'),
	(28, NULL, '123', 'maria.oliveira@gmail.com', 2, 1, '2024-12-02', NULL, '987.654.321-00', '(21) 98765-4321', 'Maria Oliveira', 45678910, 'Rua Avenida Paulista', 456, 'Rio de Janeiro'),
	(29, NULL, '123', 'carlos.pereira@gmail.com', 1, 1, '2024-12-02', NULL, '321.654.987-00', '(31) 99876-5432', 'Carlos Pereira', 30123456, 'Rua Belo Horizonte', 789, 'Belo Horizonte'),
	(30, NULL, '123', 'marco@gmail.com', 3, 1, '2000-07-12', NULL, NULL, '21986146122', 'Marcos', 26525160, 'Rua Agamemnon Magalhães', 9, 'Nilópolis');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
