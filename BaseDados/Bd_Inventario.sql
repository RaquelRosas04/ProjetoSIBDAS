-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           8.0.46 - MySQL Community Server - GPL
-- SO do servidor:               Win64
-- HeidiSQL Versão:              12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- A despejar estrutura da base de dados para bd_inventario
CREATE DATABASE IF NOT EXISTS `bd_inventario` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bd_inventario`;

-- A despejar estrutura para tabela bd_inventario.edificios
CREATE TABLE IF NOT EXISTS `edificios` (
  `id` int NOT NULL,
  `nome` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `morada` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `localidade` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `codPostal` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.edificios: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.equipamentofornecedores
CREATE TABLE IF NOT EXISTS `equipamentofornecedores` (
  `idEquipamento` int NOT NULL,
  `idFornecedor` int NOT NULL,
  `Tipo Fornecedor` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Fabricante/Compra/AT',
  KEY `FK_equipamentofornecedores_fornecedores` (`idFornecedor`),
  KEY `FK_equipamentofornecedores_equipamentos` (`idEquipamento`),
  CONSTRAINT `FK_equipamentofornecedores_equipamentos` FOREIGN KEY (`idEquipamento`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentofornecedores_fornecedores` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.equipamentofornecedores: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.equipamentos
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `idTipo` int NOT NULL DEFAULT '0',
  `idMarca` int NOT NULL DEFAULT '0',
  `modelo` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `anosGarantia` int NOT NULL COMMENT 'Garantia em Anos',
  `criticidade` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_equipamentos_marca` (`idMarca`) USING BTREE,
  KEY `FK_equipamentos_tipoequipamento` (`idTipo`) USING BTREE,
  CONSTRAINT `FK_equipamentos_marca` FOREIGN KEY (`idMarca`) REFERENCES `marca` (`id`),
  CONSTRAINT `FK_equipamentos_tipoequipamento` FOREIGN KEY (`idTipo`) REFERENCES `tipoequipamento` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.equipamentos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.equipamentounidade
CREATE TABLE IF NOT EXISTS `equipamentounidade` (
  `id` int NOT NULL,
  `idEquipamento` int NOT NULL,
  `Codigo` varchar(20) NOT NULL DEFAULT '',
  `idLocalizacao` int NOT NULL,
  `idFornecedor` int NOT NULL,
  `numSerie` char(100) NOT NULL DEFAULT '',
  `estado` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `anoFabrico` int NOT NULL DEFAULT '0',
  `dataAquisicao` date NOT NULL,
  `dataFimGarantia` date NOT NULL,
  `tipoEntrada` char(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'Compra, Doação, Empréstimo',
  `obs` bit(1) NOT NULL DEFAULT (0),
  PRIMARY KEY (`id`),
  KEY `FK_equipamentounidade_localizacao` (`idLocalizacao`),
  KEY `FK_equipamentounidade_equipamentos` (`idEquipamento`) USING BTREE,
  CONSTRAINT `FK_equipamentounidade_equipamentos` FOREIGN KEY (`idEquipamento`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `FK_equipamentounidade_localizacao` FOREIGN KEY (`idLocalizacao`) REFERENCES `localizacao` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.equipamentounidade: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.fornecedores
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL,
  `nome` char(100) NOT NULL DEFAULT '',
  `morada` char(100) NOT NULL DEFAULT '',
  `localidade` char(100) NOT NULL DEFAULT '',
  `codPostal` char(8) NOT NULL DEFAULT '',
  `loccodPostal` char(50) NOT NULL DEFAULT '',
  `telemovel` char(50) NOT NULL DEFAULT '',
  `nif` char(20) NOT NULL DEFAULT '',
  `www` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.fornecedores: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.localizacao
CREATE TABLE IF NOT EXISTS `localizacao` (
  `id` int NOT NULL,
  `idEdificio` int NOT NULL,
  `idServico` int NOT NULL,
  `andar` int NOT NULL,
  `sala` char(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.localizacao: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.marca
CREATE TABLE IF NOT EXISTS `marca` (
  `id` int NOT NULL,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT=' ';

-- A despejar dados para tabela bd_inventario.marca: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.servicos
CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int NOT NULL,
  `descricao` char(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.servicos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela bd_inventario.tipoequipamento
CREATE TABLE IF NOT EXISTS `tipoequipamento` (
  `id` int NOT NULL,
  `descricao` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- A despejar dados para tabela bd_inventario.tipoequipamento: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
