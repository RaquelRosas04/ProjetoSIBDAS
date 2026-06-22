/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `edificios` (
  `id` int NOT NULL,
  `nome` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `morada` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `localidade` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `codPostal` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

CREATE TABLE IF NOT EXISTS `equipamentocomponentes` (
  `idEquiPai` int NOT NULL,
  `idEquiComp` int NOT NULL,
  KEY `FK_equipamentocomponentes_equipamentos` (`idEquiPai`),
  KEY `FK_equipamentocomponentes_equipamentos_2` (`idEquiComp`),
  CONSTRAINT `FK_equipamentocomponentes_equipamentos` FOREIGN KEY (`idEquiPai`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentocomponentes_equipamentos_2` FOREIGN KEY (`idEquiComp`) REFERENCES `equipamentos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `equipamentocontratos` (
  `id` int DEFAULT NULL,
  `idEquipamentoUni` int DEFAULT NULL,
  `datainicio` date DEFAULT NULL,
  `datafim` date DEFAULT NULL,
  `obs` text COLLATE utf8mb4_bin,
  `caminho` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `ficheiro` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE IF NOT EXISTS `equipamentofornecedores` (
  `idEquipamentoUni` int NOT NULL,
  `idFornecedor` int NOT NULL,
  `TipoFornecedor` enum('','Fabricante','Distribuidor','AT','Consumiveis') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Fabricante/Compra/AT',
  KEY `FK_equipamentofornecedores_fornecedores` (`idFornecedor`),
  KEY `FK_equipamentofornecedores_equipamentos` (`idEquipamentoUni`) USING BTREE,
  CONSTRAINT `FK_equipamentofornecedores_equipamentounidade` FOREIGN KEY (`idEquipamentoUni`) REFERENCES `equipamentounidade` (`id`),
  CONSTRAINT `FK_equipamentofornecedores_fornecedores` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

CREATE TABLE IF NOT EXISTS `fabricante` (
  `nome` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `webSite` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`,`nome`),
  KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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

CREATE TABLE IF NOT EXISTS `localizacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEdificio` int NOT NULL,
  `idServico` int NOT NULL,
  `andar` int NOT NULL,
  `sala` char(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `marca` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT=' ';

CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `tipoequipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `identcod` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `password` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `perfil` enum('Gestor','Tecnico') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `Nome` varchar(50) COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
