-- --------------------------------------------------------
-- Anfitrião:                    vsgate-s1.dei.isep.ipp.pt
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- SO do servidor:               Linux
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- A despejar estrutura da base de dados para db1230798
CREATE DATABASE IF NOT EXISTS `db1230798` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db1230798`;

-- A despejar estrutura para tabela db1230798.edificios
CREATE TABLE IF NOT EXISTS `edificios` (
  `id` int NOT NULL,
  `nome` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `morada` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `localidade` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `codPostal` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.edificios: ~2 rows (aproximadamente)
REPLACE INTO `edificios` (`id`, `nome`, `morada`, `localidade`, `codPostal`) VALUES
	(1, 'Trofa Saude Penafiel', '', 'Penafiel', ''),
	(2, 'Trofa Saude Porto', '', 'Porto', '');

-- A despejar estrutura para tabela db1230798.equipamentocadastro
CREATE TABLE IF NOT EXISTS `equipamentocadastro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idequipamento` int DEFAULT NULL,
  `idlocalizacao` int DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentocadastro_equipamentos` (`idequipamento`),
  KEY `FK_equipamentocadastro_localizacao` (`idlocalizacao`),
  CONSTRAINT `FK_equipamentocadastro_equipamentos` FOREIGN KEY (`idequipamento`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentocadastro_localizacao` FOREIGN KEY (`idlocalizacao`) REFERENCES `localizacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1230798.equipamentocadastro: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1230798.equipamentocomponentes
CREATE TABLE IF NOT EXISTS `equipamentocomponentes` (
  `idEquiPai` int NOT NULL,
  `idEquiComp` int NOT NULL,
  KEY `FK_equipamentocomponentes_equipamentos` (`idEquiPai`),
  KEY `FK_equipamentocomponentes_equipamentos_2` (`idEquiComp`),
  CONSTRAINT `FK_equipamentocomponentes_equipamentos` FOREIGN KEY (`idEquiPai`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentocomponentes_equipamentos_2` FOREIGN KEY (`idEquiComp`) REFERENCES `equipamentos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.equipamentocomponentes: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1230798.equipamentocontratos
CREATE TABLE IF NOT EXISTS `equipamentocontratos` (
  `id` int DEFAULT NULL,
  `idEquipamentoUni` int DEFAULT NULL,
  `datainicio` date DEFAULT NULL,
  `datafim` date DEFAULT NULL,
  `obs` text COLLATE utf8mb4_bin,
  `caminho` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `ficheiro` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1230798.equipamentocontratos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1230798.equipamentofornecedores
CREATE TABLE IF NOT EXISTS `equipamentofornecedores` (
  `idEquipamentoUni` int NOT NULL,
  `idFornecedor` int NOT NULL,
  `TipoFornecedor` enum('','Fabricante','Distribuidor','AT','Consumiveis') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Fabricante/Compra/AT',
  KEY `FK_equipamentofornecedores_fornecedores` (`idFornecedor`),
  KEY `FK_equipamentofornecedores_equipamentos` (`idEquipamentoUni`) USING BTREE,
  CONSTRAINT `FK_equipamentofornecedores_equipamentounidade` FOREIGN KEY (`idEquipamentoUni`) REFERENCES `equipamentounidade` (`id`),
  CONSTRAINT `FK_equipamentofornecedores_fornecedores` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.equipamentofornecedores: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1230798.equipamentos
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `idTipo` int NOT NULL DEFAULT '0',
  `idMarca` int NOT NULL DEFAULT '0',
  `modelo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `anosGarantia` int NOT NULL COMMENT 'Garantia em Anos',
  `criticidade` enum('','Baixa','Média','Alta','Suporte de vida') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `componente` bit(1) NOT NULL DEFAULT (0),
  `idfabricante` int DEFAULT NULL,
  `manualSer` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `manualTec` varchar(250) DEFAULT NULL,
  `manualCon` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentos_marca` (`idMarca`) USING BTREE,
  KEY `FK_equipamentos_tipoequipamento` (`idTipo`),
  KEY `FK_equipamentos_fabricante` (`idfabricante`),
  CONSTRAINT `FK_equipamentos_fabricante` FOREIGN KEY (`idfabricante`) REFERENCES `fabricante` (`id`),
  CONSTRAINT `FK_equipamentos_marca` FOREIGN KEY (`idMarca`) REFERENCES `marca` (`id`),
  CONSTRAINT `FK_equipamentos_tipoequipamento` FOREIGN KEY (`idTipo`) REFERENCES `tipoequipamento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.equipamentos: ~2 rows (aproximadamente)
REPLACE INTO `equipamentos` (`id`, `descricao`, `idTipo`, `idMarca`, `modelo`, `anosGarantia`, `criticidade`, `componente`, `idfabricante`, `manualSer`, `manualTec`, `manualCon`) VALUES
	(67, 'Monitor Multipramérico', 1, 1, 'IntelliVue MX450', 3, 'Alta', b'0', 1, NULL, NULL, NULL),
	(68, 'Ventilador Pulmonara', 2, 2, 'Evita V600', 3, 'Alta', b'0', 2, NULL, NULL, NULL);

-- A despejar estrutura para tabela db1230798.equipamentounidade
CREATE TABLE IF NOT EXISTS `equipamentounidade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEquipamento` int NOT NULL,
  `Codigo` varchar(20) NOT NULL DEFAULT '',
  `idLocalizacao` int NOT NULL,
  `numSerie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `estado` enum('Ativo','Inativo','Manutenção','Calibração','Quarentena','Abatido') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `anoFabrico` int NOT NULL DEFAULT '0',
  `dataAquisicao` date NOT NULL,
  `dataFimGarantia` date NOT NULL,
  `tipoEntrada` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'Compra, Doação, Empréstimo',
  `obs` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentounidade_localizacao` (`idLocalizacao`),
  KEY `FK_equipamentounidade_equipamentos` (`idEquipamento`) USING BTREE,
  KEY `Codigo` (`Codigo`),
  CONSTRAINT `FK_equipamentounidade_equipamentos` FOREIGN KEY (`idEquipamento`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentounidade_localizacao` FOREIGN KEY (`idLocalizacao`) REFERENCES `localizacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.equipamentounidade: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1230798.fabricante
CREATE TABLE IF NOT EXISTS `fabricante` (
  `nome` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `webSite` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`,`nome`),
  KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1230798.fabricante: ~6 rows (aproximadamente)
REPLACE INTO `fabricante` (`nome`, `id`, `webSite`) VALUES
	('Philips Healthcare', 1, 'www.philips.com'),
	('Dräger', 2, 'www.drager.com'),
	('B.Braun', 3, 'www.bbraun.com'),
	('Zoll Medical', 4, 'www.zollmedical.com'),
	('Mindray', 5, 'www.mindray.com'),
	('GE Healthcare', 6, 'www.gehealtcare.com');

-- A despejar estrutura para tabela db1230798.fornecedores
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` char(100) NOT NULL DEFAULT '',
  `morada` char(100) NOT NULL DEFAULT '',
  `localidade` char(100) NOT NULL DEFAULT '',
  `codPostal` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `telefone` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `nif` char(20) NOT NULL DEFAULT '',
  `www` char(100) NOT NULL DEFAULT '',
  `email` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.fornecedores: ~6 rows (aproximadamente)
REPLACE INTO `fornecedores` (`id`, `nome`, `morada`, `localidade`, `codPostal`, `telefone`, `nif`, `www`, `email`) VALUES
	(1, 'MedicalCare Portugal', 'Rua da Saude 10, Porto', '', '4000-100', '229111222', '501234567', 'www.medicalcare.pt', 'geral@medicalcare.pt'),
	(2, 'Hospitecnica', 'Av. Central 45, Lisboa', '', '1000-200', '213222333', '502345678', 'www.hospitecnica.pt', 'comercial@hospitecnica.pt'),
	(3, 'BioMed Norte', 'Rua Industrial 8, Braga', '', '4700-300', '252333444', '503456789', 'www.biomednorte.pt', 'info@biomednorte.pt'),
	(4, 'TecnoHospital', 'Rua do Hospital 21, Leiria', '', '2400-150', '244444555', '504567890', 'www.tecnohosp.pt', 'apoio@tecnohosp.pt'),
	(5, 'EquipSaude', 'Rua Nova 17, Gaia', '', '4400-120', '226555666', '505678901', 'www.equipsaude.pt', 'geral@equipsaude.pt'),
	(6, 'Clinimed', 'Av. Europa 12, Coimbra', '', '3000-050', '239666777', '506789012', 'www.clinimed.pt', 'contacto@clinimed.pt');

-- A despejar estrutura para tabela db1230798.localizacao
CREATE TABLE IF NOT EXISTS `localizacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEdificio` int NOT NULL,
  `idServico` int NOT NULL,
  `andar` int NOT NULL,
  `sala` char(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.localizacao: ~5 rows (aproximadamente)
REPLACE INTO `localizacao` (`id`, `idEdificio`, `idServico`, `andar`, `sala`) VALUES
	(1, 2, 3, 5, '321'),
	(3, 1, 4, 3, '304'),
	(4, 1, 3, 2, '222'),
	(6, 2, 1, 6, '101-112'),
	(12, 2, 1, 5, 'E');

-- A despejar estrutura para tabela db1230798.marca
CREATE TABLE IF NOT EXISTS `marca` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT=' ';

-- A despejar dados para tabela db1230798.marca: ~7 rows (aproximadamente)
REPLACE INTO `marca` (`id`, `descricao`) VALUES
	(1, 'Philips'),
	(2, 'Drager'),
	(3, 'Braun'),
	(4, 'Zoll'),
	(5, 'Pentax'),
	(6, 'Mindray'),
	(7, 'GE');

-- A despejar estrutura para tabela db1230798.servicos
CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.servicos: ~7 rows (aproximadamente)
REPLACE INTO `servicos` (`id`, `descricao`) VALUES
	(1, 'Cardiologia'),
	(2, 'Pneumologia'),
	(3, 'Pediatria'),
	(4, 'Gastroentrologia'),
	(5, 'Urgência'),
	(6, 'Imagiologia'),
	(7, 'Bloco Operatório');

-- A despejar estrutura para tabela db1230798.tipoequipamento
CREATE TABLE IF NOT EXISTS `tipoequipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `identcod` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela db1230798.tipoequipamento: ~6 rows (aproximadamente)
REPLACE INTO `tipoequipamento` (`id`, `descricao`, `identcod`) VALUES
	(1, 'Monitorizacão', 'MO'),
	(2, 'Suporte de Vida', 'SV'),
	(3, 'Terapia', 'TP'),
	(4, 'Diagnóstico', 'DG'),
	(5, 'Esterilização', 'ES'),
	(6, 'Componentes', 'CP');

-- A despejar estrutura para tabela db1230798.users
CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `perfil` enum('Gestor','Tecnico') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Nome` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1230798.users: ~4 rows (aproximadamente)
REPLACE INTO `users` (`email`, `password`, `perfil`, `Nome`) VALUES
	('gestor@medint.pt', '$2y$10$fPuLOVdZObB3AcELzZdUAuvLXWjuRf7WNcbaTRPhiJb.zwey8qBTe', 'Gestor', 'gestor'),
	('tecnico@medint.pt', '$2y$10$wjgTNxCYmEFl6ng/LqlPOe5hB2Xu7/ooNgQxMZVDoC5YEmdJiCqne', 'Tecnico', 'tecnico'),
	('paulo.rosas@medint.pt', '$2y$10$du4oPrmRebFjTsR0YBJiVuQP4ZtK5gbat02JhWzpHNZ5z1YoeZv5C', 'Gestor', 'Paulo'),
	('sandracardoso@medint.pt', '$2y$10$xgsL01TyH2qJ9Pbdkfr6SOZ/GcYUGP3twIyJ1dr4k9C96GC18FI2.', 'Tecnico', 'Sandra');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
